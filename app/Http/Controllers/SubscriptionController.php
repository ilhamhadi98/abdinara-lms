<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        // midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        $packages = SubscriptionPackage::where('is_active', true)->get();
        return view('subscription.index', compact('packages'));
    }

    public function checkout(Request $request, SubscriptionPackage $package)
    {
        $user = Auth::user();
        $orderId = 'TRX-' . time() . '-' . $user->id . '-' . $package->id;

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'subscription_package_id' => $package->id,
            'order_id' => $orderId,
            'gross_amount' => $package->price,
            'status' => 'pending',
        ]);

        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $package->price,
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'email' => $user->email,
            ),
            'item_details' => array(
                [
                    'id' => $package->id,
                    'price' => $package->price,
                    'quantity' => 1,
                    'name' => $package->name,
                ]
            ),
        );

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);
            return redirect()->route('subscription.pay', $transaction->id);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghubungi payment gateway.');
        }
    }

    public function pay(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return redirect()->route('subscription.index')->with('status', 'Transaksi sudah diproses.');
        }

        return view('subscription.pay', compact('transaction'));
    }

    public function history()
    {
        $transactions = Transaction::where('user_id', Auth::id())->with('package')->latest()->get();

        // Sync pending transactions from Midtrans API (Useful for Localhost testing without Webhooks)
        foreach ($transactions as $trx) {
            if ($trx->status === 'pending') {
                try {
                    $statusResponse = \Midtrans\Transaction::status($trx->order_id);
                    if (isset($statusResponse->transaction_status)) {
                        $this->updateTransactionStatus(
                            $trx, 
                            $statusResponse->transaction_status, 
                            $statusResponse->payment_type ?? null, 
                            $statusResponse->fraud_status ?? null
                        );
                    }
                } catch (\Exception $e) {
                    // Ignore if order is not found in midtrans
                }
            }
        }

        // Refetch after possible status updates
        $transactions = Transaction::where('user_id', Auth::id())->with('package')->latest()->get();
        return view('subscription.history', compact('transactions'));
    }

    public function invoice(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        return view('subscription.invoice', compact('transaction'));
    }

    public function notification(Request $request)
    {
        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $trx = Transaction::where('order_id', $order_id)->with('package', 'user')->first();

        if (!$trx) {
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
        }

        $this->updateTransactionStatus($trx, $transaction, $type, $fraud);

        return response()->json(['status' => 'ok']);
    }

    private function updateTransactionStatus(Transaction $trx, $transaction, $type, $fraud)
    {
        // Pre-empt duplicate activation
        if ($trx->status === 'success') {
            return;
        }

        if ($transaction == 'capture' || $transaction == 'settlement') {
            if ($type == 'credit_card' && $fraud == 'challenge') {
                $trx->update(['status' => 'challenge']);
            } else {
                $trx->update(['status' => 'success', 'payment_type' => $type]);
                $this->activateSubscription($trx);
            }
        } else if ($transaction == 'cancel' || $transaction == 'deny' || $transaction == 'expire') {
            $trx->update(['status' => 'failed']);
        } else if ($transaction == 'pending') {
            $trx->update(['status' => 'pending']);
        }
    }

    private function activateSubscription(Transaction $trx)
    {
        $user = $trx->user;
        $package = $trx->package;

        $currentExpire = $user->subscription_expires_at;

        if ($currentExpire && $currentExpire->isFuture()) {
            // Add to existing
            $newExpire = $currentExpire->addDays($package->duration_days);
        } else {
            // Start from today
            $newExpire = now()->addDays($package->duration_days);
        }

        $user->update(['subscription_expires_at' => $newExpire]);
    }
}
