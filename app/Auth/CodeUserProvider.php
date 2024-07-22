<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class CodeUserProvider extends EloquentUserProvider
{
  public function retrieveByCredentials(array $credentials)
  {
    if (empty($credentials['code'])) {
      return null;
    }

    // implement the logic to retrieve the user by the code here.
    return $this->createModel()->newQuery()
      ->where('login_code', $credentials['code'])
      ->first();
  }

  public function validateCredentials(UserContract $user, array $credentials)
  {
    // Assuming the user is already validated by the code, return true.
    return true;
  }
}
