<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EditTokenIsNotDemo
{
   /**
    * Handle an incoming request.
    *
    * @param Request $request
    * @param Closure $next
    * @return mixed
    */
   public function handle(Request $request, Closure $next)
   {
      $editToken = $request->route('editToken', '');
      if ($editToken !== 'demo') {
         return $next($request);
      } else {
         return response(
            ['error' => 'Access token is invalid', 'error_code' => 3],
            403
         );
      }
   }
}
