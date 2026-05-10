<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController
{
    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'order_amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'in:bank_transfer,qris,cash'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'order_items' => ['nullable', 'array'],
        ]);

        $timestamp = now()->format('YmdHis');
        $paymentId = 'pay-' . $timestamp . '-' . random_int(100, 999);

        $response = [
            'payment_id' => $paymentId,
            'reference' => strtoupper($payload['payment_method']) . '-' . $timestamp,
            'payment_method' => $payload['payment_method'],
            'payment_status' => $payload['payment_method'] === 'cash' ? 'paid' : 'pending',
            'amount' => (float) $payload['order_amount'],
            'customer_name' => $payload['customer_name'] ?? '',
            'customer_phone' => $payload['customer_phone'] ?? '',
        ];

        if ($payload['payment_method'] === 'bank_transfer') {
            $response['bank_name'] = 'Bank Mandiri';
            $response['va_number'] = '70012' . random_int(100000, 999999);
        }

        if ($payload['payment_method'] === 'qris') {
            $response['qr_string'] = 'QRIS-DEMO-' . $paymentId;
        }

        return response()->json($response, 201);
    }

    public function status(string $id): JsonResponse
    {
        return response()->json([
            'payment_id' => $id,
            'payment_status' => 'pending',
            'status' => 'pending',
            'message' => 'Status payment masih mock untuk tahap integrasi Midtrans.',
            'updated_at' => now()->toIso8601String(),
        ]);
    }
}
