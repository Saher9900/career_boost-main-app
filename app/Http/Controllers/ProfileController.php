<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\JobApplication;
use App\Models\Resume;
use App\Services\ResumeAnanlicesService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('profile.show', [
            'user' => $user,
            'resumeCount' => $user->resumes()->count(),
            'applicationCount' => $user->jobApplications()->count(),
            'resumes' => $user->resumes()->latest()->limit(3)->get(),
            'applications' => $user->jobApplications()
                ->with(['jobVacancy.company', 'resume'])
                ->latest()
                ->limit(4)
                ->get(),
        ]);
    }

    public function resumes(Request $request): View
    {
        return view('profile.resumes', [
            'user' => $request->user(),
            'resumes' => $request->user()->resumes()->latest()->get(),
        ]);
    }

    public function storeResume(Request $request, ResumeAnanlicesService $resumeAnalysisService): RedirectResponse
    {
        $validated = $request->validate([
            'resume' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $file = $validated['resume'];

        try {
            $resumeText = $resumeAnalysisService->extractTextFromFile($file->getPathname());

            if (trim($resumeText) === '') {
                return back()->withErrors([
                    'resume' => __('No selectable text could be extracted from this PDF. Please use a text-based PDF.'),
                ])->withInput();
            }

            $analysis = $resumeAnalysisService->analyzeText($resumeText);
        } catch (ConnectionException $exception) {
            report($exception);

            return back()->withErrors([
                'resume' => __('AI analysis took too long to respond. Please try again.'),
            ])->withInput();
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'resume' => __('Resume analysis failed. Please try again later.'),
            ])->withInput();
        }

        $fileName = 'resume_'.time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('resumes', $fileName, 'cloud');

        if (! is_string($path)) {
            return back()->withErrors([
                'resume' => __('The resume could not be saved. Please try again.'),
            ])->withInput();
        }

        Resume::create([
            'file_name' => $file->getClientOriginalName(),
            'file_url' => $path,
            'contact_details' => json_encode([
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ]),
            'user_id' => $request->user()->id,
            ...$analysis['resume'],
        ]);

        return Redirect::route('profile.resumes.index')->with('status', 'resume-uploaded');
    }

    public function applications(Request $request): View
    {
        return view('profile.applications.index', [
            'user' => $request->user(),
            'applications' => $request->user()->jobApplications()
                ->with(['jobVacancy.company', 'resume'])
                ->latest()
                ->get(),
        ]);
    }

    public function application(Request $request, JobApplication $jobApplication): View
    {
        $application = $request->user()->jobApplications()
            ->with(['jobVacancy.company', 'resume'])
            ->findOrFail($jobApplication->id);

        return view('profile.applications.show', [
            'application' => $application,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
