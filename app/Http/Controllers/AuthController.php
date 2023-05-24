<?php

namespace App\Http\Controllers;

use App\HelperService\Helpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(
        Helpers $helpers
    )
    {
       $this->middleware('auth:api', ['except' => ['auth/phone', 'auth/verify']]);
        $this->created = 'Создано';
        $this->createdCode = 201;
        $this->unauthorized = 'Неавторизован';
        $this->unauthorizedCode = 401;
        $this->accepted = 'Успешно';
        $this->acceptedCode = 200;
        $this->internalError = 'Внутренняя ошибка';
        $this->internalErrorCode = 500;
        $this->helpers = $helpers;

    }

    /**
     * Создание нового токена JWT
     * @param $token
     * @param $user
     * @return array
     */
    protected function createNewToken($token, $user)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'middlename' => $user->middlename,
                'phone' => $user->phone,
                'email' => $user->email,
                'date_of_birth' => $user->date_of_birth
            ],
            'expires_in' => JWTAuth::factory()->getTTL() * 100
        ];
    }

    public function phoneAuth(Request $request){

        $this->validate($request, [
            'phone' => 'required|string',
            'smsFlag' => 'boolean'
        ]);

        $phone = $request['phone'];
        $smsFlag = $request['smsFlag'] ?? null;

        $devPhone = [
            '79524061727',
        ];

        if ($smsFlag != null) {
            $code = in_array($phone, $devPhone) ? '1111' : $this->loginBySms($phone);
        } else {
            $code = in_array($phone, $devPhone) ? '1111' : '1111';
        }

        if(is_numeric($code)){
            $user = User::where('phone', $phone)->first();
            if(is_null($user)){
                $user = $this->createNewUser($phone);
            }
            $user->password = app('hash')->make($code);
            $user->save();
            return response()->json(
                ['id' => $user->id, 'code' => $code],
                200
            );
        } else {
            return response()->json(['message' => 'Слишком много попыток, попробуйте позже'], 400);
        }
    }

    /**
     * Метод создания нового пользователя
     * @param $phone
     * @return User
     */
    public function createNewUser($phone)
    {
        $newUser = new User;
        $newUser->name = 'Ваше имя';
        $newUser->phone = $phone;
        $newUser->last_entrance = date("Y-m-d H:i:s");
        $newUser->country = 'Россия';
        $newUser->city = 'Москва';
        $newUser->status = 1;
        $newUser->created_at = date("Y-m-d H:i:s");
        $newUser->updated_at = date("Y-m-d H:i:s");
        $newUser->save();
        return $newUser;
    }


    public function loginBySms($phone)
    {
        $code = rand(1000, 9999);
        //$sendsms = (new SmsSendController)->SmsSend($phone, $code);
        return $code;
    }


    public function verifyCode(Request $request){
        $credentials = $request->only(['id', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return $this->helpers->response(false, $this->unauthorized, null, null, $this->unauthorizedCode);
        }
        $accessToken = $this->createNewToken($token, auth()->user());
        $results = $this->helpers->response(true, $this->accepted, $accessToken, null, $this->acceptedCode);
        if ($results->isSuccessful()) {
            return $results;
        } else {
            return $this->helpers->response(false, $this->unauthorized, null, null, $this->unauthorizedCode);
        }

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
