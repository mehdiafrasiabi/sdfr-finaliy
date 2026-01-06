<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class GiftCode extends Model

{

    use HasFactory;


    protected $guarded = [];


    protected $casts = [

        'is_active' => 'boolean',

        'expires_at' => 'date',

    ];


    public function user()

    {

        return $this->belongsTo(User::class);

    }


    public function usages()

    {

        return $this->hasMany(GiftCodeUsage::class);

    }


    public function isValid()

    {

        return $this->is_active

            && $this->expires_at->isFuture()

            && ($this->type === 'for_one' || $this->usage_count < $this->usage_limit);

    }


    public function canBeUsedBy(User $user)

    {

        if (!$this->isValid()) {

            return false;

        }


        if ($this->type === 'for_one' && $this->user_id !== $user->id) {

            return false;

        }


        return !$this->usages()->where('user_id', $user->id)->exists();

    }


    public function use(User $user)

    {

        if (!$this->canBeUsedBy($user)) {

            throw new \Exception('این کد هدیه قابل استفاده نیست');

        }


        $this->usages()->create([

            'user_id' => $user->id,

            'amount' => $this->amount,

        ]);


        $this->increment('usage_count');


        return $this->amount;

    }


    public function getTypeTextAttribute()

    {

        return match ($this->type) {

            'for_all' => 'برای همه',

            'for_one' => 'برای یک نفر',

            default => $this->type,

        };

    }


    public function getStatusTextAttribute()

    {

        if (!$this->is_active) {

            return 'غیرفعال';

        }

        if ($this->expires_at->isPast()) {

            return 'منقضی شده';

        }

        if ($this->type === 'for_all' && $this->usage_count >= $this->usage_limit) {

            return 'اتمام ظرفیت';

        }

        return 'فعال';

    }


    public function getStatusColorAttribute()

    {

        if (!$this->is_active || $this->expires_at->isPast()) {

            return 'danger';

        }

        if ($this->type === 'for_all' && $this->usage_count >= $this->usage_limit) {

            return 'warning';

        }

        return 'success';

    }

}
