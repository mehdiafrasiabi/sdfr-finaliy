<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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
