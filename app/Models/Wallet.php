<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class Wallet extends Model

{

    use HasFactory;


    protected $guarded = [];


    protected $casts = [

        'is_active' => 'boolean',

    ];


    public function user()

    {

        return $this->belongsTo(User::class);

    }


    public function transactions()

    {

        return $this->hasMany(WalletTransaction::class);

    }


    public function deposit($amount, $description = null, $type = 'deposit')

    {

        $this->balance += $amount;

        $this->save();


        return $this->transactions()->create([

            'user_id' => $this->user_id,

            'type' => $type,

            'amount' => $amount,

            'balance_after' => $this->balance,

            'description' => $description,

        ]);

    }


    public function withdraw($amount, $description = null, $type = 'withdraw')

    {

        if ($this->balance < $amount) {

            throw new \Exception('موجودی کیف پول کافی نیست');

        }


        $this->balance -= $amount;

        $this->save();


        return $this->transactions()->create([

            'user_id' => $this->user_id,

            'type' => $type,

            'amount' => $amount,

            'balance_after' => $this->balance,

            'description' => $description,

        ]);

    }


    public function canAfford($amount)

    {

        return $this->is_active && $this->balance >= $amount;

    }

}
