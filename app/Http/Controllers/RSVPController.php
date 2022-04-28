<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRSVPRequest;
use App\Http\Requests\UpdateRSVPRequest;
use App\Models\RSVP;
use Illuminate\Support\Str;

class RSVPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRSVPRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRSVPRequest $request)
    {
        //

//        return (string) Str::uuid();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RSVP  $rSVP
     * @return \Illuminate\Http\Response
     */
    public function show(RSVP $rSVP)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RSVP  $rSVP
     * @return \Illuminate\Http\Response
     */
    public function edit(RSVP $rSVP)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRSVPRequest  $request
     * @param  \App\Models\RSVP  $rSVP
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRSVPRequest $request, RSVP $rSVP)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RSVP  $rSVP
     * @return \Illuminate\Http\Response
     */
    public function destroy(RSVP $rSVP)
    {
        //
    }
}
