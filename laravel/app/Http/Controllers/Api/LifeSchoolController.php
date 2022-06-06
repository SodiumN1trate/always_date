<?php

namespace App\Http\Controllers\Api;

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
        $lifeSchoolByGender = LifeSchool::where('gender', auth()->user()->gender)->get();
        return LifeSchoolResource::collection($lifeSchoolByGender);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LifeSchoolRequest $request)
    {
        error_log($request['gender']);
        if ($request['gender'] <= -1 || $request['gender'] >= 2) {
            return response()->json([
                'message' => [
                    'type' => 'error',
                    'data' => 'Nevar noteikt dzimumu.'
                ]
            ]);
        }
        $lifeSchool = LifeSchool::create($request->validated());
        return new LifeSchoolResource($lifeSchool);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LifeSchool $lifeSchool)
    {
        if ($lifeSchool['gender'] != auth()->user()->gender) {
            return response()->json([
               'message' => [
                   'type' => 'error',
                   'data' => 'Nedrīkst skatīt cita dzimuma dzīves skolu.',

               ]
            ]);
        } else {
            return new LifeSchoolResource($lifeSchool);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LifeSchoolRequest $request, LifeSchool $lifeSchool)
    {
        $lifeSchool->update($request->validated());
        return new LifeSchoolResource($lifeSchool);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LifeSchool $lifeSchool)
    {
        $lifeSchool->delete();
        return new LifeSchoolResource($lifeSchool);
    }
}
