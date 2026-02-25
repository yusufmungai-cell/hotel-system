<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class MpesaService
{
    private function accessToken()
    {
        $response = Http::withBasicAuth(
            config('mpesa.consumer_key'),
            config('mpesa.consumer_secret')
        )->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');

        return $response['access_token'];
    }

    public function stkPush($phone,$amount,$orderId)
    {
        $timestamp = Carbon::now()->format('YmdHis');

        $password = base64_encode(
            config('mpesa.shortcode') .
            config('mpesa.passkey') .
            $timestamp
        );

        return Http::withToken($this->accessToken())
            ->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest',[
                "BusinessShortCode"=>config('mpesa.shortcode'),
                "Password"=>$password,
                "Timestamp"=>$timestamp,
                "TransactionType"=>"CustomerPayBillOnline",
                "Amount"=>$amount,
                "PartyA"=>$phone,
                "PartyB"=>config('mpesa.shortcode'),
                "PhoneNumber"=>$phone,
                "CallBackURL"=>config('mpesa.callback_url'),
                "AccountReference"=>"ORDER".$orderId,
                "TransactionDesc"=>"Restaurant Payment"
            ])->json();
    }
}