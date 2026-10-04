<x-app-layout>
    {{-- Header slot with the brand logo and title matching job-vacancies and show pages --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('job-vacancies.index') }}" class="shrink-0 transition hover:opacity-90">
                <img src="{{ asset('images/image.svg') }}" alt="{{ __('Career Booster logo') }}"
                    class="h-10 w-10 shrink-0">
            </a>
            <div>
                <p class="text-xs font-semibold text-[#138A9E]">{{ __('Career Booster') }}</p>
                <h1 class="text-xl font-semibold leading-tight text-[#0E6378]">
                    {{ __('Apply for vacancy') }}
                </h1>
            </div>
        </div>
    </x-slot>

    <main class="min-h-screen bg-[#F4F8F8] py-8 sm:py-12">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            {{-- Navigation back link --}}
            <div class="mb-6">
                <a href="{{ route('job-vacancies.show', $jobVacancy) }}"
                    class="inline-flex items-center gap-2 rounded-sm text-sm font-semibold text-[#0E6378] hover:text-[#138A9E] focus:outline-none focus:ring-2 focus:ring-[#138A9E]">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M12.5 4.5 7 10l5.5 5.5M7.5 10h9" stroke="currentColor" stroke-width="1.7"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ __('Back to job details') }}
                </a>
            </div>

            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_21rem] lg:items-start">
                {{-- Left column: The Application Form --}}
                <div class="border border-[#DCE9EB] bg-white p-6 shadow-sm sm:p-8">
                    <div>
                        <span
                            class="text-xs font-semibold uppercase tracking-wider text-[#138A9E]">{{ __('Application Form') }}</span>
                        <h2 class="mt-1 text-2xl font-semibold text-[#17343A] sm:text-3xl">
                            {{ __('Submit your application') }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ __('You are applying for') }} <span
                                class="font-semibold text-[#0E6378]">{{ $jobVacancy->title ?? $jobVacancy->name }}</span>
                            @if ($jobVacancy->company?->name)
                                {{ __('at') }} <span
                                    class="font-semibold text-[#17343A]">{{ $jobVacancy->company->name }}</span>
                            @endif.
                        </p>
                    </div>

                    <form action="{{ route('application-actions.uploadResume', $jobVacancy->id) }}" method="POST"
                        enctype="multipart/form-data" class="mt-8 space-y-6" x-data="{ resumeChoice: @js(old('resume_source', $existingResumes->isNotEmpty() ? 'existing' : 'upload')), fileName: '' }">
                        @csrf

                        @if ($errors->any())
                            <div class="rounded-md border border-red-200 bg-red-50 p-4">
                                <div class="flex">
                                    <div class="shrink-0">
                                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor"
                                            aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ms-3">
                                        <h3 class="text-sm font-semibold text-red-800">
                                            {{ __('There were errors with your submission:') }}
                                        </h3>
                                        <ul class="mt-2 list-inside list-disc text-sm text-red-700">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="border-t border-[#E8F0F1] pt-6">
                            <h3 class="text-lg font-semibold text-[#17343A]">
                                {{ __('Choose your resume') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ __('Select an existing resume on file or upload a newly updated one.') }}
                            </p>

                            @if ($existingResumes->isNotEmpty())
                                <div class="mt-4">
                                    <label
                                        class="relative flex cursor-pointer items-start gap-4 rounded-lg border p-4 transition"
                                        :class="resumeChoice === 'existing' ?
                                            'border-[#138A9E] bg-[#F7FBFC] ring-1 ring-[#138A9E]' :
                                            'border-[#DCE9EB] bg-white hover:bg-gray-50'">
                                        <input type="radio" name="resume_source" value="existing"
                                            x-model="resumeChoice"
                                            class="mt-1 h-4 w-4 border-[#D5E2E4] text-[#138A9E] focus:ring-[#138A9E]">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <span class="text-sm font-semibold text-[#17343A]">
                                                    {{ __('Select an existing resume') }}
                                                </span>
                                                <span
                                                    class="inline-flex items-center rounded-full bg-[#E6F3F5] px-2.5 py-0.5 text-xs font-medium text-[#0E6378]">
                                                    {{ trans_choice(':count saved resume|:count saved resumes', $existingResumes->count(), ['count' => $existingResumes->count()]) }}
                                                </span>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ __('Choose which saved resume to attach to this application.') }}
                                            </p>
                                        </div>
                                    </label>

                                    <div class="mt-3">
                                        <label for="existing-resume"
                                            class="mb-1.5 block text-sm font-medium text-[#17343A]">
                                            {{ __('Saved resumes') }}
                                        </label>
                                        <select id="existing-resume" name="existing_resume_id"
                                            class="block w-full rounded-md border-[#B8D5D9] text-sm text-[#17343A] shadow-sm focus:border-[#138A9E] focus:ring-[#138A9E]">
                                            @foreach ($existingResumes as $existingResume)
                                                <option value="{{ $existingResume->id }}"
                                                    @selected((string) old('existing_resume_id', $existingResumes->first()->id) === (string) $existingResume->id)>
                                                    {{ $existingResume->file_name }}
                                                    ({{ $existingResume->created_at->format('M j, Y') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('existing_resume_id')
                                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="relative my-6">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-[#E8F0F1]"></div>
                                    </div>
                                    <div class="relative flex justify-center text-xs uppercase">
                                        <span
                                            class="bg-white px-3 font-medium tracking-wider text-gray-500">{{ __('Or upload a new resume') }}</span>
                                    </div>
                                </div>
                            @else
                                <label
                                    class="relative mt-4 flex cursor-not-allowed items-start gap-4 rounded-lg border border-[#DCE9EB] bg-gray-50 p-4 opacity-70">
                                    <input type="radio" name="resume_source" value="existing" disabled
                                        class="mt-1 h-4 w-4 border-[#D5E2E4] text-[#138A9E]">
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-sm font-semibold text-[#17343A]">
                                            {{ __('Choose an existing resume') }}
                                        </span>
                                        <span class="mt-1 block text-xs text-gray-500">
                                            {{ __('No saved resumes are available. Upload a resume instead.') }}
                                        </span>
                                    </span>
                                </label>
                            @endif

                            <div>
                                <label
                                    class="relative mb-3 flex cursor-pointer items-start gap-4 rounded-lg border p-4 transition"
                                    :class="resumeChoice === 'upload' ?
                                        'border-[#138A9E] bg-[#F7FBFC] ring-1 ring-[#138A9E]' :
                                        'border-[#DCE9EB] bg-white hover:bg-gray-50'">
                                    <input type="radio" name="resume_source" value="upload" x-model="resumeChoice"
                                        class="mt-1 h-4 w-4 border-[#D5E2E4] text-[#138A9E] focus:ring-[#138A9E]">
                                    <div class="min-w-0 flex-1">
                                        <span class="text-sm font-semibold text-[#17343A]">
                                            {{ __('Upload a new resume file') }}
                                        </span>
                                        <p class="mt-0.5 text-xs text-gray-500">
                                            {{ __('Attach a tailored resume specifically for this position.') }}
                                        </p>
                                    </div>
                                </label>

                                <div class="mt-2" :class="resumeChoice === 'upload' ? 'opacity-100' : 'opacity-80'">
                                    <label for="resume-file"
                                        class="group relative flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed px-6 py-8 text-center transition {{ $errors->has('resume') ? 'border-red-400 bg-red-50/30' : 'border-[#DCE9EB] bg-[#F9FCFC] hover:border-[#138A9E] hover:bg-[#F2F8F9]' }}">
                                        <div
                                            class="rounded-full {{ $errors->has('resume') ? 'bg-red-100 text-red-600' : 'bg-[#E6F3F5] text-[#138A9E]' }} p-3 transition group-hover:scale-110 group-hover:bg-[#138A9E] group-hover:text-white">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                            </svg>
                                        </div>

                                        <div class="mt-3 text-sm text-gray-600">
                                            <span
                                                class="font-semibold {{ $errors->has('resume') ? 'text-red-700' : 'text-[#0E6378]' }} group-hover:text-[#138A9E]">{{ __('Click to upload') }}</span>
                                            <span>{{ __(' or drag and drop') }}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ __('PDF only (Max 5MB)') }}
                                        </p>

                                        {{-- File preview indicator when selected --}}
                                        <template x-if="fileName">
                                            <div
                                                class="mt-3 inline-flex items-center gap-2 rounded-md border border-[#B8D5D9] bg-white px-3 py-1.5 text-xs font-medium text-[#0E6378]">
                                                <svg class="h-4 w-4 text-[#138A9E]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span x-text="fileName"></span>
                                            </div>
                                        </template>

                                        <input type="file" name="resume" id="resume-file"
                                            accept=".pdf,application/pdf" class="sr-only"
                                            @change="if ($event.target.files.length) { fileName = $event.target.files[0].name; resumeChoice = 'upload'; }">
                                    </label>

                                    @error('resume')
                                        <p class="mt-2 flex items-center gap-1.5 text-sm font-medium text-red-600">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div
                            class="flex flex-col-reverse gap-3 border-t border-[#E8F0F1] pt-6 sm:flex-row sm:items-center sm:justify-end">
                            <a href="{{ route('job-vacancies.show', $jobVacancy) }}"
                                class="inline-flex min-h-11 items-center justify-center rounded-md border border-[#B8D5D9] bg-white px-5 py-2 text-sm font-semibold text-[#0E6378] transition hover:bg-[#E6F3F5] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex min-h-11 items-center justify-center gap-2 rounded-md bg-[#138A9E] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0E6378] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                <span>{{ __('Apply now') }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Right column: Vacancy Details Summary Card --}}
                <aside class="border border-[#DCE9EB] bg-white p-5 shadow-sm sm:p-6"
                    aria-labelledby="vacancy-summary-heading">
                    <div class="flex items-center justify-between gap-2 border-b border-[#E8F0F1] pb-4">
                        <h2 id="vacancy-summary-heading" class="text-base font-semibold text-[#17343A]">
                            {{ __('Job summary') }}
                        </h2>
                        {{-- Guideline: highlight the type of the vacancy --}}
                        <span
                            class="inline-flex w-fit shrink-0 items-center rounded-full bg-[#E6F3F5] px-3 py-1 text-xs font-semibold text-[#0E6378]">
                            {{ $jobVacancy->type ? ucwords(str_replace('_', ' ', $jobVacancy->type)) : __('Not specified') }}
                        </span>
                    </div>

                    <div class="mt-4">
                        <h3 class="break-words text-lg font-semibold leading-snug text-[#0E6378]">
                            {{ $jobVacancy->title ?? $jobVacancy->name }}
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#17343A]">
                            {{ $jobVacancy->company?->name ?? __('Company not specified') }}
                        </p>
                    </div>

                    <dl class="mt-5 divide-y divide-[#E8F0F1]">
                        <div class="py-3 first:pt-0">
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Employment type') }}</dt>
                            <dd class="mt-1 text-sm font-semibold text-[#0E6378]">
                                {{ $jobVacancy->type ? ucwords(str_replace('_', ' ', $jobVacancy->type)) : __('Not specified') }}
                            </dd>
                        </div>

                        @if ($jobVacancy->location || $jobVacancy->company?->address)
                            <div class="py-3">
                                <dt class="text-xs font-semibold uppercase text-gray-500">
                                    {{ __('Location / Address') }}</dt>
                                <dd class="mt-1 whitespace-pre-line text-sm text-[#17343A]">
                                    {{ $jobVacancy->location ?: $jobVacancy->company?->address }}
                                </dd>
                            </div>
                        @endif

                        <div class="py-3">
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Salary') }}</dt>
                            <dd class="mt-1 text-sm font-medium text-[#17343A]">
                                {{ $jobVacancy->salary ?: __('Not specified') }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-6 border-t border-[#E8F0F1] pt-5">
                        <a href="{{ route('job-vacancies.show', $jobVacancy) }}"
                            class="inline-flex min-h-10 w-full items-center justify-center rounded-md border border-[#B8D5D9] bg-white px-4 py-2 text-xs font-semibold text-[#0E6378] transition hover:bg-[#E6F3F5] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2">
                            {{ __('View full role details') }}
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </main>
</x-app-layout>
