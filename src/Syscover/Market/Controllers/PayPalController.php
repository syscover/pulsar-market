<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Syscover\Market\Events\PaymentResponseError;
use Syscover\Market\Events\PaymentResponseSuccessful;
use Syscover\Market\Events\PaypalResponseError;
use Syscover\Market\Events\PaypalResponseSuccessful;
use Syscover\Market\Services\PayPalPaymentService;

class PayPalController extends BaseController
{
    public function successful()
    {
        $order = PayPalPaymentService::successful();

        $paymentResponses   = event(new PaymentResponseSuccessful($order));
        $paypalResponses    = event(new PaypalResponseSuccessful($order));
        $responses          = array_merge($paymentResponses, $paypalResponses);

        foreach ($responses as $response)
        {
            if(is_string($response) && Route::has($response))
            {
                return redirect()
                    ->route($response, [
                        'id'        => $order->id,
                        'referer'   => 'paypal'
                    ])
                    ->with([
                        'status' => 'successful'
                    ]);
            }
        }

        // Check is response is a redirect
        foreach ($responses as $response)
        {
            if (get_class($response) === 'Illuminate\Http\RedirectResponse')
            {
                return $response;
            }
        }

        return null;
    }

    public function error($id)
    {
        $order = PayPalPaymentService::error($id);

        $paymentResponses   = event(new PaymentResponseError($order));
        $paypalResponses    = event(new PaypalResponseError($order));
        $responses          = array_merge($paymentResponses, $paypalResponses);

        foreach ($responses as $response)
        {
            if(is_string($response) && Route::has($response))
            {
                return redirect()
                    ->route($response, ['id' => $order->id])
                    ->with([
                        'status' => 'error'
                    ]);
            }
        }

        return null;
    }
}