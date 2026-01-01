<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Support\Facades\Storage;



class Story extends Model

{

    protected $guarded = [];



    protected $casts = [

        'expires_at' => 'datetime',

        'status' => 'boolean',

    ];



    /**

     * Get the user that created the story.

     */

    public function user(): BelongsTo

    {

        return $this->belongsTo(User::class);

    }



    /**

     * Get the likes for the story.

     */

    public function likes(): HasMany

    {

        return $this->hasMany(StoryLike::class);

    }



    /**

     * Get the number of likes.

     */

    public function getLikesCountAttribute(): int

    {

        return $this->likes()->count();

    }



    /**

     * Check if a user has liked this story.

     */

    public function isLikedBy(?User $user): bool

    {

        if (!$user) {

            return false;

        }

        return $this->likes()->where('user_id', $user->id)->exists();

    }



    /**

     * Scope a query to only include active stories.

     */

    public function scopeActive($query)

    {

        return $query->where('status', true);

    }



    /**

     * Scope a query to only include non-expired stories.

     */

    public function scopeNotExpired($query)

    {

        return $query->where(function ($q) {

            $q->whereNull('expires_at')

                ->orWhere('expires_at', '>', now());

        });

    }



    /**

     * Scope a query to only include published stories (active and not expired).

     */

    public function scopePublished($query)

    {

        return $query->active()->notExpired();

    }



    /**

     * Check if the story is expired.

     */

    public function getIsExpiredAttribute(): bool

    {

        return $this->expires_at && $this->expires_at->isPast();

    }



    /**

     * Get the thumbnail URL.

     */

    public function getThumbnailUrlAttribute(): string

    {

        return "/stories/thumbnail/{$this->thumbnail}";

    }



    /**

     * Get the story media URL.

     */

    public function getStoryUrlAttribute(): string

    {

        return "/stories/story/{$this->story}";

    }

}
