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
use Illuminate\Support\Str;
use StackBoom\ApiResponder\Models\ResponderModel;
use StringTemplate\Engine;

/**
 * Class Responder
 * @property $name
 * @property $code
 * @property $message
 * @property $data
 * @property $body
 * @package StackBoom\ApiResponder
 */
class Responder implements Responsable
{

    /**
     * @var int $code
     */
    protected $code;

    /**
     * @var string $message
     */
    protected $message;

    /**
     * @var array|Arrayable $data
     */
    protected $data;

    /**
     * @var array|$append
     */
    protected $append;

    /**
     * @var bool|array $locale
     */
    protected $locale;

    /**
     * @var ResponderModel[]|Collection $languages
     */
    protected $languages;


    /**
     * @return mixed
     */
    public function getName()
    {
        return Str::snake($this->name);
    }

    /**
     * @param mixed $name
     * @return Responder
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return Responder
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Responder
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return Responder
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     * @return Responder
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }


    /**
     * @param $request
     * @return array|bool
     */
    public function getLocale($request)
    {
        return $this->locale===false?false:$this->locale??array_values(array_map('strtolower',array_unique(array_filter([
            $request?$request->getLocale():false,
            config('api_responder.locale'),
            config('app.locale'),
            config('app.fallback_locale')
        ]))));
    }

    /**
     * @param $locale
     * @return static
     */
    public function setLocale($locale)
    {
        if(is_string($locale) && is_array($this->locale)){
            //TODO:strict
            if(!in_array($locale,$this->locale))
                array_push($this->locale,$locale);
        }else{
            $this->locale = $locale;
        }
        return $this;
    }

    public function getDefaultCode(){
        return 200;
    }

    /**
     * Responder constructor.
     * @param $name // name
     * @param array $data
     * @param mixed ...$param
     */
    public function __construct($name,$data=[],$body=[])
    {
        $this->name = $name;
        $this->data = $data;
        $this->body = $body;
        $this->locale = config('api_responder.locale');
    }

    /**
     * @param $name
     * @param $data
     * @return static
     */
    public static function __callStatic($name,$data)
    {
        return new static($name,...$data);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $locale = $this->getLocale($request);

        if($locale===false)
            return JsonResponse::create(array_merge(array_filter([
                'msg'=>$this->getName(),
                'code'=>$this->getCode()??$this->getDefaultCode(),
            ]),[
                'data'=>$this->getData()
            ],
                $this->getBody()
            ));


        $this->languages = ResponderModel::fetch($this->name,$locale);

        if($this->languages instanceof Collection && $this->languages->isNotEmpty()){
            /**
             * @var ResponderModel $responder
             */
            $responder = $this->languages->sort(function($a,$b)use($locale){
                $offset_a = array_search($a->lang,$locale);
                $offset_b = array_search($b->lang,$locale);

                if($offset_a === $offset_b)
                    return 0;

                if($offset_a===false || $offset_b===false){
                    return $offset_a===false?1:-1;
                }
                return $offset_a>$offset_b?1:-1;
            })->first();

            $data = $this->getData();
            $message = (new Engine())->render($responder->message,$data);

            return JsonResponse::create(array_merge(array_filter([
                'msg'=>$this->getName(),
                'message'=>$this->getMessage()??$message,
                'code'=>$this->getCode()??$responder->code??$this->getDefaultCode(),
            ]),[
                'data'=>$data
            ],
                $this->getBody()
            ));
        }else{
            //TODO:strict
        }

    }
}