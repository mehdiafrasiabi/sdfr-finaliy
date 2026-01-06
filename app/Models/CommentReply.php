<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReply extends Model
{
    use HasFactory;


    protected $fillable = [

        'comment_id',

        'admin_id',

        'reply',

    ];


    protected $casts = [

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

    ];


    public function comment(): BelongsTo

    {

        return $this->belongsTo(ProductComment::class, 'comment_id');

    }


    public function admin(): BelongsTo

    {

        return $this->belongsTo(Admin::class);

    }
}
