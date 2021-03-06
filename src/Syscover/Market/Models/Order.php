<?php namespace Syscover\Market\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Models\Country;
use Syscover\Admin\Models\TerritorialArea1;
use Syscover\Admin\Models\TerritorialArea2;
use Syscover\Admin\Models\TerritorialArea3;
use Syscover\Core\Models\CoreModel;
use Syscover\Crm\Models\Customer;

/**
 * Class Order
 * @package Syscover\Market\Models
 */

class Order extends CoreModel
{
	protected $table        = 'market_order';
    protected $fillable     = ['id', 'date', 'payment_method_id', 'status_id', 'ip', 'data', 'comments', 'transaction_id', 'discount_amount', 'subtotal_with_discounts', 'tax_amount', 'cart_items_total_without_discounts', 'subtotal', 'shipping_amount', 'total', 'has_gift', 'gift_from', 'gift_to', 'gift_message', 'customer_id', 'customer_group_id', 'customer_company', 'customer_tin', 'customer_name', 'customer_surname', 'customer_email', 'customer_mobile', 'customer_phone', 'has_invoice', 'invoiced', 'invoice_number', 'invoice_company', 'invoice_tin', 'invoice_name', 'invoice_surname', 'invoice_email', 'invoice_mobile', 'invoice_phone', 'invoice_country_id', 'invoice_territorial_area_1_id', 'invoice_territorial_area_2_id', 'invoice_territorial_area_3_id', 'invoice_zip', 'invoice_locality', 'invoice_address', 'invoice_latitude', 'invoice_longitude', 'invoice_comments', 'has_shipping', 'shipping_tracking_id', 'shipping_company', 'shipping_name', 'shipping_surname', 'shipping_email', 'shipping_mobile', 'shipping_phone', 'shipping_country_id', 'shipping_territorial_area_1_id', 'shipping_territorial_area_2_id', 'shipping_territorial_area_3_id', 'shipping_zip', 'shipping_locality', 'shipping_address', 'shipping_latitude', 'shipping_longitude', 'shipping_comments'];
    protected $casts        = [
        'data' => 'array'
    ];
    public $with = [
        'customer',
        'payment_methods',
        'statuses',
        'rows',
        'discounts',
        'shipping_countries',
        'shipping_territorial_area_1',
        'shipping_territorial_area_2',
        'shipping_territorial_area_3',
        'invoice_countries',
        'invoice_territorial_area_1',
        'invoice_territorial_area_2',
        'invoice_territorial_area_3'
    ];
    private static $rules   = [
        'status'            => 'required',
        'payment_method_id' => 'required',
        'gift_from'         => 'between:2,255',
        'gift_to'           => 'between:2,255',
        'customer_id'       => 'required',
        'customer_company'  => 'between:2,255',
        'customer_tin'      => 'between:2,255',
        'customer_name'     => 'between:2,255',
        'customer_surname'  => 'between:2,255',
        'customer_email'    => 'required|between:2,255|email',
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query
            ->join('crm_customer', 'market_order.customer_id', '=', 'crm_customer.id')
            ->join('market_order_row', 'market_order.id', '=', 'market_order_row.order_id')
            ->join('market_payment_method', function ($join) {
                $join->on('market_order.payment_method_id', '=', 'market_payment_method.id')
                    ->where('market_payment_method.lang_id', '=', base_lang());
            })
            ->join('market_order_status', function ($join) {
                $join->on('market_order.status_id', '=', 'market_order_status.id')
                    ->where('market_order_status.lang_id', '=', base_lang());
            })
            ->groupBy('market_order.id', 'market_payment_method.ix', 'market_order_status.ix')
            ->addSelect(
                'crm_customer.*',
                'market_payment_method.*',
                'market_order_status.*',
                'market_order.*',

                'crm_customer.id as crm_customer_id',
                'market_payment_method.id as market_payment_method_id',
                'market_payment_method.name as market_payment_method_name',
                'market_order_status.id as market_order_status_id',
                'market_order_status.name as market_order_status_name',
                'market_order.id as market_order_id'
            );
    }

    public function scopeCalculateFoundRows($query)
    {
        return $query->select(DB::raw('SQL_CALC_FOUND_ROWS market_order.id'));
    }

    // Accessors
    public function getDateAttribute($value)
    {
        // https://es.wikipedia.org/wiki/ISO_8601
        // return (new Carbon($value))->toW3cString();
        return $value ? (new Carbon($value))->format('Y-m-d\TH:i:s') : null;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function statuses()
    {
        return $this->hasMany(OrderStatus::class, 'id','status_id');
    }

    public function payment_methods()
    {
        return $this->hasMany(PaymentMethod::class, 'id', 'payment_method_id');
    }

    public function shipping_countries()
    {
        return $this->hasMany(Country::class, 'id', 'shipping_country_id');
    }

    public function shipping_territorial_area_1()
    {
        return $this->hasOne(TerritorialArea1::class, 'id', 'shipping_territorial_area_1_id');
    }

    public function shipping_territorial_area_2()
    {
        return $this->hasOne(TerritorialArea2::class, 'id', 'shipping_territorial_area_2_id');
    }

    public function shipping_territorial_area_3()
    {
        return $this->hasOne(TerritorialArea3::class, 'id', 'shipping_territorial_area_3_id');
    }

    public function invoice_countries()
    {
        return $this->hasMany(Country::class, 'id', 'invoice_country_id');
    }

    public function invoice_territorial_area_1()
    {
        return $this->hasOne(TerritorialArea1::class, 'id', 'invoice_territorial_area_1_id');
    }

    public function invoice_territorial_area_2()
    {
        return $this->hasOne(TerritorialArea2::class, 'id', 'invoice_territorial_area_2_id');
    }

    public function invoice_territorial_area_3()
    {
        return $this->hasOne(TerritorialArea3::class, 'id', 'invoice_territorial_area_3_id');
    }

    public function rows()
    {
        return $this->hasMany(OrderRow::class, 'order_id');
    }

    public function discounts()
    {
        return $this->hasMany(CustomerDiscountHistory::class, 'order_id');
    }

    /**
     * Get subtotal
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getSubtotal($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->subtotal, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format discount amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getDiscountAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->discount_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format subtotal with discounts amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getSubtotalWithDiscounts($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->subtotal_with_discounts, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format tax amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getTaxAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->tax_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format cart items total without discounts
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getCartItemsTotalWithoutDiscounts($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->cart_items_total_without_discounts, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format shipping amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getShippingAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->shipping_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get total without shipping
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getTotalWithoutShipping($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->total - $this->shipping_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format total
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getTotal($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->total, $decimals, $decimalPoint, $thousandSeparator);
    }
}
