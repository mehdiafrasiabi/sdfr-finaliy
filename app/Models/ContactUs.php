<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactUs extends Model
{

    use HasFactory;

    protected $guarded = [];
    public function submit($formData)
    {
        ContactUs::query()->create(
            [
            'name' => $formData['name'],
            'mobile' => $formData['mobile'],
            'text' => $formData['text'],
            ]
        );
    }
}
