<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadResumeRequest;
use App\Jobs\AnalyzeJobApplication;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        string $jobVacancyId
    ): RedirectResponse {
        $validated = $request->validated();
        JobVacancy::findOrFail($jobVacancyId);
        $file = $request->file('resume');
        $resume = null;

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

        $storedResumePath = null;

        if ($resume === null && $file) {
            $fileName = 'resume_'.time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $storedResumePath = $file->storeAs('resumes', $fileName, 'cloud');

            if (! is_string($storedResumePath)) {
                return back()->withErrors([
                    'resume' => __('The resume could not be saved. Please try again.'),
                ])->withInput();
            }
        }

        $jobApplication = null;

        try {
            $jobApplication = DB::transaction(function () use ($file, $jobVacancyId, $resume, $storedResumePath) {
                $user = Auth::user();

                if ($resume === null) {
                    if (! $file || ! is_string($storedResumePath)) {
                        throw new RuntimeException('The uploaded resume is unavailable.');
                    }

                    $resume = new Resume;
                    $resume->forceFill([
                        'file_name' => $file->getClientOriginalName(),
                        'file_url' => $storedResumePath,
                        'contact_details' => json_encode([
                            'name' => $user->name,
                            'email' => $user->email,
                        ]),
                        'user_id' => $user->id,
                        'education' => '',
                        'summary' => '',
                        'skills' => '',
                        'experience' => '',
                        'analysis_status' => 'pending',
                    ])->save();
                }

                $jobApplication = new JobApplication;
                $jobApplication->forceFill([
                    'status' => 'pending',
                    'analysis_status' => 'pending',
                    'user_id' => $user->id,
                    'resume_id' => $resume->id,
                    'job_vacancy_id' => $jobVacancyId,
                ]);
                $jobApplication->save();

                return $jobApplication;
            });

            AnalyzeJobApplication::dispatch($jobApplication->id);
        } catch (Throwable $exception) {
            if ($jobApplication !== null) {
                try {
                    DB::transaction(function () use ($jobApplication, $storedResumePath) {
                        $jobApplication->delete();

                        if (is_string($storedResumePath)) {
                            Resume::whereKey($jobApplication->resume_id)->delete();
                        }
                    });
                } catch (Throwable $cleanupException) {
                    report($cleanupException);
                }
            }

            if (is_string($storedResumePath) && ! Storage::disk('cloud')->delete($storedResumePath)) {
                report(new RuntimeException('The uploaded resume could not be removed after application submission failed.'));
            }

            throw $exception;
        }

        return to_route('profile.applications.show', $jobApplication)
            ->with('status', 'application-submitted');
    }
}
