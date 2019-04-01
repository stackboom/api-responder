<?php
/**
 * Created by PhpStorm.
 * User: LunaticFish
 * Date: 2019/3/13
 * Time: 17:46
 */

use Illuminate\Support\Facades\Route;

if (env('ENABLE_ERRCODE_DOC', false)) {
    /**
     * @see \StackBoom\ApiResponder\Controllers\DocController::code()
     */
    Route::get('/doc/errcode/{code?}','\StackBoom\ErrorCode\Controllers\DocController@code');
}