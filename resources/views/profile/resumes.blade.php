<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/image.svg') }}" alt="" class="h-10 w-10 shrink-0">
            <div>
                <p class="text-xs font-semibold text-[#138A9E]">{{ __('Career Booster') }}</p>
                <h1 class="text-xl font-semibold leading-tight text-[#0E6378]">{{ __('Your resumes') }}</h1>
            </div>
        </div>
    </x-slot>

    <main class="min-h-screen bg-[#F4F8F8] py-8 sm:py-12">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('profile.show') }}" class="text-sm font-semibold text-[#0E6378] hover:text-[#138A9E]">
                {{ __('Back to profile') }}
            </a>

            <div class="mt-6 grid gap-8 lg:grid-cols-[minmax(0,1fr)_19rem] lg:items-start">
                <section aria-labelledby="resume-list-heading">
                    <div class="flex flex-wrap items-end justify-between gap-3 border-b border-[#B8D5D9] pb-4">
                        <div>
                            <p class="text-xs font-semibold uppercase text-[#138A9E]">{{ __('Career documents') }}</p>
                            <h2 id="resume-list-heading" class="mt-1 text-2xl font-semibold text-[#17343A]">{{ __('Saved resumes') }}</h2>
                        </div>
                        <span class="text-sm text-gray-500">{{ trans_choice(':count resume|:count resumes', $resumes->count(), ['count' => $resumes->count()]) }}</span>
                    </div>

                    @forelse ($resumes as $resume)
                        <article class="border-b border-[#DCE9EB] py-6 last:border-b-0">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="break-all text-lg font-semibold text-[#17343A]">{{ $resume->file_name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('Added :date', ['date' => $resume->created_at->format('M j, Y')]) }}</p>
                                </div>
                                <a href="{{ rtrim((string) config('filesystems.disks.cloud.url'), '/') . '/' . ltrim($resume->file_url, '/') }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="inline-flex min-h-9 items-center justify-center rounded-md border border-[#B8D5D9] bg-white px-3 py-1.5 text-sm font-semibold text-[#0E6378] hover:bg-[#E6F3F5]">
                                    {{ __('Open PDF') }}
                                </a>
                            </div>
                            <dl class="mt-5 grid gap-x-6 gap-y-4 sm:grid-cols-2">
                                @foreach ([__('Summary') => $resume->summary, __('Skills') => $resume->skills, __('Education') => $resume->education, __('Experience') => $resume->experience] as $label => $value)
                                    @if ($value)
                                        <div>
                                            <dt class="text-xs font-semibold uppercase text-gray-500">{{ $label }}</dt>
                                            <dd class="mt-1 whitespace-pre-line text-sm leading-6 text-[#344F54]">{{ $value }}</dd>
                                        </div>
                                    @endif
                                @endforeach
                            </dl>
                        </article>
                    @empty
                        <div class="border-b border-[#DCE9EB] py-8">
                            <p class="text-sm text-gray-600">{{ __('Your saved resumes will appear here.') }}</p>
                        </div>
                    @endforelse
                </section>

                <aside class="border border-[#DCE9EB] bg-white p-5 sm:p-6" aria-labelledby="upload-resume-heading">
                    <p class="text-xs font-semibold uppercase text-[#138A9E]">{{ __('Add a document') }}</p>
                    <h2 id="upload-resume-heading" class="mt-1 text-lg font-semibold text-[#17343A]">{{ __('Upload a resume') }}</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">{{ __('Add a PDF to your profile so you can use it when applying for jobs.') }}</p>

                    @if (session('status') === 'resume-uploaded')
                        <p class="mt-4 border-s-2 border-emerald-600 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">
                            {{ __('Resume added to your profile.') }}
                        </p>
                    @endif

                    <form action="{{ route('profile.resumes.store') }}" method="POST" enctype="multipart/form-data" class="mt-5">
                        @csrf
                        <label for="resume" class="block text-sm font-medium text-[#17343A]">{{ __('Resume PDF') }}</label>
                        <input id="resume" name="resume" type="file" accept=".pdf,application/pdf" required
                            class="mt-2 block w-full text-sm text-gray-600 file:me-3 file:rounded-md file:border-0 file:bg-[#E6F3F5] file:px-3 file:py-2 file:text-sm file:font-semibold file:text-[#0E6378] hover:file:bg-[#D7ECEF]">
                        @error('resume')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">{{ __('PDF only, maximum 5 MB.') }}</p>
                        <button type="submit" class="mt-5 inline-flex min-h-10 w-full items-center justify-center rounded-md bg-[#138A9E] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#0E6378] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2">
                            {{ __('Add resume') }}
                        </button>
                    </form>
                </aside>
            </div>
        </div>
    </main>
</x-app-layout>