<?php
/**
 * Created by PhpStorm.
 * User: LunaticFish
 * Date: 2019/3/28
 * Time: 15:38
 */

namespace StackBoom\ApiResponder;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use StackBoom\ApiResponder\Models\ResponderModel;

class Responder implements Responsable
{

    /**
     * @var array|Arrayable $data
     */
    protected $data;

    /**
     * @var int $code
     */
    protected $code;

    /**
     * @var string|array|Arrayable $message
     */
    protected $message;

    protected static $presets=[
        'Success'=>[
            'code'=>200,
            'message'=>'操作成功.'
        ],
        'Failed'=>[
            'code'=>400,
            'message'=>'操作失败！'
        ],
    ];

    /**
     * @return array|Arrayable
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array|Arrayable $data
     * @return Responder
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return Responder
     */
    public function setCode(int $code): Responder
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return array|Arrayable|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param array|Arrayable|string $message
     * @return Responder
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function __construct($msg,$data=null,$message=null,$code=200)
    {
        $this->msg = $msg;
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
    }

    public static function __callStatic($name, $arguments)
    {
        if(array_key_exists($name,static::$presets)){

            if(isset($arguments[0]) && is_string($arguments[0])){
                $message = $arguments[0];
                $data = $arguments[1]??null;
            }else{
                $data = $arguments[0]??null;
                $message = static::$presets['message']??$name;
            }

            $code = static::$presets[$name]['code']??200;
            return new static($name,$data,$message,$code);

        }else{
            $msg = Str::snake($name);
            if(($responder = ResponderModel::where('name',$msg)->get()) instanceof Collection){
                return new static($responder);
            }else{
                throw new Exception("response code \"{$msg}\" not found;");
            }
        }
    }

    /**
     * toResponse
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|Response
     * User: LunaticFish
     * Date: 2019/3/29
     * Time: 14:34
     */
    public function toResponse($request)
    {
        return JsonResponse::create([
            'msg'=>$this->msg,
            'code'=>$this->code,
            'message'=>$this->parseMessage($request),
        ]);
    }

    /**
     * parseMessage
     * @param \Illuminate\Http\Request $request
     * User: LunaticFish
     * Date: 2019/3/29
     * Time: 14:35
     */
    public function parseMessage($request)
    {
        $lang = $request->getLanguages();
    }
}