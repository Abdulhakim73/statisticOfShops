<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\NewAccessToken;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * Users list.
     *
     * @OA\Get(
     *      path="/api/users",
     *      operationId="usersList",
     *      summary="Users list",
     *      tags={"Users Routes"},
     *      security={{ "bearerAuth": {} }},
     *
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     */
    public function index(): Collection
    {
        return User::all();
    }

    /**
     * User show.
     *
     * @OA\Get(
     *      path="/api/user/{id}",
     *      operationId="UserShow",
     *      summary="User show",
     *      tags={"Users Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         example="2",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=404, description="User not found!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     * @return Model|Collection|Builder|array|null
     */
    public function show($id): Model|Collection|Builder|array|null
    {
        return User::query()->findOrFail($id);
    }

    /**
     * User add.
     *
     * @OA\Post(
     *      path="/api/user/create",
     *      operationId="UserCreate",
     *      summary="User Create",
     *      tags={"Users Routes"},
     *      security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Phone number",
     *         required=true,
     *         example="+998977731573",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         example="123456",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!"),
     *      @OA\Response(response=422, description="Validation error!", @OA\JsonContent()),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required|integer|digits:12|starts_with:998|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => true, 'message' => $validate->messages()], 400);
        }

        $inputs['phone'] = $request->input('phone');
        $inputs['password'] = Hash::make($request->input('password'));
        $inputs['status'] = 'active';

        $user = User::query()->create($inputs);
        return response()->json(['success' => true, 'message' => 'User created', 'data' => $user], 200);
    }


    /**
     * User update.
     *
     * @OA\Put(
     *      path="/api/user/update/{id}",
     *      operationId="UserUpdate",
     *      summary="User update",
     *      tags={"Users Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         example="2",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Phone number",
     *         required=true,
     *         example="+998977731573",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         example="user12345",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=422, description="Validation error!", @OA\JsonContent()),
     *      @OA\Response(response=404, description="User not found!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required|integer|digits:12|starts_with:998|unique:users,phone',
            'password' => 'nullable|string|min:6',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => true, 'message' => $validate->messages()], 400);
        }

        $user = User::query()->findOrFail($id);
        $inputs['phone'] = $request->input('phone') ?? $user->phone;
        $inputs['password'] = $request->input(Hash::make('password')) ?? $user->password;
        $user->update($inputs);
        return response()->json(['success' => true, 'message' => 'User updated'], 200);
    }


    /**
     * User delete info.
     *
     * @OA\Delete(
     *      path="/api/user/delete",
     *      operationId="UserDelete",
     *      summary="User delete",
     *      tags={"Users Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         example="2",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *     @OA\Response(response=404, description="User not found!", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Delete error!", @OA\JsonContent()),
     *     @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        $user->delete();
        return response()->json(['success' => true, 'data' => $user], 200);
    }

    /**
     * User login api.
     *
     * @OA\Post(
     *      path="/api/login",
     *      operationId="authLogin",
     *      summary="User login",
     *      tags={"Authentication"},
     *      @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="User's phone number",
     *         required=true,
     *         example="+998977731573",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         example="123456qwe",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Response(response=200, description="Success!"),
     *      @OA\Response(response=422, description="Validation error!"),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('phone', 'password');

        //check user's status
        $user = DB::table('users')
            ->where('phone', $credentials['phone'])
            ->first();

        //check is blocked user
        if ($user->status == 'blocked') {
            exit("you are blocked");
        }

        if (!$user) {
            return response()->json(['status' => false, 'message' => "User doesn't exists"], 400);
        }

        if (Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']])) {
            $auth = Auth::user();

            $token = $this->token($auth);
            return response()->json([
                'success' => true, 'message' => 'User logged in successfully',
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ]);
        }
        return response()->json(['status' => false, 'message' => "user's details incorrect"]);
    }

    /**
     * User logout.
     *
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="userLogout",
     *      summary="User logout",
     *      tags={"Authentication"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Response(response=200, description="Success!"),
     * )
     *
     * @param Request $request
     */
    public function logout(Request $request): void
    {
        $request->user()->tokens()->delete();

    }

    private function token(User $user): NewAccessToken
    {
        $token = $user->createToken('ClientAccessToken', [User::ACCESS_API], Carbon::now()->addHours(24)); //one day
        $user->auth = Carbon::now();
        $user->update();
        return $token;
    }
}
