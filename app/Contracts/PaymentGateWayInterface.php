<?php

namespace App\Contracts;

interface PaymentGateWayInterface
{
    /**
     * Initiate a payment. Should return a redirect Response to the gateway.
     */
    public function request(int $amount, string $callbackUrl, ?string $description = null);

    /**
     * Verify the gateway callback request. Returns an array:
     *   [ 'success' => bool, 'ref_number' => ?string, 'authority' => ?string, 'message' => ?string ]
     */
    public function verify($request): array;

    /**
     * Gateway short name (zarinpal | zibal).
     */
    public function name(): string;
}
