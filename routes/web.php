<?php

use App\Http\Controllers\ApplicationActions;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use App\Models\Resume;
use App\Services\ResumeAnanlicesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guests see the welcome page; authenticated users go to the vacancies list.
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('job-vacancies.index');
    }

    return view('welcome');
});

Route::get('test', function (ResumeAnanlicesService $resumeAnalysisService) {
    $resume = Resume::where('user_id', Auth::id())->latest()->firstOrFail();

    return $resumeAnalysisService->extractText($resume->file_url);
})->middleware('auth');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('make-application/{jobVacancy}', [ApplicationActions::class, 'showForm'])
        ->name('application-actions.showForm');
    Route::post('upload-resume/{jobVacancyId}', [ApplicationActions::class, 'uploadResume'])
        ->name('application-actions.uploadResume');
});

// Dashboard: the authenticated home page. The "auth" middleware redirects guests
// to the login page, and "verified" requires a confirmed email address.
Route::resource('job-vacancies', JobVacancyController::class)
    ->middleware(['auth', 'verified']);

// Profile management, restricted to logged-in users. Each route is named so
// views can generate URLs with route('profile.edit') instead of hardcoding paths.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/resumes', [ProfileController::class, 'resumes'])->name('profile.resumes.index');
    Route::post('/profile/resumes', [ProfileController::class, 'storeResume'])->name('profile.resumes.store');
    Route::get('/profile/applications', [ProfileController::class, 'applications'])->name('profile.applications.index');
    Route::get('/profile/applications/{jobApplication}', [ProfileController::class, 'application'])->name('profile.applications.show');

    // Persist profile changes (PATCH = partial update of an existing resource).
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Delete the current user's account.
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Loads the framework auth routes (login, register, password reset, email
// verification, logout) kept in a separate file to keep this one readable.

require __DIR__.'/auth.php';
