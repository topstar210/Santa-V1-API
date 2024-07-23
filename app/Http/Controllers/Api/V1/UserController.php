<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class UserController extends Controller
{
  use ApiResponseTrait;

  public $userRepository;

  public $not_found;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
    $this->not_found = Response::HTTP_NOT_FOUND;
  }

  /**
   * Display a listing of the users.
   *
   * @return \Illuminate\Http\Response user list 
   */
  public function index()
  {
    try {
      # fetch users
      $data = $this->userRepository->all();
      return self::apiResponseSuccess($data, 'Fetched all users!');

    } catch (\Exception $e) {
      return self::apiServerError($e->getMessage());
    }
  }

  public function create(UserRequest $request)
  {
    try {
      $requestData = $request->only(
        'name',
        'email',
        'status',
        'password',
        'password_confirmation'
      );
      $user = $this->userRepository->create($requestData);
      return self::apiResponseSuccess($user, 'Successfully added', Response::HTTP_OK);
    } catch (\Exception $e) {
      return self::apiServerError($e->getMessage());
    }
  }

  public function analytic(Request $request)
  {
    try {
      $result = $this->userRepository->analytic();
      return self::apiResponseSuccess($result, 'Successfully Analyze', Response::HTTP_OK);
    } catch (\Exception $e) {
      return self::apiServerError($e->getMessage());
    }
  }

}
