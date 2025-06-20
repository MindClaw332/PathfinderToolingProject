<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use Illuminate\Routing\Controller;


class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        return view('donation.donate');
    }

    public function showSuccess(){
        return view('donation.success');
    }
    public function showFailure(){
        return view('donation.failure');
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $stripe = new StripeClient(env('STRIPE_SECRET'));

        // Create Checkout Session
        $checkout = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card', 'bancontact'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => "master's toolkit donation",
                    ],
                    'unit_amount' => $request->amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('donate.success'),
            'cancel_url' => route('donate.failure'),
        ]);

        return redirect()->away($checkout->url);
    }
}
