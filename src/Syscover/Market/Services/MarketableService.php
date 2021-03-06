<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Order;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductLang;

class MarketableService
{
    public static function create($payload, $marketable)
    {
        self::checkCreate($payload);

        // get product related
        $product = Product::where('object_type', $payload['object_type'])->where('object_id', $payload['object_id'])->first();

        // check if there is id
        if($product)
        {
            // create new lang product
            $payload['id'] = $product->id;
        }
        else
        {
            // create new product
            $product = Product::create(self::builder($payload, [
                'sku',
                'field_group_id',
                'parent_id',
                'object_type',
                'object_id',
                'class_id',
                'enable_from',
                'enable_to',
                'starts_at',
                'ends_at',
                'limited_capacity',
                'fixed_cost',
                'cost_per_sale',
                'weight',
                'active',
                'sort',
                'price_type_id',
                'product_class_tax_id',
                'cost',
                'subtotal',
                'data_lang'
            ]));
            $payload['id'] = $product->id;

            // set product id in marketable object
            $marketable->product_id = $product->id;
            $marketable->save();
        }

        // if lang_id is empty, set lang_id with base_lang
        if (empty($payload['lang_id'])) $payload['lang_id'] = config('pulsar-admin.base_lang');

        // create product lang
        $product = ProductLang::create(self::builder($payload, [
            'id',
            'lang_id',
            'name',
            'slug',
            'data'
        ]));

        // product already is create, it's not necessary update product with data_lang value
        Product::getDataLang($payload['lang_id'], $payload['id']);

        // get object with builder, to get every relations
        $product = Product::builder()
            ->where('market_product.id', $product->id)
            ->where('market_product_lang.lang_id', $product->lang_id)
            ->first();

        // set categories
        if(! empty($payload['categories_id'])) $product->categories()->sync($payload['categories_id']);

        // set sections
        if(! empty($payload['sections_id'])) $product->sections()->sync($payload['sections_id']);

        return $product;
    }

    public static function update($payload)
    {
        self::checkUpdate($payload);

        // get product related
        $product = Product::where('object_type', $payload['object_type'])->where('object_id', $payload['object_id'])->first();

        Product::where('id', $product->id)->update(self::builder($payload, [
            'sku',
            'field_group_id',
            'parent_id',
            'object_type',
            'object_id',
            'class_id',
            'enable_from',
            'enable_to',
            'starts_at',
            'ends_at',
            'limited_capacity',
            'fixed_cost',
            'cost_per_sale',
            'weight',
            'active',
            'sort',
            'price_type_id',
            'product_class_tax_id',
            'cost',
            'subtotal',
            'data_lang'
        ]));

        // if lang_id is empty, set lang_id with base_lang
        if (empty($payload['lang_id'])) $payload['lang_id'] = config('pulsar-admin.base_lang');

        // update product lang
        ProductLang::where('market_product_lang.id', $product->id)
            ->where('market_product_lang.lang_id', $payload['lang_id'])
            ->update(self::builder($payload, [
                'name',
                'slug',
                'data'
            ]));

        // get product instance
        $product = Product::builder()
            ->where('market_product.id', $product->id)
            ->where('market_product_lang.lang_id', $payload['lang_id'])
            ->first();

        // set categories
        if(! empty($payload['categories_id'])) $product->categories()->sync($payload['categories_id']);

        // set sections
        if(! empty($payload['sections_id'])) $product->sections()->sync($payload['sections_id']);

        return $product;
    }

    private static function builder($payload, $filterKeys = null)
    {
        $payload = collect($payload);
        if($filterKeys)
        {
            $payload = $payload->only($filterKeys);
        }
        else
        {
            $payload = $payload->only([
                'id',
                'lang_id',
                'name',
                'slug',
                'sku',
                'field_group_id',
                'parent_id',
                'class_id',
                'enable_from',
                'enable_to',
                'starts_at',
                'ends_at',
                'limited_capacity',
                'fixed_cost',
                'cost_per_sale',
                'weight',
                'active',
                'sort',
                'price_type_id',
                'product_class_tax_id',
                'cost',
                'subtotal',
                'data_lang',
                'data'
            ]);
        }

        if($payload->has('weight') && $payload->get('weight') === null) $payload['weight'] = 0;

        return $payload->toArray();
    }

    private static function checkCreate($payload)
    {
        if(empty($payload['name']))          throw new \Exception('You have to define a name field to create a product');
        if(empty($payload['slug']))          throw new \Exception('You have to define a slug field to create a product');

        // avoid check if is create lang action
        if(empty($payload['id']))
        {
            if(empty($payload['class_id']))      throw new \Exception('You have to define a class_id field to create a product');
            if(empty($payload['price_type_id'])) throw new \Exception('You have to define a price_type_id field to create a product');
        }
    }

    private static function checkUpdate($payload)
    {
        if(empty($payload['id']))        throw new \Exception('You have to define a id field to update a product');
    }


    /**
     * Set order like successful, when the payment is successful
     *
     * @param Order $order
     */
    public static function setOrderPaymentSuccessful(Order $order)
    {
        // change order status
        $paymentMethod      = $order->payment_methods->where('lang_id', user_lang())->first();
        $order->status_id   = $paymentMethod->order_status_successful_id;
        $order->save();
    }
}
