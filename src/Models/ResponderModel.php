<?php
/**
 * Created by PhpStorm.
 * User: LunaticFish
 * Date: 2019/3/28
 * Time: 16:21
 */

namespace StackBoom\ApiResponder\Models;


use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class ResponderModel
 * @property string camel_name
 * @property string name
 * @property string message
 * @property integer code
 * @property string lang
 * @property string param
 * @property string help
 * @package StackBoom\ApiResponder
 * @mixin \Eloquent
 * User: LunaticFish
 * Date: 2019/3/29
 * Time: 11:09
 */

class ResponderModel extends Model implements Responsable
{
    /**
     * @return string
     */
    public function getCamelNameAttribute(): string
    {
        return Str::camel(Str::lower($this->name));
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('api_responder.table_name','api_responder'));
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        // TODO: Implement toResponse() method.
    }
}