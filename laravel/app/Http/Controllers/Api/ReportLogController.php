<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportLogResource;
use App\Models\ReportLog;
use Illuminate\Http\Request;

class ReportLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ReportLogResource::collection(ReportLog::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reportLog = $request->validate([
            'profile_id' => '',
            'report_type' => '',
        ]);

        $userReports = ReportLog::where('reporter_id', auth()->user()->id)
            ->where('profile_id', $reportLog['profile_id'])->first();

        if (auth()->user()->id == $reportLog['profile_id']) {
            return response()->json([
                'message' => [
                    'type' => 'error',
                    'data' => 'Nevar nosūtīt sūdzību par sevi.',
                ]
            ]);
        } elseif ($userReports) {
            return response()->json([
                'message' => [
                    'type' => 'error',
                    'data' => 'Nevar nosūtīt vairāk par vienu sūdzību.',
                ]
            ]);
        } else {
            $reportLog['reporter_id'] = auth()->user()->id;
            $report = ReportLog::create($reportLog);
            return new ReportLogResource($report);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ReportLog $reportLog)
    {
        return new ReportLogResource($reportLog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportLog $reportLog)
    {
        $reportLog->delete();
        return new ReportLogResource($reportLog);
    }
}
