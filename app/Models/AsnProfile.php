<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AsnProfile
 *
 * @property int $id
 * @property int $user_id
 * @property string $full_name
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $birth_place
 * @property string $gender
 * @property string|null $address
 * @property string|null $position
 * @property string|null $rank
 * @property string|null $grade
 * @property \Illuminate\Support\Carbon|null $appointment_date
 * @property string|null $education_level
 * @property string|null $major
 * @property string|null $photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $user
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereAppointmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereBirthPlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereEducationLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereMajor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsnProfile whereUserId($value)
 * @method static \Database\Factories\AsnProfileFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class AsnProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'birth_place',
        'gender',
        'address',
        'position',
        'rank',
        'grade',
        'appointment_date',
        'education_level',
        'major',
        'photo_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'appointment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}