<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/image.svg') }}" alt="" class="h-10 w-10 shrink-0">
            <div>
                <p class="text-xs font-semibold text-[#138A9E]">{{ __('Career Booster') }}</p>
                <h1 class="text-xl font-semibold leading-tight text-[#0E6378]">{{ __('Application details') }}</h1>
            </div>
        </div>
    </x-slot>

    <main class="min-h-screen bg-[#F4F8F8] py-8 sm:py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('profile.applications.index') }}" class="text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                {{ __('Back to applications') }}
            </a>

            @if (session('status') === 'application-submitted')
                <div role="status" class="mt-5 border-s-4 border-emerald-600 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ __('Your application was submitted successfully.') }}
                </div>
            @endif

            <article class="mt-6 border-y border-[#DCE9EB] bg-white px-5 py-7 sm:px-8 sm:py-9">
                <div class="flex flex-wrap items-start justify-between gap-4 border-b border-[#E8F0F1] pb-6">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase text-[#138A9E]">{{ __('Submitted :date', ['date' => $application->created_at->format('M j, Y')]) }}</p>
                        <h2 class="mt-2 break-words text-2xl font-semibold text-[#17343A]">
                            {{ $application->jobVacancy?->title ?? __('Position no longer available') }}
                        </h2>
                        <p class="mt-1 text-base font-medium text-[#0E6378]">
                            {{ $application->jobVacancy?->company?->name ?? __('Company not specified') }}
                        </p>
                    </div>
                    <span class="inline-flex rounded-full bg-[#E6F3F5] px-3 py-1.5 text-sm font-semibold text-[#0E6378]">
                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>

                <section class="mt-7" aria-labelledby="application-info-heading">
                    <h3 id="application-info-heading" class="text-lg font-semibold text-[#17343A]">{{ __('Application information') }}</h3>
                    <dl class="mt-4 grid gap-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Employment type') }}</dt>
                            <dd class="mt-1 text-sm text-[#344F54]">
                                {{ $application->jobVacancy?->type ? ucwords(str_replace('_', ' ', $application->jobVacancy->type)) : __('Not available') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Location') }}</dt>
                            <dd class="mt-1 text-sm text-[#344F54]">{{ $application->jobVacancy?->location ?: __('Not available') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Match score') }}</dt>
                            <dd class="mt-1 text-sm text-[#344F54]">{{ $application->ai_score !== null ? $application->ai_score : __('Not available') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Resume submitted') }}</dt>
                            <dd class="mt-1 break-words text-sm text-[#344F54]">{{ $application->resume?->file_name ?? __('Resume unavailable') }}</dd>
                        </div>
                    </dl>
                </section>

                @if ($application->ai_feedback)
                    <section class="mt-7 border-t border-[#E8F0F1] pt-6" aria-labelledby="feedback-heading">
                        <h3 id="feedback-heading" class="text-lg font-semibold text-[#17343A]">{{ __('Feedback') }}</h3>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600">{{ $application->ai_feedback }}</p>
                    </section>
                @endif

                @if ($application->jobVacancy?->description)
                    <section class="mt-7 border-t border-[#E8F0F1] pt-6" aria-labelledby="role-heading">
                        <h3 id="role-heading" class="text-lg font-semibold text-[#17343A]">{{ __('Role description') }}</h3>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600">{{ $application->jobVacancy->description }}</p>
                    </section>
                @endif
            </article>
        </div>
    </main>
</x-app-layout>