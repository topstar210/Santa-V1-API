<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
// use App\Http\Requests\ModuleRequest;
use App\Repositories\ModuleRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
  use ApiResponseTrait;

  public $moduleRepository;

  public $not_found;

  public function __construct(ModuleRepository $moduleRepository)
  {
    $this->moduleRepository = $moduleRepository;
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
      $data = $this->moduleRepository->all();
      return self::apiResponseSuccess($data, 'Fetched all modules!');

    } catch (\Exception $e) {
      return self::apiServerError($e->getMessage());
    }
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    try {
      $data = $this->moduleRepository->create($request->all());
      return self::apiResponseSuccess($data, 'New Module Added!');
    } catch (\Exception $e) {
      return self::apiServerError($e->getMessage());
    }
  }

  public function all()
  {
    $data = $this->moduleRepository->all();
    return self::apiResponseSuccess($data, 'Found ' . count($data) . ' Modules');
  }

  public function analytic(Request $request)
  {
    try {
      $result = $this->moduleRepository->analytic();
      return self::apiResponseSuccess($result, 'Successfully Analyze', Response::HTTP_OK);
    } catch (\Exception $e) {
      return self::apiServerError($e->getMessage());
    }
  }

}
