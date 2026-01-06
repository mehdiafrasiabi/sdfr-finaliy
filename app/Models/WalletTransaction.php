<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class WalletTransaction extends Model

{

    use HasFactory;


    protected $guarded = [];


    public function wallet()

    {

        return $this->belongsTo(Wallet::class);

    }


    public function user()

    {

        return $this->belongsTo(User::class);

    }


    public function getTypeTextAttribute()

    {

        return match ($this->type) {

            'deposit' => 'شارژ کیف پول',

            'withdraw' => 'برداشت',

            'gift' => 'کد هدیه',

            'purchase' => 'خرید',

            'refund' => 'بازگشت وجه',

            default => $this->type,

        };

    }


    public function getTypeColorAttribute()

    {

        return match ($this->type) {

            'deposit', 'gift', 'refund' => 'success',

            'withdraw', 'purchase' => 'danger',

            default => 'secondary',

        };

    }

}
