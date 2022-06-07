<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportTypeRequest;
use App\Http\Resources\ReportTypeResource;
use App\Models\ReportType;
use Illuminate\Http\Request;

class ReportTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ReportTypeResource::collection(ReportType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportTypeRequest $request)
    {
        $ReportType = ReportType::create($request->validated());
        return new ReportTypeResource($ReportType);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ReportType $reportType)
    {
        return new ReportTypeResource($reportType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReportTypeRequest $request, ReportType $ReportType)
    {
        $ReportType->update($request->validated());
        return new ReportTypeResource($ReportType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportType $ReportType)
    {
        $ReportType->delete();
        return new ReportTypeResource($ReportType);
    }
}
