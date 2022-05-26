<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LifeSchoolRequest;
use App\Http\Resources\LifeSchoolResource;
use App\Models\LifeSchool;
use Illuminate\Http\Request;

class LifeSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LifeSchoolResource::collection(LifeSchool::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LifeSchoolRequest $request)
    {
        $schoolLife = LifeSchool::create($request->validated());
        return new LifeSchoolResource($schoolLife);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LifeSchool $schoolLife)
    {
        return new LifeSchoolResource($schoolLife);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LifeSchoolRequest $request, LifeSchool $schoolLife)
    {
        $schoolLife->update($request->validated());
        return new LifeSchoolResource($schoolLife);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LifeSchool $schoolLife)
    {
        $schoolLife->delete();
        return new LifeSchoolResource($schoolLife);
    }
}
