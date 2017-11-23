<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class PaymentMethodType extends GraphQLType {

    protected $attributes = [
        'name'          => 'PaymentMethod',
        'description'   => 'Payment method of order'
    ];

    public function fields()
    {
        return [
            'ix' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The index of order status'
            ],
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of payment method'
            ],
            'lang_id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Lang of order status'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of order status'
            ],
            'order_status_successful_id' => [
                'type' => Type::int(),
                'description' => 'The order status when payment is successful'
            ],
            'minimum_price' => [
                'type' => Type::float(),
                'description' => 'The minimum price for to do payment for this method'
            ],
            'maximum_price' => [
                'type' => Type::float(),
                'description' => 'The maximum price for to do payment for this method'
            ],
            'instructions' => [
                'type' => Type::string(),
                'description' => 'Instructions to show at customer'
            ],
            'sort' => [
                'type' => Type::int(),
                'description' => 'Sort of payment method'
            ],
            'active' => [
                'type' => Type::boolean(),
                'description' => 'Set if payment method is active'
            ],
            'data_lang' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'JSON string that contain information about object translations'
            ]
        ];
    }
}