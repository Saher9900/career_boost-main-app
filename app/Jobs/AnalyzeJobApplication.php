<?php

namespace App\Jobs;

use App\Models\JobApplication;
use App\Services\ResumeAnanlicesService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;
use Throwable;

class AnalyzeJobApplication implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 55;

    public function __construct(public int $jobApplicationId) {}

    public function handle(ResumeAnanlicesService $resumeAnalysisService): void
    {
        $application = JobApplication::query()
            ->with(['resume', 'jobVacancy.company'])
            ->findOrFail($this->jobApplicationId);
        $resume = $application->resume;
        $jobVacancy = $application->jobVacancy;

        if ($resume === null || $jobVacancy === null) {
            throw new RuntimeException('The application resume or job vacancy is unavailable.');
        }

        $resumeText = $resumeAnalysisService->extractText($resume->file_url);

        if (trim($resumeText) === '') {
            throw new RuntimeException('No selectable text could be extracted from the resume PDF.');
        }

        $analysis = $resumeAnalysisService->analyzeText($resumeText, $jobVacancy);
        $applicationAnalysis = $analysis['application'];

        if ($applicationAnalysis === null) {
            throw new RuntimeException('The resume analysis did not return application feedback.');
        }

        $resume->forceFill([
            ...$analysis['resume'],
            'analysis_status' => 'completed',
        ])->save();
        $application->forceFill([
            ...$applicationAnalysis,
            'analysis_status' => 'completed',
        ])->save();
    }

    public function failed(Throwable $exception): void
    {
        $application = JobApplication::with('resume')->find($this->jobApplicationId);

        if ($application !== null) {
            $application->forceFill([
                'analysis_status' => 'failed',
                'ai_feedback' => __('Resume analysis could not be completed. Please try again later.'),
            ])->save();

            $application->resume?->forceFill([
                'analysis_status' => 'failed',
            ])->save();
        }

        report($exception);
    }
}
