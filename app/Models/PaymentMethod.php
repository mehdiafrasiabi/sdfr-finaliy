<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    //
    use HasFactory;

    protected $guarded = [];
    public function submit($formData, $paymentId)
    {
        PaymentMethod::query()->updateOrCreate(
            [
                'id' => $paymentId,
            ],
            [
                'name' => $formData['name'],
                'merchant_id' => $formData['merchantCode'],
            ]
        );
    }
}
