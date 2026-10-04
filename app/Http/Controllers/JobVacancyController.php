<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:contract,remote,full_time,hybrid'],
        ]);

        $search = trim($filters['search'] ?? '');
        $type = $filters['type'] ?? '';

        $vacancies = JobVacancy::query()
            ->with('company')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhereHas('company', fn ($c) => $c->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($type !== '', fn ($query) => $query->where('type', $type))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('job-vacancies', compact('vacancies', 'search', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(JobVacancy $jobVacancy)
    {
        $jobVacancy->load(['company', 'jobCategory']);

        return view('job-vacancies.show', compact('jobVacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobVacancy $jobVacancy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobVacancy $jobVacancy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobVacancy $jobVacancy)
    {
        //
    }
}
