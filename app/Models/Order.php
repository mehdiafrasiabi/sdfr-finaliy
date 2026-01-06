<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,softDeletes;
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class,'id','order_id');
    }
    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class,'id','user_id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected $casts = [

        'paid_with_wallet' => 'boolean',

    ];



    public function getPaymentMethodTextAttribute()

    {

        if ($this->paid_with_wallet) {

            return 'کیف پول';

        }

        return $this->paymentMethod?->name ?? 'درگاه پرداخت';

    }



    public function getStatusPaymentColorAttribute()

    {

        return match ($this->payment?->status) {

            'completed' => 'success',

            'pending' => 'warning',

            'cancelled' => 'danger',

            default => 'secondary',

        };

    }

}
