<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{

  public function all()
  {
    return User::orderBy('id', 'desc')->get();
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

  public function delete($id)
  {
    $user = User::find($id);

    if ($user) {
      $user->delete();

      return true;
    }
    return false;
  }

}