<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MpesaService;
use App\Models\MpesaTransaction; // model to log

class MpesaController extends Controller
{
    protected $mpesa;

    public function __construct(MpesaService $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    // initiate STK push
    public function stkPush(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'amount' => 'required|numeric|min:1',
            'order_id' => 'nullable|integer',
        ]);

        $res = $this->mpesa->stkPush($request->phone, $request->amount, 'Order:' . ($request->order_id ?? Str::random(6)), 'Payment');

        // log initial request
        MpesaTransaction::create([
            'external_id' => $res['CheckoutRequestID'] ?? null,
            'type' => 'stk_push_init',
            'payload' => $res,
        ]);

        return response()->json($res);
    }

    // callback endpoint from Safaricom (public)
    public function callback(Request $request)
    {
        // Safaricom will POST JSON with nested structure
        $payload = $request->getContent();

        // store raw
        $data = json_decode($payload, true) ?: $request->all();

        $checkoutRequestId = $data['Body']['stkCallback']['CheckoutRequestID'] ?? null;
        $resultCode = $data['Body']['stkCallback']['ResultCode'] ?? null;

        // Find the transaction
        $transaction = MpesaTransaction::where('checkout_request_id', $checkoutRequestId)->first();

        if ($transaction) {
            if ($resultCode == 0) {
                // Successful payment
                $transaction->update([
                    'status' => 'completed',
                    'mpesa_receipt_number' => $data['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'] ?? null,
                    'transaction_date' => $data['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'] ?? null,
                    'phone_number' => $data['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'] ?? null,
                    'amount' => $data['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'] ?? null,
                ]);

                // Update order status
                $orderId = str_replace('Order:', '', $transaction->payload['AccountReference'] ?? '');
                if ($orderId) {
                    $order = \App\Models\Order::find($orderId);
                    if ($order) {
                        $order->update(['status' => 'paid']);
                    }
                }
            } else {
                // Failed payment
                $transaction->update([
                    'status' => 'failed',
                    'result_desc' => $data['Body']['stkCallback']['ResultDesc'] ?? 'Unknown error',
                ]);
            }
        }

        MpesaTransaction::create([
            'external_id' => $checkoutRequestId,
            'checkout_request_id' => $checkoutRequestId,
            'type' => 'stk_push_callback',
            'payload' => $data,
            'status' => $resultCode == 0 ? 'completed' : 'failed',
        ]);

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
