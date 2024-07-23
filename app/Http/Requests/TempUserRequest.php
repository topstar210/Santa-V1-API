<?php

namespace App\Http\Requests;


class TempUserRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => 'required',
      'login_code' => 'required',
      'expired_date' => 'required',
    ];
  }

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * @return array
   * Custom validation message
   */
  public function messages()
  {
    return [
      'name.required' => 'Name is missing',
      'login_code.required' => 'Login Code is missing',
      'expired_date.required' => 'Expired Date is missing',
    ];
  }
}
