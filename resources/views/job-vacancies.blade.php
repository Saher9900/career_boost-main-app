<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/image.svg') }}" alt="" class="h-10 w-10 shrink-0">
            <div>
                <p class="text-xs font-semibold text-[#138A9E]">{{ __('Career Booster') }}</p>
                <h2 class="text-xl font-semibold leading-tight text-[#0E6378]">
                    {{ __('Job vacancies') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <main class="min-h-screen bg-[#F4F8F8] py-8 sm:py-12">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold text-[#138A9E]">{{ __('Your next chapter starts here') }}</p>
                <h1 class="mt-2 text-3xl font-semibold leading-tight text-[#17343A] sm:text-4xl">
                    {{ __('Find work that moves you forward.') }}
                </h1>
                <p class="mt-3 text-base leading-relaxed text-gray-600">
                    {{ __('Explore opportunities from companies ready to meet their next great hire.') }}
                </p>
            </div>

            {{-- Search and type filters are submitted as query parameters. --}}
            <div class="mt-8 rounded-lg border border-[#DCE9EB] bg-white p-3 shadow-sm sm:p-4">
                <form method="GET" action="{{ route('job-vacancies.index') }}" class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_13rem_auto]">
                    <label class="relative block">
                        <span class="sr-only">{{ __('Search vacancies') }}</span>
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-[#138A9E]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.8" />
                            <path d="m16 16 4.5 4.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        <input
                            type="search"
                            placeholder="{{ __('Job title, company, or keyword') }}"
                            class="block min-h-12 w-full rounded-md border-[#D5E2E4] pl-10 text-sm text-[#17343A] placeholder:text-gray-400 focus:border-[#138A9E] focus:ring-[#138A9E]"
                            name="search"
                            value="{{ $search }}"
                        >
                    </label>

                    <label class="block">
                        <span class="sr-only">{{ __('Vacancy type') }}</span>
                        <select name="type" class="block min-h-12 w-full rounded-md border-[#D5E2E4] text-sm text-gray-600 focus:border-[#138A9E] focus:ring-[#138A9E]">
                            <option value="" @selected($type === '')>{{ __('All vacancy types') }}</option>
                            <option value="full_time" @selected($type === 'full_time')>{{ __('Full-time') }}</option>
                            <option value="contract" @selected($type === 'contract')>{{ __('Contract') }}</option>
                            <option value="remote" @selected($type === 'remote')>{{ __('Remote') }}</option>
                            <option value="hybrid" @selected($type === 'hybrid')>{{ __('Hybrid') }}</option>
                        </select>
                    </label>

                    <button
                        type="submit"
                        aria-label="{{ __('Search vacancies') }}"
                        class="inline-flex min-h-12 items-center justify-center gap-2 rounded-md bg-[#138A9E] px-5 text-sm font-semibold text-white transition hover:bg-[#0E6378] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2"
                    >
                        <span>{{ __('Search') }}</span>
                    </button>
                </form>
            </div>

            <section class="mt-10" aria-labelledby="vacancy-list-heading">
                <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
                    <div>
                        <h2 id="vacancy-list-heading" class="text-lg font-semibold text-[#17343A]">
                            {{ __('Latest opportunities') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ number_format($vacancies->total()) }} {{ __('open roles') }}
                        </p>
                    </div>
                </div>

                @forelse ($vacancies as $vacancy)
                    <article class="border-t border-[#DCE9EB] bg-white px-4 py-5 transition hover:bg-[#F9FCFC] sm:px-6">
                        {{-- Keep the vacancy details on the left and the employment type on the right. --}}
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <h3 class="break-words text-lg font-semibold leading-snug text-[#0E6378]">
                                    <a href="{{ route('job-vacancies.show', $vacancy) }}" class="rounded-sm hover:text-[#138A9E] focus:outline-none focus:ring-2 focus:ring-[#138A9E]">
                                        {{ $vacancy->title }}
                                    </a>
                                </h3>

                                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-600">
                                    <span class="font-medium text-[#17343A]">
                                        {{ $vacancy->company?->name ?? __('Company not specified') }}
                                    </span>
                                    @if ($vacancy->location)
                                        <span class="text-gray-300" aria-hidden="true">|</span>
                                        <span>{{ $vacancy->location }}</span>
                                    @endif
                                    @if ($vacancy->salary)
                                        <span class="text-gray-300" aria-hidden="true">|</span>
                                        <span>{{ $vacancy->salary }}</span>
                                    @endif
                                    @if ($vacancy->created_at)
                                        <span class="text-gray-300" aria-hidden="true">|</span>
                                        <time datetime="{{ $vacancy->created_at->toAtomString() }}">
                                            {{ __('Posted :date', ['date' => $vacancy->created_at->format('M j, Y')]) }}
                                        </time>
                                    @endif
                                </div>
                            </div>

                            <span class="inline-flex w-fit shrink-0 items-center rounded-full bg-[#E6F3F5] px-3 py-1.5 text-sm font-semibold text-[#0E6378]">
                                {{ $vacancy->type ?: __('Other') }}
                            </span>
                        </div>
                    </article>
                @empty
                    <div class="border-y border-[#DCE9EB] bg-white px-6 py-14 text-center">
                        <img src="{{ asset('images/image.svg') }}" alt="" class="mx-auto h-12 w-12 opacity-70">
                        <h3 class="mt-4 text-base font-semibold text-[#17343A]">
                            {{ __('No vacancies yet') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('New opportunities will appear here when they are posted.') }}
                        </p>
                    </div>
                @endforelse

                <div class="mt-8 border-t border-[#DCE9EB] pt-6">
                    {{ $vacancies->links() }}
                </div>
            </section>
        </div>
    </main>
</x-app-layout>
