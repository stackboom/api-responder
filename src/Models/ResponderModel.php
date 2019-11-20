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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class ResponderModel
 * @property-read string camel_name
 * @property string name
 * @property string message
 * @property integer code
 * @property string lang
 * @property array param
 * @property string help
 * @package StackBoom\ApiResponder
 * @mixin \Eloquent
 * User: LunaticFish
 * Date: 2019/3/29
 * Time: 11:09
 */

class ResponderModel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'lang',
        'message',
        'code',
        'param',
        'comment',
        'help',
    ];

    /**
     * @param string $message
     * @param array|string $lang
     * @return Collection
     */
    public static function fetch(string $name, $lang){
        if(is_string($lang))$lang=[$lang];
        return static::withoutTrashed()->where('name',$name)->whereIn('lang',$lang)->get();
    }

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

        $this->setTable(config('api_responder.table_name','responder_languages'));
    }
}