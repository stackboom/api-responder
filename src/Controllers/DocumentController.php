<?php
/**
 * Created by PhpStorm.
 * User: LunaticFish
 * Date: 2019/3/13
 * Time: 18:05
 */

namespace StackBoom\ApiResponder\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use StackBoom\ApiResponder\Models\ResponderModel;

class DocumentController extends Controller
{
    public function reference(string $lang=null){

        /**
         * @var ResponderModel[]|Collection|null $responders
         */
        if($lang){
            $responders = ResponderModel::where('lang',$lang)->get();
        }else{
            $responders = ResponderModel::all()->groupBy('name');
        }

        return Response::view('api_responder::markdown/reference',[
            'responders'=>$responders,
            'lang'=>$lang
        ])->withHeaders([
            'Content-Type'=>'text/markdown'
        ]);
    }

    public function help(string $msg,string $lang=null){
        if($lang){
            $responder = ResponderModel::where('name',$msg)->where('lang',$lang)->first();
        }else{
            $responder = ResponderModel::where('name',$msg)->first();
        }

        return Response::view('api_responder::markdown/help',[
            'responder'=>$responder,
            'lang'=>$lang
        ])->withHeaders([
            'Content-Type'=>'text/markdown'
        ]);
    }
}