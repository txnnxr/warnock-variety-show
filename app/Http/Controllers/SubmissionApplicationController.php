<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Show;
use App\Models\SubmissionApplication;
use Illuminate\Http\Request;

class SubmissionApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Show $show)
    {
        $submissionApplications = $show->submissionApplications;
        return view('shows.submission-applications.index', compact('show', 'submissionApplications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create(Show $show)
    {
        return view('shows.submission-applications.create', compact('show'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Show $show, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'title' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'description' => 'nullable|string',
        ]);

        $submissionApplication = SubmissionApplication::create([
            'show_id' => $show->id,
            'person_id' => Person::resolve($validated['name'], $validated['email'] ?? null, $validated['phone'] ?? null)->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'description' => $validated['description'] ?? null,
            'approved' => false,
            'title' => $validated['title'],
        ]);

        return redirect()->route('applications.status', $submissionApplication);
    }

    /**
     * Admin view of an application.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Show $show, SubmissionApplication $submissionApplication)
    {
        abort_unless($submissionApplication->show_id === $show->id, 404);

        return view('shows.submission-applications.show', compact('submissionApplication'));
    }

    /**
     * The applicant's own view of their application, reached by its secret key.
     */
    public function status(SubmissionApplication $submissionApplication)
    {
        return view('shows.submission-applications.show', compact('submissionApplication'));
    }

    public function approve(SubmissionApplication $submissionApplication){
        $submissionApplication->approve();

        return redirect("/shows/{$submissionApplication->show_id}/submission-applications/{$submissionApplication->id}/view");
    }

    public function deny(SubmissionApplication $submissionApplication){
        $submissionApplication->deny();

        return redirect("/shows/{$submissionApplication->show_id}/submission-applications/{$submissionApplication->id}/view");
    }
}
