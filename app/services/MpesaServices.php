<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MpesaService
{
    protected $config;
    protected $env;
    protected $endpoints;

    public function __construct()
    {
        $this->config = config('mpesa');
        $this->env = $this->config['env'] ?? 'sandbox';
        $this->endpoints = $this->config['endpoints'][$this->env];
    }

    protected function getToken()
    {
        $cacheKey = $this->config['bearer_cache_key'] ?? 'mpesa_bearer_token';
        $tokenData = Cache::get($cacheKey);

        if ($tokenData && isset($tokenData['token']) && Carbon::now()->lt($tokenData['expires_at'])) {
            return $tokenData['token'];
        }

        $key = $this->config['consumer_key'];
        $secret = $this->config['consumer_secret'];
        $url = $this->endpoints['oauth'];

        $response = Http::withBasicAuth($key, $secret)->get($url);

        if (!$response->ok()) {
            throw new \Exception('Unable to fetch M-Pesa OAuth token: ' . $response->body());
        }

        $json = $response->json();
        $token = $json['access_token'] ?? null;
        $expiresIn = $json['expires_in'] ?? 3600;

        Cache::put($cacheKey, [
            'token' => $token,
            'expires_at' => Carbon::now()->addSeconds($expiresIn - 30)
        ], $expiresIn - 30);

        return $token;
    }

    protected function endpoint($key)
    {
        return $this->endpoints[$key];
    }

    public function stkPush($phone, $amount, $accountReference = 'Order', $transactionDesc = 'Payment')
    {
        $url = $this->endpoint('stk_push');
        $token = $this->getToken();

        $timestamp = now()->format('YmdHis'); // e.g. 20251120123045
        $shortcode = $this->config['shortcode'];
        $passkey = $this->config['passkey'];
        $password = base64_encode($shortcode . $passkey . $timestamp);

        $payload = [
            'BusinessShortCode' => $shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int) $amount,
            'PartyA' => $this->formatPhone($phone), // customer phone
            'PartyB' => $shortcode,
            'PhoneNumber' => $this->formatPhone($phone),
            'CallBackURL' => $this->config['callback_url'],
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc,
        ];

        $resp = Http::withToken($token)->post($url, $payload);

        return $resp->throw()->json();
    }

    public function simulateC2B($shortcode, $msisdn, $amount, $billRef = 'TestPayment')
    {
        $url = $this->endpoint('c2b_simulate');
        $token = $this->getToken();

        $payload = [
            'ShortCode' => $shortcode,
            'CommandID' => 'CustomerPayBillOnline',
            'Msisdn' => $this->formatPhone($msisdn),
            'Amount' => (int) $amount,
            'BillRefNumber' => $billRef,
        ];

        $resp = Http::withToken($token)->post($url, $payload);
        return $resp->throw()->json();
    }

    public function registerC2B($shortcode, $confirmationUrl, $validationUrl)
    {
        $url = $this->endpoint('c2b_register');
        $token = $this->getToken();

        $payload = [
            'ShortCode' => (string)$shortcode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl,
        ];

        $resp = Http::withToken($token)->post($url, $payload);
        return $resp->throw()->json();
    }

    protected function formatPhone($phone)
    {
        // ensure format 2547xxxxxxx (Kenya)
        $p = preg_replace('/\D+/', '', $phone);
        if (strlen($p) === 10 && substr($p,0,1) === '0') {
            return '254' . substr($p,1);
        }
        if (strlen($p) === 9 && substr($p,0,1) === '7') {
            return '254' . $p;
        }
        if (strlen($p) === 12 && substr($p,0,3) === '254') {
            return $p;
        }
        return $p; // best effort
    }

    // Add other endpoints like b2c(), transactionStatus(), etc. as needed.
}
