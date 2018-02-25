<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Syscover\Core\GraphQL\ScalarTypes\ObjectType;

class CartPriceRuleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'CartPriceRule',
        'description' => 'CartPriceRule'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of cart price rule'
            ],
            'names' => [
                'type' => app(ObjectType::class),
                'description' => 'JSON string that contain multi language name'
            ],
            'descriptions' => [
                'type' => app(ObjectType::class),
                'description' => 'JSON string that contain multi language description'
            ],
            'active' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Active this cart price rule'
            ],
            'group_ids' => [
                'type' => app(ObjectType::class),
                'description' => 'Customer groups that can take this cart price rule'
            ],
            'customer_ids' => [
                'type' => app(ObjectType::class),
                'description' => 'Customer that can take this cart price rule'
            ],
            'combinable' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Define if this rule can to be combined with other rule'
            ],
            'priority' => [
                'type' => Type::int(),
                'description' => 'Short to apply discounts'
            ],
            'has_coupon' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Define if this rule has coupon'
            ],
            'coupon_code' => [
                'type' => Type::string(),
                'description' => 'Coupon code'
            ],
            'customer_uses' => [
                'type' => Type::int(),
                'description' => 'Times a coupon can be used per user'
            ],
            'coupon_uses' => [
                'type' => Type::int(),
                'description' => 'Times a coupon can be used'
            ],
            'total_uses' => [
                'type' => Type::int(),
                'description' => 'Total times the discount has been used'
            ],
            'enable_from' => [
                'type' => Type::string(),
                'description' => 'Date from this coupon is enabled'
            ],
            'enable_to' => [
                'type' => Type::string(),
                'description' => 'Date to this coupon is enabled'
            ],
            'condition_rules' => [
                'type' => app(ObjectType::class),
                'description' => 'Check if this rules are valid to apply this discount'
            ],
            'discount_type_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Discount type'
            ],
            'discount_fixed_amount' => [
                'type' => Type::float(),
                'description' => 'Fixed amount to discount over shopping cart'
            ],
            'discount_percentage' => [
                'type' => Type::float(),
                'description' => 'Discount percentage on an amount'
            ],
            'maximum_discount_amount' => [
                'type' => Type::float(),
                'description' => 'Maximum amount to discount'
            ],
            'apply_shipping_amount' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Check if the discount is applied to the transport price'
            ],
            'free_shipping' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'This discount has free shipping'
            ],
            'product_rules' => [
                'type' => app(ObjectType::class),
                'description' => 'Rules that will determinate that products will be applied this discounts'
            ],
            'data_lang' => [
                'type' => app(ObjectType::class),
                'description' => 'JSON string that contain information about object translations'
            ]
        ];
    }
}