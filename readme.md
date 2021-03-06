# Market for Laravel

[![Total Downloads](https://poser.pugx.org/syscover/market/downloads)](https://packagist.org/packages/syscover/market)

## Installation

Before install sycover/pulsar-market, you need install syscover/pulsar-core, syscover/pulsar-admin and syscover/pulsar-crm

**1 - After install Laravel framework, execute on console:**
```
composer require syscover/pulsar-market
```

Register service provider, on file config/app.php add to providers array
```
Syscover\Market\MarketServiceProvider::class,
```

**2 - Execute publish command**
```
php artisan vendor:publish --provider="Syscover\Market\MarketServiceProvider"
```
and
```
composer dump-autoload
```

**3 - And execute migrations and seed database**
```
php artisan migrate
php artisan db:seed --class="MarketTableSeeder"
```

**4 - Execute command to load all updates**
```
php artisan migrate --path=vendor/syscover/pulsar-market/src/database/migrations/updates
```

**5 - Register TaxRule middleware on file app/Http/Kernel.php add to $middlewareGroups array in web group**
```

'web' => [
    ...
    \Syscover\Market\Middleware\TaxRule::class
],
```

**6 - To run unit testing**
```
./vendor/bin/phpunit
```


## General configuration environment values
We indicate configuration variables available, to change them what you should do from the file environment variables .env

### Order id prefix [default value empty]
You can set a prefix for all your orders, for example, if you can set on all you orders the prefix ORDER, set this value on you .env file
```
MARKET_ORDER_ID_PREFIX=ORDER
```

### Tax default country [default value ES]
To set default country to calculate tax, you can use this parameter, for example to change to US, set this value on you .env file

```
MARKET_DEFAULT_COUNTRY_TAX=US
```

### Default customer class tax [default value 1]
Set default ID customer class value for calculate tax amount of products

```
MARKET_DEFAULT_CUSTOMER_CLASS_TAX=1
```

### Configuración de reglas de tasas
Cuando configuramos una regla de tasa podemos acotar la regla indicando país y áreas territoriales donde queremos aplicar dicha regla. 
También disponemos del campo ZIP donde podrmos usar un patrón. Si queremos aplicar esa tasa entre los ZIPs 28001 y 28099 podemos indicar el valor 280** en el campo ZIP.

A la hora de crear las reglas de impuestos es importante tener en cuenta los siguientes campos.

**Prioridad**
En este apartado aplicamos un número para indicar la prioridad de este impuesto, cuando se aplica más de un impuesto. Si se aplican dos reglas fiscales con la misma prioridad, los impuestos se suman. Si se aplican dos impuestos con diferentes configuraciones de prioridad, entonces los impuestos se capitalizan en función del orden de prioridad. Cuando los impuestos están compuestos, el impuesto de la primera prioridad se calcula sobre el importe subtotal, y luego el impuesto de la segunda prioridad se calcula sobre el subtotal más el monto del primer impuesto prioritario.

**Orden**
En este apartado aplicamos un número para indicar el orden en que se muestran las reglas fiscales en la página Administrar reglas impositivas.

### Set product price tax [default value 1]
Defines the types of prices that are introduced in products, this option is consulted when you create or update a product
You have this values:
* Value: 1 *Excluding tax*
* Value: 2 *Including tax*

```
MARKET_PRODUCT_TAX_PRICES=1
```

### Set shipping price tax [default value 1]
Defines the types of prices that are introduced in shipping prices, this option is consulted when you create or update a shipping price
* Value: 1 *Excluding tax*
* Value: 2 *Including tax*

```
MARKET_TAX_SHIPPING_PRICES=1
```

### Set product display price tax [default value 1]
Defines how you want display product prices
You have this values:
* Value: 1 *Excluding tax*
* Value: 2 *Including tax*

```
MARKET_PRODUCT_TAX_DISPLAY_PRICES=1
```

### Set shipping display price tax [default value 1]
Defines how you want display shipping prices
* Value: 1 *Excluding tax*
* Value: 2 *Including tax*

```
MARKET_TAX_SHIPPING_DISPLAY_PRICES=1
```

## PayPal environment values

### Set PayPal mode
* Value: sandbox *for testing or development environments* 
* Value: live *for production environments* 
```
MARKET_PAYPAL_MODE=sandbox
```

### PayPal sandbox values
```
MARKET_PAYPAL_SANDBOX_WEB_PROFILE=XX-XXXX-XXXX-XXXX-XXXX
MARKET_PAYPAL_SANDBOX_CLIENT_ID=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MARKET_PAYPAL_SANDBOX_SECRET=xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx
```

### PayPal live values
```
MARKET_PAYPAL_LIVE_WEB_PROFILE=XX-XXXX-XXXX-XXXX-XXXX
MARKET_PAYPAL_LIVE_CLIENT_ID=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MARKET_PAYPAL_LIVE_SECRET_KEY=xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx
```

### PayPal translations
If you want set shipping description in PayPal you mus to create the next key in common.php language file
```
paypal_shipping_description
```

If you want set item list description in PayPal you mus to create the next key in common.php language file
```
paypal_item_list_description
```

## Redsys environment values

### Set Redsys mode
* Value: test *for testing or development environments* 
* Value: live *for production environments* 
```
MARKET_REDSYS_MODE=test
```

### Redsys test values
```
MARKET_REDSYS_TEST_MERCHANT_NAME="MERCHANT NAME (TEST)"
MARKET_REDSYS_TEST_MERCHANT_CODE=xxxxxxxxx
MARKET_REDSYS_TEST_KEY=xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx
```

### Redsys live values
```
MARKET_REDSYS_LIVE_MERCHANT_NAME="MERCHANT NAME"
MARKET_REDSYS_LIVE_MERCHANT_CODE=xxxxxxxxx
MARKET_REDSYS_LIVE_KEY=xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx
```

## Events

You can register this events in app/Providers/EventServiceProvider.php to manage various actions. 

```
protected $listen = [
    ...
    'Syscover\...\Events\YourEvent' => [
        'App\Listeners\YourListener'
    ],
    ...
];
```
##### Event when response from any payment method is successful
```
Syscover\Market\Events\PaymentResponseSuccessful
```
##### Event when response from any payment method is error
```
Syscover\Market\Events\PaymentResponseError
```
##### Event when response is call from REDSYS server
```
Syscover\Market\Events\RedsysAsyncResponse
```
##### Event when response from REDSYS payment is successful
```
Syscover\Market\Events\RedsysResponseSuccessful
```
##### Event when response from REDSYS payment is error
```
Syscover\Market\Events\RedsysResponseError
```
##### Event when response from PayPal payment is successful
```
Syscover\Market\Events\PaypalResponseSuccessful
```
##### Event when response from PayPal payment is error
```
Syscover\Market\Events\PaypalResponseError
```
