<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Syscover\Market\Events\PaypalResponseSuccessful;
use Syscover\Market\Services\PayPalService;

class PayPalController extends BaseController
{
    public function successful(Request $request)
    {
        $order = PayPalService::successful();

        $responses = event(new PaypalResponseSuccessful($order));

        $countries = Country::builder()
            ->where('lang_id', user_lang())
            ->get();

        foreach ($responses as $response)
        {
            if(View::exists($response)) return view($response, [
                'countries' => $countries,
                'order'     => $order,
                'status'    => 'successful'
            ]);
        }

        return null;
    }

    public function error()
    {

    }
}