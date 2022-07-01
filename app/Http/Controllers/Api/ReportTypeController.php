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
     * @OA\Get(
     *      path="/report_type",
     *      operationId="getReportType",
     *      tags={"Report type"},
     *      summary="Iegūst sūdzība ierakstus",
     *      description="Iegūst visus sūdzība veidus",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ReportTypeResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function index()
    {
        return ReportTypeResource::collection(ReportType::all());
    }

    /**
     * @OA\Post(
     *      path="/report_type",
     *      operationId="postReportType",
     *      tags={"Report type"},
     *      summary="Izvedio jaunu sūdzības ierakstu",
     *      description="Izveido jaunu sūdzības ierakstu",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  ref="#components/schemas/ReportTypeRequest",
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ReportTypeResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated.",
     *      )
     *)
     */
    public function store(ReportTypeRequest $request)
    {
        $ReportType = ReportType::create($request->validated());
        return new ReportTypeResource($ReportType);
    }

    /**
     * @OA\Get(
     *      path="/report_type/{id}",
     *      operationId="getReportTypeById",
     *      tags={"Report type"},
     *      summary="Iegūst sūdzību ierakstu",
     *      description="Iegūst sūdzību pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="ReportType id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ReportTypeResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated.",
     *      ),
     * )
     */
    public function show(ReportType $reportType)
    {
        return new ReportTypeResource($reportType);
    }

    /**
     * @OA\Put(
     *      path="/report_type/{id}",
     *      operationId="updatReportType",
     *      tags={"Report type"},
     *      summary="Atjauno kādu sūdzības ierakstu",
     *      description="Atjauno kādu sūdzības ierakstu pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="ReportType id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  ref="#components/schemas/ReportTypeRequest"
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ReportTypeResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     *)
     */
    public function update(ReportTypeRequest $request, ReportType $ReportType)
    {
        $ReportType->update($request->validated());
        return new ReportTypeResource($ReportType);
    }

    /**
     * @OA\Delete(
     *      path="/report_type/{id}",
     *      operationId="deleteReportType",
     *      tags={"Report type"},
     *      summary="Izdzēš sūdzības ierakstu",
     *      description="Izdzēš sūdzības ierakstu pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="ReportType id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ReportTypeResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function destroy(ReportType $ReportType)
    {
        $ReportType->delete();
        return new ReportTypeResource($ReportType);
    }
}
