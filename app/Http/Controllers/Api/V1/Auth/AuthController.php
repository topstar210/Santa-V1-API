<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\AuthRepository;
use Illuminate\Http\Response;
use App\Traits\ApiResponseTrait;
// use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public $authRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if ($token = $this->guard()->attempt($credentials)) {
                $user = $this->guard()->user();
                // $isEmailVerify = $user->email_verified_at;
                // if (!isset($isEmailVerify)) {
                //     event(new Registered($user));
                //     // return self::apiResponseError(null, 'The verification link sent. ', Response::HTTP_ACCEPTED);
                // }

                if ($user->status === 'active') {
                    $data = $this->respondWithToken($token);
                    return self::apiResponseSuccess($data, 'Logged In Successfully !');
                } else {
                    return self::apiResponseError(null, 'Your account is inactive. Please contact support.', Response::HTTP_FORBIDDEN);
                }
            } else {
                return self::apiResponseError(null, 'Invalid Email and Password !', Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }

    public function emailVerify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return self::apiResponseSuccess(null, 'Email Validation Was Successfully !');
    }


    public function loginByCode(Request $request)
    {
        try {
            $credentials = $request->only('code');
            $token = Auth::guard('code')->attempt($credentials);

            if ($token) {
                $data = $this->respondWithToken($token);
                return self::apiResponseSuccess($data, 'Logged In Successfully !');
            } else {
                return self::apiResponseError(null, 'Invalid the login code !', Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }


    public function register(RegisterRequest $request)
    {
        try {
            $requestData = $request->only(
                'name',
                'email',
                'password',
                'password_confirmation'
            );
            $user = $this->authRepository->register($requestData);
            if ($user) {
                if ($token = $this->guard()->attempt($requestData)) {
                    $data = $this->respondWithToken($token);
                    return self::apiResponseSuccess($data, 'Successfully registered', Response::HTTP_OK);
                }
            }
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }

    public function profile()
    {
        try {
            $data = $this->guard()->user();
            return self::apiResponseSuccess($data, 'Profile Fetched Successfully !');
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }


    public function logout()
    {
        try {

            $this->guard()->logout();
            return self::apiResponseSuccess(null, 'Logged out successfully!');

        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }


    public function refresh()
    {
        try {

            $refreshed = $this->guard()->refresh();
            $data = $this->respondWithToken($refreshed);

            return self::apiResponseSuccess($data, 'Token Refreshed!');

        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return auth()->guard('api');
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
        $data = [
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60 * 24 * 30,
                'user' => $this->guard()->user()
            ]
        ];

        return $data[0];
    }
}
