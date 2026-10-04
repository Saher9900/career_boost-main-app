<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/image.svg') }}" alt="" class="h-10 w-10 shrink-0">
            <div>
                <p class="text-xs font-semibold text-[#138A9E]">{{ __('Career Booster') }}</p>
                <h1 class="text-xl font-semibold leading-tight text-[#0E6378]">
                    {{ __('Job details') }}
                </h1>
            </div>
        </div>
    </x-slot>

    <main class="min-h-screen bg-[#F4F8F8] py-8 sm:py-12">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('job-vacancies.index') }}" class="inline-flex items-center gap-2 rounded-sm text-sm font-semibold text-[#0E6378] hover:text-[#138A9E] focus:outline-none focus:ring-2 focus:ring-[#138A9E]">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M12.5 4.5 7 10l5.5 5.5M7.5 10h9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ __('Back to jobs') }}
            </a>

            <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_19rem] lg:items-start">
                <article class="min-w-0 border-y border-[#DCE9EB] bg-white px-5 py-7 sm:px-8 sm:py-9">
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                        @if ($jobVacancy->jobCategory)
                            <span class="font-semibold text-[#138A9E]">{{ $jobVacancy->jobCategory->title }}</span>
                        @endif
                        @if ($jobVacancy->created_at)
                            <span class="text-gray-300" aria-hidden="true">|</span>
                            <time class="text-gray-500" datetime="{{ $jobVacancy->created_at->toAtomString() }}">
                                {{ __('Posted :date', ['date' => $jobVacancy->created_at->format('M j, Y')]) }}
                            </time>
                        @endif
                    </div>

                    <h2 class="mt-3 break-words text-3xl font-semibold leading-tight text-[#17343A] sm:text-4xl">
                        {{ $jobVacancy->title }}
                    </h2>
                    <p class="mt-3 text-lg font-medium text-[#0E6378]">
                        {{ $jobVacancy->company?->name ?? __('Company not specified') }}
                    </p>

                    <div class="mt-8 border-t border-[#E8F0F1] pt-7">
                        <h3 class="text-lg font-semibold text-[#17343A]">{{ __('About this role') }}</h3>
                        @if ($jobVacancy->description)
                            <p class="mt-3 whitespace-pre-line text-base leading-7 text-gray-600">{{ $jobVacancy->description }}</p>
                        @else
                            <p class="mt-3 text-sm text-gray-500">{{ __('No description has been provided for this role.') }}</p>
                        @endif
                    </div>

                    @if ($jobVacancy->company?->industry || $jobVacancy->company?->address)
                        <div class="mt-8 border-t border-[#E8F0F1] pt-7">
                            <h3 class="text-lg font-semibold text-[#17343A]">{{ __('About the company') }}</h3>
                            @if ($jobVacancy->company->industry)
                                <p class="mt-3 text-sm text-gray-600">{{ $jobVacancy->company->industry }}</p>
                            @endif
                            @if ($jobVacancy->company->address)
                                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600">{{ $jobVacancy->company->address }}</p>
                            @endif
                        </div>
                    @endif
                </article>

                <aside class="border border-[#DCE9EB] bg-white p-5 sm:p-6" aria-labelledby="role-summary-heading">
                    <h2 id="role-summary-heading" class="text-base font-semibold text-[#17343A]">{{ __('Role summary') }}</h2>
                    <dl class="mt-5 divide-y divide-[#E8F0F1]">
                        <div class="py-4 first:pt-0">
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Employment type') }}</dt>
                            <dd class="mt-1 text-sm font-semibold text-[#0E6378]">
                                {{ $jobVacancy->type ? ucwords(str_replace('_', ' ', $jobVacancy->type)) : __('Not specified') }}
                            </dd>
                        </div>
                        <div class="py-4">
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Location') }}</dt>
                            <dd class="mt-1 whitespace-pre-line text-sm font-medium text-[#17343A]">
                                {{ $jobVacancy->location ?: __('Not specified') }}
                            </dd>
                        </div>
                        <div class="py-4 last:pb-0">
                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ __('Salary') }}</dt>
                            <dd class="mt-1 text-sm font-medium text-[#17343A]">
                                {{ $jobVacancy->salary ?: __('Not specified') }}
                            </dd>
                        </div>
                    </dl>

                    <a href="{{ route('application-actions.showForm', $jobVacancy->id) }}" class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-[#138A9E] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#0E6378] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2">
                        {{ __('Apply now') }}
                    </a>
                    <a href="{{ route('job-vacancies.index') }}" class="mt-3 inline-flex min-h-11 w-full items-center justify-center rounded-md border border-[#B8D5D9] bg-white px-4 py-2 text-sm font-semibold text-[#0E6378] transition hover:bg-[#E6F3F5] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2">
                        {{ __('Browse all jobs') }}
                    </a>
                </aside>
            </div>
        </div>
    </main>
</x-app-layout>