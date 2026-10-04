<?php

use App\Jobs\AnalyzeJobApplication;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Schema::create('job_vacancies', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->foreignId('company_id')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('resumes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id');
        $table->string('file_name');
        $table->string('file_url');
        $table->text('contact_details')->nullable();
        $table->text('education')->nullable();
        $table->text('summary')->nullable();
        $table->text('skills')->nullable();
        $table->text('experience')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('job_applications', function (Blueprint $table) {
        $table->id();
        $table->float('ai_score')->nullable();
        $table->text('ai_feedback')->nullable();
        $table->string('status');
        $table->string('analysis_status')->default('completed');
        $table->foreignId('user_id');
        $table->foreignId('resume_id');
        $table->foreignId('job_vacancy_id');
        $table->timestamps();
        $table->softDeletes();
    });
});

it('queues uploaded resume analysis and responds without calling the AI service', function () {
    Queue::fake();
    Http::fake();
    Storage::fake('cloud');

    $user = User::factory()->create();
    $jobVacancy = JobVacancy::create([
        'title' => 'Laravel Developer',
        'description' => 'Build Laravel applications.',
    ]);

    $response = $this->actingAs($user)->post(route('application-actions.uploadResume', $jobVacancy->id), [
        'resume_source' => 'upload',
        'resume' => UploadedFile::fake()->createWithContent(
            'resume.pdf',
            '%PDF-1.4 '.str_repeat('resume content ', 20)
        ),
    ]);

    $application = JobApplication::firstOrFail();
    $resume = Resume::firstOrFail();

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.applications.show', $application));

    expect($application->analysis_status)->toBe('pending')
        ->and($resume->file_name)->toBe('resume.pdf')
        ->and($resume->education)->toBe('');

    Storage::disk('cloud')->assertExists($resume->file_url);
    Http::assertNothingSent();
    Queue::assertPushed(AnalyzeJobApplication::class, function (AnalyzeJobApplication $job) use ($application) {
        return $job->jobApplicationId === $application->id;
    });
});
