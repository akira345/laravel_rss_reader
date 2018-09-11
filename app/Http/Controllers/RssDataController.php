<?php

namespace App\Http\Controllers;

use App\Models\RssData;
use Illuminate\Http\Request;

class RssDataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function show(RssData $rssData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function edit(RssData $rssData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RssData $rssData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function destroy(RssData $rssData)
    {
        //
    }
}
