<?php
/**
 * Created by PhpStorm.
 * User: LunaticFish
 * Date: 2019/3/13
 * Time: 17:46
 */

use Illuminate\Support\Facades\Route;

if (env('ENABLE_RESPONDER_REFERENCE_DOC', false)) {
    /**
     *
     * @see \StackBoom\ApiResponder\Controllers\DocumentController::reference()
     */
    Route::get('/doc/responder_ref/{lang?}','\StackBoom\ApiResponder\Controllers\DocumentController@reference');
}

if (env('ENABLE_RESPONDER_HELP_DOC', false)) {
    Route::get('/help/{msg}/{lang?}', '\StackBoom\ApiResponder\Controllers\DocumentController@help');
}