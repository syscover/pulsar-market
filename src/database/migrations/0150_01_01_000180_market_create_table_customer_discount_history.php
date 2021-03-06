<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableCustomerDiscountHistory extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('market_customer_discount_history'))
		{
			Schema::create('market_customer_discount_history', function (Blueprint $table) {
				$table->engine = 'InnoDB';

                $table->increments('id');

                $table->integer('customer_id')->unsigned();
                $table->integer('order_id')->unsigned();

                // if order is canceled, you can deactivate discounts
                $table->boolean('applied')->default(true);

                // name of discount model:
                // CartPriceRule
                // CatalogPriceRule
                // CustomerPriceRule
                $table->string('rule_type');
                $table->integer('rule_id')->unsigned()->nullable();

                $table->json('names')->nullable(); // rule names value in different languages
                $table->json('descriptions')->nullable(); // rule descriptions value in different languages

                $table->boolean('has_coupon')->default(false);
                $table->string('coupon_code')->nullable();

                // see config/pulsar-market.php section Discount type on shopping cart
                // 1 - without discount
                // 2 - discount percentage subtotal
                // 3 - discount fixed amount subtotal
                // 4 - discount percentage total
                // 5 - discount fixed amount total
                $table->tinyInteger('discount_type_id')->unsigned()->nullable();

                // fixed amount to discount over shopping cart
                $table->decimal('discount_fixed_amount', 12, 4)->nullable();

                // percentage to discount over shopping cart
                $table->decimal('discount_percentage', 12, 4)->nullable();

                // limit amount to discount, if the discount is a percentage
                $table->decimal('maximum_discount_amount', 12, 4)->nullable();

                // check if apply discount to shipping amount
                $table->boolean('apply_shipping_amount')->default(false);

                // check if this discount has free shipping
                $table->boolean('free_shipping')->default(false);

                // total discount amount apply with this rule
                $table->decimal('discount_amount', 12, 4)->nullable();

                $table->json('data_lang')->nullable();

                // price rule object
                $table->json('price_rule');

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('customer_id', 'fk01_market_customer_discount_history')
                    ->references('id')
                    ->on('crm_customer')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
                $table->foreign('order_id', 'fk02_market_customer_discount_history')
                    ->references('id')
                    ->on('market_order')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');

                $table->index('rule_type', 'ix01_market_customer_discount_history');
                $table->index('rule_id', 'ix02_market_customer_discount_history');
                $table->index('coupon_code', 'ix03_market_customer_discount_history');
                $table->index('applied', 'ix04_market_customer_discount_history');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('market_customer_discount_history');
	}
}