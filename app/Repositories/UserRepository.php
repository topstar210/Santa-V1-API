<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Module;
use Illuminate\Support\Facades\Hash;

class UserRepository
{

  public function all($flag = null, $is_temp)
  {
    $user = $flag ? User::where("is_temp", $is_temp)->orderBy('id', 'desc')->get() : User::orderBy('id', 'desc')->get();
    return $user;
  }

  public function create(array $data)
  {
    $data = [
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
      'status' => $data['status']
    ];
    $user = User::create($data);

    return $user;
  }

  public function createTempUser(array $data)
  {
    $data = [
      'name' => $data['name'],
      'login_code' => $data['login_code'],
      'expired_date' => $data['expired_date'],
      'status' => 'active',
      'is_temp' => '1'
    ];
    $user = User::create($data);

    return $user;
  }

  public function findTempUser($code)
  {
    $temp = User::where('login_code', $code)->get();
    return $temp;
  }

  public function delete($id)
  {
    $user = User::find($id);
    if ($user && $user->is_admin == 0) {
      $user->delete();

      return true;
    }
    return false;
  }

  public function analytic()
  {
    $total = User::count('id');
    $temp = User::where('is_temp', 1)->count();
    $modules = Module::with('user')->count('id');

    return [
      'total' => $total,
      'registered' => $total - $temp,
      'temp' => $temp,
      'modules' => $modules
    ];
  }

}