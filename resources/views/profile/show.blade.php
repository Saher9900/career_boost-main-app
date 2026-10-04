<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/image.svg') }}" alt="" class="h-10 w-10 shrink-0">
            <div>
                <p class="text-xs font-semibold text-[#138A9E]">{{ __('Career Booster') }}</p>
                <h1 class="text-xl font-semibold leading-tight text-[#0E6378]">{{ __('Your profile') }}</h1>
            </div>
        </div>
    </x-slot>

    <main class="min-h-screen bg-[#F4F8F8] py-8 sm:py-12">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <section class="border-b border-[#B8D5D9] pb-7 sm:flex sm:items-end sm:justify-between sm:gap-6">
                <div>
                    <p class="text-sm font-semibold text-[#138A9E]">{{ __('Career profile') }}</p>
                    <h2 class="mt-1 break-words text-3xl font-semibold text-[#17343A]">{{ $user->name }}</h2>
                    <p class="mt-2 text-sm text-gray-600">{{ $user->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="mt-5 inline-flex min-h-10 items-center justify-center rounded-md border border-[#B8D5D9] bg-white px-4 py-2 text-sm font-semibold text-[#0E6378] transition hover:bg-[#E6F3F5] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2 sm:mt-0">
                    {{ __('Edit profile details') }}
                </a>
            </section>

            <div class="mt-7 grid gap-7 lg:grid-cols-[minmax(0,1fr)_19rem] lg:items-start">
                <div class="space-y-8">
                    <section aria-labelledby="applications-heading">
                        <div class="flex flex-wrap items-end justify-between gap-3 border-b border-[#DCE9EB] pb-3">
                            <div>
                                <p class="text-xs font-semibold uppercase text-[#138A9E]">{{ __('Your activity') }}</p>
                                <h3 id="applications-heading" class="mt-1 text-xl font-semibold text-[#17343A]">
                                    {{ __('Job applications') }}
                                    <span class="ms-1 text-sm font-medium text-gray-500">{{ $applicationCount }}</span>
                                </h3>
                            </div>
                            <a href="{{ route('profile.applications.index') }}"
                                class="text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                                {{ __('View all applications') }}
                            </a>
                        </div>

                        @forelse ($applications as $application)
                            <article class="border-b border-[#E8F0F1] py-5 last:border-b-0">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="break-words font-semibold text-[#17343A]">
                                            {{ $application->jobVacancy?->title ?? __('Position no longer available') }}
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ $application->jobVacancy?->company?->name ?? __('Company not specified') }}
                                            <span class="mx-1 text-gray-300" aria-hidden="true">|</span>
                                            {{ $application->created_at->format('M j, Y') }}
                                        </p>
                                        <p class="mt-2 text-xs text-gray-500">
                                            {{ __('Resume: :name', ['name' => $application->resume?->file_name ?? __('Unavailable')]) }}
                                        </p>
                                    </div>
                                    <span class="inline-flex rounded-full bg-[#E6F3F5] px-3 py-1 text-xs font-semibold text-[#0E6378]">
                                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                                <a href="{{ route('profile.applications.show', $application) }}"
                                    class="mt-3 inline-flex text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                                    {{ __('Application details') }}
                                </a>
                            </article>
                        @empty
                            <div class="py-8">
                                <p class="text-sm text-gray-600">{{ __('You have not applied to any jobs yet.') }}</p>
                                <a href="{{ route('job-vacancies.index') }}"
                                    class="mt-3 inline-flex text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                                    {{ __('Explore open jobs') }}
                                </a>
                            </div>
                        @endforelse
                    </section>

                    <section aria-labelledby="resumes-heading">
                        <div class="flex flex-wrap items-end justify-between gap-3 border-b border-[#DCE9EB] pb-3">
                            <div>
                                <p class="text-xs font-semibold uppercase text-[#138A9E]">{{ __('Your documents') }}</p>
                                <h3 id="resumes-heading" class="mt-1 text-xl font-semibold text-[#17343A]">
                                    {{ __('Resumes') }}
                                    <span class="ms-1 text-sm font-medium text-gray-500">{{ $resumeCount }}</span>
                                </h3>
                            </div>
                            <a href="{{ route('profile.resumes.index') }}"
                                class="text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                                {{ __('Manage resumes') }}
                            </a>
                        </div>

                        @forelse ($resumes as $resume)
                            <article class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E8F0F1] py-4 last:border-b-0">
                                <div class="min-w-0">
                                    <h4 class="break-all text-sm font-semibold text-[#17343A]">{{ $resume->file_name }}</h4>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ __('Added :date', ['date' => $resume->created_at->format('M j, Y')]) }}
                                    </p>
                                </div>
                                <a href="{{ rtrim((string) config('filesystems.disks.cloud.url'), '/') . '/' . ltrim($resume->file_url, '/') }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                                    {{ __('Open PDF') }}
                                </a>
                            </article>
                        @empty
                            <div class="py-8">
                                <p class="text-sm text-gray-600">{{ __('No resumes have been added yet.') }}</p>
                                <a href="{{ route('profile.resumes.index') }}"
                                    class="mt-3 inline-flex text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                                    {{ __('Add your first resume') }}
                                </a>
                            </div>
                        @endforelse
                    </section>
                </div>

                <aside class="border border-[#DCE9EB] bg-white p-5 sm:p-6" aria-labelledby="details-heading">
                    <div class="flex items-start justify-between gap-3 border-b border-[#E8F0F1] pb-4">
                        <h3 id="details-heading" class="text-base font-semibold text-[#17343A]">{{ __('Personal details') }}</h3>
                        <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                            {{ __('Edit') }}
                        </a>
                    </div>
                    <dl class="divide-y divide-[#E8F0F1]">
                        <div class="py-4">
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Name') }}</dt>
                            <dd class="mt-1 break-words text-sm font-medium text-[#17343A]">{{ $user->name }}</dd>
                        </div>
                        <div class="py-4">
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Email') }}</dt>
                            <dd class="mt-1 break-all text-sm font-medium text-[#17343A]">{{ $user->email }}</dd>
                        </div>
                        <div class="py-4">
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Account type') }}</dt>
                            <dd class="mt-1 text-sm font-medium text-[#17343A]">{{ ucwords(str_replace('_', ' ', $user->role ?? 'job seeker')) }}</dd>
                        </div>
                        <div class="py-4 last:pb-0">
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Member since') }}</dt>
                            <dd class="mt-1 text-sm font-medium text-[#17343A]">{{ $user->created_at->format('M Y') }}</dd>
                        </div>
                    </dl>
                </aside>
            </div>
        </div>
    </main>
</x-app-layout>