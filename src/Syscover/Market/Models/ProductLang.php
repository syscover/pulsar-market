<?php namespace Syscover\Market\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Traits\CustomizableValues;
use Syscover\Core\Models\CoreModel;
use Syscover\Admin\Traits\Slugable;
use Syscover\Admin\Traits\Translatable;

/**
 * Class Product
 * @package Syscover\Market\Models
 */

class ProductLang extends CoreModel
{
    use CustomizableValues, Translatable, Slugable;

	protected $table        = 'market_product_lang';
    protected $fillable     = ['id', 'object_id', 'lang_id', 'name', 'slug', 'description', 'data'];
    public $incrementing    = false;
    protected $casts        = [
        'data' => 'array'
    ];

    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query;
    }
}