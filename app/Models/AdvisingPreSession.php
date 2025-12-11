<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\HasOne;



class AdvisingPreSession extends Model

{

    protected $guarded = [];



    const STATUS_PENDING = 'pending';

    const STATUS_COMPLETED = 'completed';



    public function advisingSession(): BelongsTo

    {

        return $this->belongsTo(AdvisingSession::class);

    }



    public function student(): BelongsTo

    {

        return $this->belongsTo(Student::class);

    }



    // روابط با جداول مراحل

    public function exams(): HasMany

    {

        return $this->hasMany(AdvisingPreSessionExam::class, 'pre_session_id');

    }



    public function assignments(): HasMany

    {

        return $this->hasMany(AdvisingPreSessionAssignment::class, 'pre_session_id');

    }



    public function qas(): HasMany

    {

        return $this->hasMany(AdvisingPreSessionQa::class, 'pre_session_id');

    }



    public function freeTimes(): HasMany

    {

        return $this->hasMany(AdvisingPreSessionFreeTime::class, 'pre_session_id');

    }



    public function miscellaneous(): HasOne

    {

        return $this->hasOne(AdvisingPreSessionMisc::class, 'pre_session_id');

    }



    // بررسی امکان ویرایش

    public function canEdit(): bool

    {

        return $this->advisingSession->canFillPreSession();

    }



    // نمایش وضعیت فارسی

    public function getStatusLabelAttribute(): string

    {

        return match($this->status) {

            self::STATUS_PENDING => 'در انتظار تکمیل',

            self::STATUS_COMPLETED => 'تکمیل شده',

            default => 'نامشخص',

        };

    }

}
