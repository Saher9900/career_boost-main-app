<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/image.svg') }}" alt="" class="h-10 w-10 shrink-0">
            <div>
                <p class="text-xs font-semibold text-[#138A9E]">{{ __('Career Booster') }}</p>
                <h1 class="text-xl font-semibold leading-tight text-[#0E6378]">{{ __('Your applications') }}</h1>
            </div>
        </div>
    </x-slot>

    <main class="min-h-screen bg-[#F4F8F8] py-8 sm:py-12">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('profile.show') }}" class="text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                {{ __('Back to profile') }}
            </a>

            <section class="mt-6" aria-labelledby="applications-heading">
                <div class="border-b border-[#B8D5D9] pb-4">
                    <p class="text-xs font-semibold uppercase text-[#138A9E]">{{ __('Application history') }}</p>
                    <h2 id="applications-heading" class="mt-1 text-2xl font-semibold text-[#17343A]">{{ __('All applications') }}</h2>
                </div>

                @forelse ($applications as $application)
                    <article class="border-b border-[#DCE9EB] py-5 last:border-b-0">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="break-words text-lg font-semibold text-[#17343A]">
                                    {{ $application->jobVacancy?->title ?? __('Position no longer available') }}
                                </h3>
                                <p class="mt-1 text-sm text-[#0E6378]">
                                    {{ $application->jobVacancy?->company?->name ?? __('Company not specified') }}
                                </p>
                            </div>
                            <span class="inline-flex rounded-full bg-[#E6F3F5] px-3 py-1 text-xs font-semibold text-[#0E6378]">
                                {{ ucwords(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>
                        <dl class="mt-4 grid gap-x-6 gap-y-3 sm:grid-cols-3">
                            <div>
                                <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Applied') }}</dt>
                                <dd class="mt-1 text-sm text-[#344F54]">{{ $application->created_at->format('M j, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Resume') }}</dt>
                                <dd class="mt-1 break-words text-sm text-[#344F54]">{{ $application->resume?->file_name ?? __('Unavailable') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Match score') }}</dt>
                                <dd class="mt-1 text-sm text-[#344F54]">{{ $application->ai_score !== null ? $application->ai_score : __('Not available') }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('profile.applications.show', $application) }}"
                            class="mt-4 inline-flex text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                            {{ __('View application details') }}
                        </a>
                    </article>
                @empty
                    <div class="border-b border-[#DCE9EB] py-8">
                        <p class="text-sm text-gray-600">{{ __('Your job applications will appear here.') }}</p>
                        <a href="{{ route('job-vacancies.index') }}" class="mt-3 inline-flex text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                            {{ __('Browse jobs') }}
                        </a>
                    </div>
                @endforelse
            </section>
        </div>
    </main>
</x-app-layout>