<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Rules\IsAdult;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Http\Request;
use App\Models\RatingLog;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

class UserController extends Controller {
    /**
     * @OA\Get(
     *      path="/me",
     *      operationId="getUser",
     *      tags={"User"},
     *      summary="Iegūst lietotāja datus",
     *      description="Iegūst autorizētā",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function user() {
        return new UserResource(auth()->user());
    }

    /**
     * @OA\Get(
     *      path="/users{filter}",
     *      operationId="getUsersByFilter",
     *      tags={"User"},
     *      summary="Iegūst lietotājus",
     *      description="Iegūst lietotājus pēc filtrācijas. Filtrācijas piemēri:<br/>
     *          Kārtošana - ?sort[]=name&sort[]=desc/asc;<br/>
     *          Iegūt pēc filtrācijas - ?name=Alberts<br/>
     *          Iegūt starp skaitļiem(getBetween) - ?age[]=1&age[]=11;<br/>
     *          Kombinēti(combined) - ?age[]=1&age[]=11&name=Alberts&sort[]=name&sort[]=asc;",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="filter",
     *          description="Filtrācija un kārtošana izmantojot augstāk dotos piemērus.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     *)
     */
    public function index(Request $request) {
        if(isset(request()->page)) {
            return UserResource::collection(User::filter($request->all())
                ->paginate(20));
        }
        return UserResource::collection(User::filter($request->all())
            ->get());
    }


    /**
     * @OA\Get(
     *      path="/users/{user}",
     *      operationId="getUsersById",
     *      tags={"User"},
     *      summary="Iegūst lietotāju",
     *      description="Iegūst konkrētu lietotāju pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="user",
     *          description="Lietotāja id.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     *)
     */
    public function show(User $user) {
        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *      path="/me",
     *      operationId="updateMe",
     *      tags={"User"},
     *      summary="Rediģē lietotāja datus",
     *      description="Rediģē autorizētā lietotāja datus",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  ref="#components/schemas/UserRequest"
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     *)
     */

    public function update(User $user, request $request)
    {
        $validated = $request->validate([
            'avatar' => 'file',
            'firstname' => 'required',
            'lastname' => 'required',
            'birthday' => [
                'required',
                'date',
                new IsAdult(),
            ],
            'language' => '',
            'gender' => 'required',
            'about_me' => '',
        ]);
        if ($request->file('avatar') !== null) {
            $file = $request['avatar'];
            $file->store('public/avatars');
            $filename = $file->hashName();
            $validated['avatar'] = $filename;
        }
        if (isset($validated['birthday'])) {
            $age = date_create(date('Y/m/d'))->diff(date_create($validated['birthday']))->format('%Y');
            auth()->user()->update(['age' => $age]);
        }

        $user->update($validated);

        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *      path="/user/rate",
     *      operationId="postUserRate",
     *      tags={"User"},
     *      summary="Lietotāju vērtēšana",
     *      description="Lietotāju vērtēšana, kur kāds lietotājs, novērtē kādu citu lietoāju",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(format="integer", description="Lietotāja id, kurš tiek vēretēts", property="user_id"),
     *                  @OA\Property(format="integer", description="Vērtējums ar skaitli", property="rating"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Jūs jau esat novērtējuši.",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Nevar novērtēt pats sevi.",
     *      )
     *)
     */
    public function rate(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required',
            'rating' => 'required',
        ]);
        $validated['rater_id'] = auth()->user()->id;

        $raterRates = RatingLog::where('user_id', $validated['user_id'])
            ->where('rater_id', auth()->user()->id)->get();

        if (count($raterRates) > 0) {
            return response()->json([
                'error' => [
                    'data' => 'Jūs jau esat novērtējuši.',
                ]
            ], 400);
        } elseif ($validated['user_id'] == auth()->user()->id) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar novērtēt pats sevi.',
                ]
            ], 400);
        } else {
            RatingLog::create($validated);
            $user = User::find($validated['user_id']);
            $userRates = RatingLog::where('user_id', $user->id)->select('rating')->get();
            $ratingSum = 0;
            foreach ($userRates as $value) {
                $ratingSum += $value['rating'];
            }

            $user->rating = round($ratingSum / count($userRates), 2);
            $user->rate_count = RatingLog::where('user_id', $user->id)->count();
            $user->save();
            return new UserResource($user);
        }
    }

    /**
     * @OA\Get(
     *      path="/rated_user",
     *      operationId="getRatedUser",
     *      tags={"User"},
     *      summary="Iegūst visus kas novērtēja autorizēto lietotāju",
     *      description="Iegūst visus lietotājus, kuri ir novērtējuši autorizētā lietotāja profilu.",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function ratedUser() {
        $ratings = RatingLog::where('user_id', auth()->user()->id)->get();
        $users = array();
        $uniqueUser = array();

        foreach ($ratings as $user) {
            if (!in_array($user['rater_id'], $uniqueUser)) {
                $users[] = User::where('id', $user['rater_id'])->first();
                $uniqueUser[] = $user['rater_id'];
            }
        }
        return UserResource::collection($users);
    }

    /**
     * @OA\Get(
     *      path="/user_rated",
     *      operationId="getUserRated",
     *      tags={"User"},
     *      summary="Iegūst lietotājusi, kuri tika novērtēti ar autorizēto lietotāju",
     *      description="Iegūst visus lietotājus, kur autorizētais lietotājs ir novērtējis, kādu citu lietotāju.",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function userRated() {
        $ratings = RatingLog::where('rater_id', auth()->user()->id)->get();
        $users = array();
        $uniqueUser = array();

        foreach ($ratings as $user) {
            if (!in_array($user['user_id'], $uniqueUser)) {
                $users[] = User::where('id', $user['user_id'])->first();
                $uniqueUser[] = $user['user_id'];
            }
        }
        return UserResource::collection($users);
    }

    public function userLeaderboard() {
        $sortedUsers = User::where('rating', '>', 0)
            ->where('rate_count', '>=', 50)
            ->orderBy('rating', 'desc')
            ->orderBy('rate_count', 'desc')
            ->paginate(20);
        return UserResource::collection($sortedUsers);
    }

    public function userReadSchoolExp(){

        if (auth()->user()->next_read_school_beginning >= Carbon::now()){
            return response()->json([
                'message' => 'Nav vēl pagājušas 24h, lai lasītu nākamo rakstu',
            ], 400);
        }

        auth()->user()->update([
            'read_school_exp' => auth()->user()->read_school_exp + 1,
            'next_read_school_beginning' => Carbon::now()->addDay(),
        ]);
        return new UserResource(auth()->user());

    }

    public function getFile (Request $request, User $user)
    {
        if(!$request->hasValidSignature()) return abort(401);
        $user->avatar = Storage::disk('local')->path('public/avatars/'.$user->avatar);
        return response()->file($user->avatar);
    }

}
