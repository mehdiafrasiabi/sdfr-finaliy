<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function submit($formData, $countryId)
    {
        Country::query()->updateOrCreate(
            [
                'id' => $countryId,
            ],
            [
                'name' => $formData['name'],
            ]
        );
    }

}
