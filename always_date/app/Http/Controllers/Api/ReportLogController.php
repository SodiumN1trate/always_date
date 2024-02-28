<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportLogResource;
use App\Models\ReportLog;
use Illuminate\Http\Request;

class ReportLogController extends Controller {
    /**
     * @OA\Get(
     *      path="/report_log",
     *      operationId="getReportLog",
     *      tags={"Report log"},
     *      summary="Iegūst nosūtītās sūdzības",
     *      description="Iegūst visas nosūtītās sūdzibas",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ReportLogResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function index() {
        if(isset(request()->page)) {
            return ReportLogResource::collection(ReportLog::paginate(20));
        }
        return ReportLogResource::collection(ReportLog::all());
    }

    /**
     * @OA\Post(
     *      path="/report_log",
     *      operationId="postReportLog",
     *      tags={"Report log"},
     *      summary="Izveido sūdzības par lietotāju",
     *      description="Izveido jaunu sūdzību par kādu lietotāju",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"profile_id", "report_type"},
     *                  @OA\Property(format="integer", description="Lietotāja id par kuru tiks nosūtīta sūdzība", property="profile_id"),
     *                  @OA\Property(format="integer", description="Sūdzības veida id", property="report_type"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ReportLogResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Nevar nosūtīt sūdzību par sevi.",
     *      ),
     *     @OA\Response(
     *          response=402,
     *          description="Nevar nosūtīt vairāk par vienu sūdzību.",
     *      )
     *)
     */
    public function store(Request $request) {
        $reportLog = $request->validate([
            'profile_id' => 'required',
            'report_type' => 'required',
        ]);

        $userReports = ReportLog::where('reporter_id', auth()->user()->id)
            ->where('profile_id', $reportLog['profile_id'])->first();

        if (auth()->user()->id == $reportLog['profile_id']) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar nosūtīt sūdzību par sevi.',
                ]
            ], 400);
        } elseif ($userReports) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar nosūtīt vairāk par vienu sūdzību.',
                ]
            ], 400);
        } else {
            $reportLog['reporter_id'] = auth()->user()->id;
            $report = ReportLog::create($reportLog);
            return new ReportLogResource($report);
        }
    }

    /**
     * @OA\Get(
     *      path="/report_log/{id}",
     *      operationId="getReportLogById",
     *      tags={"Report log"},
     *      summary="Iegūst nosūtīto sūdzību",
     *      description="Iegūst konkrēto nosūtīto sūdzību par lietotāju",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="ReportLog id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ReportLogResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated.",
     *      ),
     * )
     */
    public function show(ReportLog $reportLog) {
        return new ReportLogResource($reportLog);
    }

    /**
     * @OA\Delete(
     *      path="/report_log/{id}",
     *      operationId="deleteReportLog",
     *      tags={"Report log"},
     *      summary="Izdzēš sūdzību par lietotāju",
     *      description="Izdzēs sūdzību par lietotāju pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="ReportLog id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ReportLogResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function destroy(ReportLog $reportLog) {
        $reportLog->delete();
        return new ReportLogResource($reportLog);
    }

}
