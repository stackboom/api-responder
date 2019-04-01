<?php
/**
 * Created by PhpStorm.
 * User: LunaticFish
 * Date: 2019/3/13
 * Time: 18:05
 */

namespace StackBoom\ApiResponder\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

class DocController extends Controller
{
    public function code(string $code=null){
        if($code=$code??Request::input('code',null)){
            if(is_numeric()){
                
            }
        }
    }
}