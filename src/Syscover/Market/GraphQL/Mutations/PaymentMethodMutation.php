<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\PaymentMethod;

class PaymentMethodMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketPaymentMethod');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketPaymentMethodInput'))
            ],
        ];
    }
}

class AddPaymentMethodMutation extends PaymentMethodMutation
{
    protected $attributes = [
        'name'          => 'addPaymentMethod',
        'description'   => 'Add new order status'
    ];

    public function resolve($root, $args)
    {
        if(! isset($args['object']['id']))
        {
            $id = PaymentMethod::max('id');
            $id++;

            $args['object']['id'] = $id;
        }

        $args['object']['data_lang'] = PaymentMethod::addLangDataRecord($args['object']['lang_id'], $args['object']['id']);

        return PaymentMethod::create($args['object']);
    }
}

class UpdatePaymentMethodMutation extends PaymentMethodMutation
{
    protected $attributes = [
        'name' => 'updatePaymentMethod',
        'description' => 'Update order status'
    ];

    public function resolve($root, $args)
    {
        PaymentMethod::where('id', $args['object']['id'])
            ->where('lang_id', $args['object']['lang_id'])
            ->update($args['object']);

        return PaymentMethod::where('id', $args['object']['id'])
            ->where('lang_id', $args['object']['lang_id'])
            ->first();
    }
}

class DeletePaymentMethodMutation extends PaymentMethodMutation
{
    protected $attributes = [
        'name' => 'deletePaymentMethod',
        'description' => 'Delete order status'
    ];

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::string())
            ],
            'lang' => [
                'name' => 'lang',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $object = SQLService::destroyRecord($args['id'], PaymentMethod::class, $args['lang']);

        return $object;
    }
}