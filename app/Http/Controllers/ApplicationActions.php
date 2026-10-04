<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadResumeRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Services\ResumeAnanlicesService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ApplicationActions extends Controller
{
    public function showForm(JobVacancy $jobVacancy)
    {
        $existingResumes = Resume::where('user_id', Auth::id())->latest()->get();

        return view('actions.apply-page', compact('jobVacancy', 'existingResumes'));
    }

    public function uploadResume(
        UploadResumeRequest $request,
        string $jobVacancyId,
        ResumeAnanlicesService $resumeAnalysisService
    ): RedirectResponse {
        $validated = $request->validated();
        $jobVacancy = JobVacancy::findOrFail($jobVacancyId);
        $errorField = $validated['resume_source'] === 'existing' ? 'existing_resume_id' : 'resume';
        $file = $request->file('resume');

        if ($validated['resume_source'] === 'existing') {
            $resume = Resume::where('user_id', Auth::id())->findOrFail($validated['existing_resume_id']);
        } else {
            if (! $file) {
                return back()->withErrors([
                    'resume' => __('Please select a PDF resume to upload.'),
                ])->withInput();
            }

            $resume = null;
        }

        try {
            if ($resume) {
                $resumeText = $resumeAnalysisService->extractText($resume->file_url);
            } elseif ($file) {
                $resumeText = $resumeAnalysisService->extractTextFromFile($file->getPathname());
            } else {
                throw new RuntimeException('The uploaded resume is unavailable.');
            }

            if (trim($resumeText) === '') {
                return back()->withErrors([
                    $errorField => __('No selectable text could be extracted from this PDF. Please use a text-based PDF.'),
                ])->withInput();
            }

            $analysis = $resumeAnalysisService->analyzeText($resumeText, $jobVacancy);
        } catch (ConnectionException $exception) {
            report($exception);

            return back()->withErrors([
                $errorField => __('AI analysis took too long to respond. Please try again.'),
            ])->withInput();
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                $errorField => __('Resume analysis failed. Please try again later.'),
            ])->withInput();
        }

        if ($resume) {
            $resume->update($analysis['resume']);
            $newResume = $resume;
        } else {
            if (! $file) {
                return back()->withErrors([
                    'resume' => __('Please select a PDF resume to upload.'),
                ])->withInput();
            }

            $fileName = 'resume_'.time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('resumes', $fileName, 'cloud');

            if (! is_string($path)) {
                return back()->withErrors([
                    'resume' => __('The resume could not be saved. Please try again.'),
                ])->withInput();
            }

            $newResume = Resume::create([
                'file_name' => $file->getClientOriginalName(),
                'file_url' => $path,
                'contact_details' => json_encode([
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]),
                'user_id' => Auth::user()->id,
                ...$analysis['resume'],
            ]);
        }

        $jobApplication = JobApplication::create([
            ...$analysis['application'],
            'status' => 'pending',
            'user_id' => Auth::user()->id,
            'resume_id' => $newResume->id,
            'job_vacancy_id' => $jobVacancyId,
        ]);

        return to_route('profile.applications.show', $jobApplication)
            ->with('status', 'application-submitted');
    }
}
