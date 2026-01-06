<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class GiftCodeUsage extends Model

{

    use HasFactory;


    protected $guarded = [];


    public function giftCode()

    {

        return $this->belongsTo(GiftCode::class);

    }


    public function user()

    {

        return $this->belongsTo(User::class);

    }

}
