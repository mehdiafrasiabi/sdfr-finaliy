<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
