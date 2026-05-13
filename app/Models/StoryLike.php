<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class StoryLike extends Model

{

    use HasFactory;

    protected $guarded = [];


    /**
     * Get the story that owns the like.
     */

    public function story(): BelongsTo

    {

        return $this->belongsTo(Story::class);

    }


    /**
     * Get the user that owns the like.
     */

    public function user(): BelongsTo

    {

        return $this->belongsTo(User::class);

    }

}
