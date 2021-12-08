<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class EditKatasApiRequest extends ApiFormRequest
{
   /**
    * @param null $keys
    *
    * @return array|null
    */
   public function all($keys = null)
   {
      // Add route parameters to validation data
      return array_merge(parent::all(), $this->route()->parameters());
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
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
   public function rules()
   {
      return [
            //
         ];
   }
}
