<?php

use App\Http\Controllers\EditKataController;
use App\Http\Controllers\PublicKataController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/edit/create-task', [
   TaskController::class,
   'createTask',
])->middleware('throttle:10,60');

Route::group(['prefix' => '/edit/{editToken}'], function () {
   Route::group(['middleware' => 'edit.kata.token.enabled'], function () {
      Route::get('/status', [TaskController::class, 'getTaskStatus']);
      Route::post('/update', [TaskController::class, 'updateTask'])->middleware(
         ['edit.kata.token.not.demo', 'throttle:10,60']
      );
   });

   Route::group(['middleware' => 'edit.kata.token'], function () {
      Route::patch('/solver', [TaskController::class, 'setSolverAbout']);
      Route::post('/public-token/generate', [
         TaskController::class,
         'generatePublicToken',
      ]);

      Route::group(['prefix' => '/katas'], function () {
         Route::get('/', [EditKataController::class, 'katas']);
         Route::patch('/{kataId}/solution/{solutionId}/visibility', [
            EditKataController::class,
            'toggleKataVisibility',
         ]);
         Route::patch('/{kataId}/hide', [
            EditKataController::class,
            'hideAllSolutions',
         ]);
         Route::match(
            ['patch', 'delete'],
            '/{kataId}/solution/{solutionId}/comment',
            [EditKataController::class, 'setComment']
         );
      });
   });
});

Route::group(
   [
      'prefix' => '/public/{publicToken}',
      'middleware' => 'public.kata.token',
   ],
   function () {
      Route::get('/', [PublicKataController::class, 'info']);
   }
);
