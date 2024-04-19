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

class UserController extends Controller
{
    public function index(): Collection
    {
        return User::all();
    }

    public function show($id): Model|Collection|Builder|array|null
    {
        return User::query()->findOrFail($id);
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required|string|digits:12|starts_with:998|unique:users,phone',
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

    public function update(Request $request, $id): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required|string|digits:12|starts_with:998|unique:users,phone',
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

    public function destroy($id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        $user->delete();
        return response()->json(['success' => true, 'data' => $user], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('phone', 'password');

        //check user's status
        $user = DB::table('users')
            ->where('phone', $credentials['phone'])
            ->first();

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

    public function logout(): string
    {
        auth()->user()->tokens()->delete();
        return 'User logged out successfully';
    }

    private function token(User $user): NewAccessToken
    {
        $token = $user->createToken('ClientAccessToken', [User::ACCESS_API], Carbon::now()->addHours(24)); //one day
        $user->auth = Carbon::now();
        $user->update();
        return $token;
    }
}
