<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubmissionApplication;
use App\Models\Show;

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
        return view('shows.submission-applications.index', compact('submissionApplications'));
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Show $show, Request $request)
    {
        $submissionApplication = SubmissionApplication::create([
            'show_id' => $show->id,
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'description' => $request->input('description'),
            'approved' => 0,
            'title' => $request->input('title'),
        ]);

        return redirect("/shows/{$submissionApplication->show_id}/submission-applications/{$submissionApplication->id}/view");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Show $show, SubmissionApplication $submissionApplication)
    {
        return view('shows.submission-applications.show', compact('submissionApplication'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Show $show, SubmissionApplication $submissionApplication)
    {
        //return view('shows.submission-applications.edit', compact('submissionApplication'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function approve(SubmissionApplication $submissionApplication){
        $submissionApplication->update([
            'approved' => 1
        ]);

        return redirect("/shows/{$submissionApplication->show_id}/submission-applications/{$submissionApplication->id}/view");
    }

    public function deny(SubmissionApplication $submissionApplication){
        $submissionApplication->update([
            'approved' => 0
        ]);

        return redirect("/shows/{$submissionApplication->show_id}/submission-applications/{$submissionApplication->id}/view");
    }
}
