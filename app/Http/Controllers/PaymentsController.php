<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;

class PaymentsController extends Controller
{
    
    public function index()
    {
        return view('payment');
    }

    public function charge(Request $request)
    {
        // return $request;
        // $stripe = new \Stripe\StripeClient(
        //     env('STRIPE_SECRET')
        //   );


        $payment = new Payment();
        $payment->save();

        Stripe::setApiKey(env('STRIPE_SECRET'));


        $checkout_session = \Stripe\Checkout\Session::create([

            'payment_method_types' => ['card'],

            'line_items' => [[

                'price_data' => [

                    'currency' => 'usd',

                    'unit_amount' => 1000,

                    'product_data' => [

                        'name' => 'T-shirt',

                        'images' => ["https://masjidmissioncenterusa.org/abasas/images/logo/MMC_Title_logo-removebg-preview.png"],

                    ],

                ],

                'quantity' => 1,

            ]],

            'mode' => 'payment',

            'success_url' => 'http://localhost:8000/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:8000/failed',

        ]);


        $payment->payment_id = $checkout_session->id;
        $payment->save();

        // $session = \Stripe\Checkout\Session::create([
        //     'payment_method_types' => ['card'],
        //     'line_items' => [[
        //       'price_data' => [
        //         'product' => 'prod_LPtb4g2lGeK3nR',
        //         'unit_amount' => 1500,
        //         'currency' => 'usd',
        //       ],
        //       'quantity' => 1,
        //     ]],
        //     'mode' => 'payment',
        //     'success_url' => 'http://localhost:8000/success',
        //     'cancel_url' => 'http://localhost:8000/failed',
        //   ]);
        // dd($checkout_session['payment_intent']);
        return redirect($checkout_session->url);
        dd($checkout_session['url']);
        return ['id' => $checkout_session->id];
        
    }
    public function success(Request $request){
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
          );
          $response =  $stripe->checkout->sessions->retrieve(
            $request->session_id,
            []
          );
          if($response['payment_status'] == 'paid'){
            $payment = Payment::where('payment_id', $request->session_id)->first();
            $payment->payment_status = 'paid';
            $payment->save();
            return 'success';

          }
          else{
            return 'failed';
          }
    }

    public function failed(Request $request){
        return $request;
    }

    
}
