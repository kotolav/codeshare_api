<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends ApiFormRequest
{
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
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
   public function rules()
   {
      return [
         'cookies' => 'required_without:login,password|string',
         'login' => 'required_without:cookies|string',
         'password' => 'required_without:cookies|string',
         'shiftStatuses' => 'boolean',
      ];
   }
}
