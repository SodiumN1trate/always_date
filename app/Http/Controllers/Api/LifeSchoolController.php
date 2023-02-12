<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LifeSchoolRequest;
use App\Http\Resources\LifeSchoolResource;
use App\Models\LifeSchool;
use Illuminate\Http\Request;

class LifeSchoolController extends Controller {
    /**
     * @OA\Get(
     *      path="/life_school",
     *      operationId="getLifeSchool",
     *      tags={"Life school"},
     *      summary="Iegūst visus life school, tikai pēc gender",
     *      description="Iegūst visas dzīves skolas rakstus, bet atgriežot dzīves skolas tiek filtrētas pēc lietotāja dzimuma",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function index() {
        if(isset(request()->page)) {
            return LifeSchoolResource::collection(LifeSchool::filter(request()->all())
                ->gender()
                ->orderBy('number')
                ->paginate(20));
        }
        return LifeSchoolResource::collection(LifeSchool::filter(request()->all())
            ->gender()
            ->orderBy('number')
            ->get());
    }
    /**
     * @OA\Post(
     *      path="/life_school",
     *      operationId="postLifeSchool",
     *      tags={"Life school"},
     *      summary="Izveido jaunu ierakstu par life school",
     *      description="Izveido jaunu ierakstu life school",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  ref="#components/schemas/LifeSchoolRequest",
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Nevar noteikt dzimumu.",
     *      )
     *)
     */
    public function store(LifeSchoolRequest $request) {
        if ($request['gender'] <= -1 || $request['gender'] >= 2) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar noteikt dzimumu.',
                ]
            ], 400);
        }
        $lifeSchool = LifeSchool::create($request->validated());
        return new LifeSchoolResource($lifeSchool);
    }

    /**
     * @OA\Get(
     *      path="/life_school/{id}",
     *      operationId="getLifeSchoolById",
     *      tags={"Life school"},
     *      summary="Atgriež dzīves skolas rakstu pēc id",
     *      description="Atgriež dzīves skolas rakstu pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="LifeSchool id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Nedrīkst skatīt cita dzimuma dzīves skolu",
     *      ),
     * )
     */
    public function show(LifeSchool $lifeSchool) {
        if ($lifeSchool['gender'] != auth()->user()->gender) {
            return response()->json([
               'error' => [
                   'data' => 'Nedrīkst skatīt cita dzimuma dzīves skolu.',
               ]
            ], 400);
        } else {
            return new LifeSchoolResource($lifeSchool);
        }
    }

    /**
     * @OA\Put(
     *      path="/life_school/{id}",
     *      operationId="updateLifeSchool",
     *      tags={"Life school"},
     *      summary="Atjaunina datus dzīves skolai",
     *      description="Atjauno datus dzīves skolai pēc ievadītā id iekš uri",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="LifeSchool id",
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
     *                  ref="#components/schemas/LifeSchoolRequest"
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     *)
     */
    public function update(LifeSchoolRequest $request, LifeSchool $lifeSchool) {
        $lifeSchool->update($request->validated());
        return new LifeSchoolResource($lifeSchool);
    }

    /**
     * @OA\Delete(
     *      path="/life_school/{id}",
     *      operationId="deleteLifeSchool",
     *      tags={"Life school"},
     *      summary="Izdzēš ārā dzīves skolas rakstu",
     *      description="Dzēš ārā dzīves skolas rakstu pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="LifeSchool id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function destroy(LifeSchool $lifeSchool) {
        $lifeSchool->delete();
        return new LifeSchoolResource($lifeSchool);
    }

}
