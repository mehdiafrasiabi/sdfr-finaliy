<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentLike extends Model
{
    use HasFactory;


    public $timestamps = false;


    protected $fillable = [

        'comment_id',

        'user_id',

    ];


    protected $casts = [

        'created_at' => 'datetime',

    ];


    public function comment(): BelongsTo

    {

        return $this->belongsTo(ProductComment::class, 'comment_id');

    }


    public function user(): BelongsTo

    {

        return $this->belongsTo(User::class);

    }
}
