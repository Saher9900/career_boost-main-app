<?php

namespace App\Jobs;

use App\Models\Resume;
use App\Services\ResumeAnanlicesService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;
use Throwable;

class AnalyzeResume implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 55;

    public function __construct(public int $resumeId) {}

    public function handle(ResumeAnanlicesService $resumeAnalysisService): void
    {
        $resume = Resume::findOrFail($this->resumeId);
        $resumeText = $resumeAnalysisService->extractText($resume->file_url);

        if (trim($resumeText) === '') {
            throw new RuntimeException('No selectable text could be extracted from the resume PDF.');
        }

        $analysis = $resumeAnalysisService->analyzeText($resumeText);

        $resume->forceFill([
            ...$analysis['resume'],
            'analysis_status' => 'completed',
        ])->save();
    }

    public function failed(Throwable $exception): void
    {
        $resume = Resume::find($this->resumeId);

        if ($resume !== null) {
            $resume->forceFill([
                'analysis_status' => 'failed',
            ])->save();
        }

        report($exception);
    }
}
