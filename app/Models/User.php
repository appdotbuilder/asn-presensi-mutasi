<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $role
 * @property string|null $nip
 * @property string|null $phone
 * @property int|null $opd_id
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Opd|null $opd
 * @property-read \App\Models\AsnProfile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendances
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mutation> $mutations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mutation> $opdReviewedMutations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mutation> $bkpsdmReviewedMutations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $approvedAttendances
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOpdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User asn()
 * @method static \Illuminate\Database\Eloquent\Builder|User operatorOpd()
 * @method static \Illuminate\Database\Eloquent\Builder|User admin()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'phone',
        'opd_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the OPD that the user belongs to.
     */
    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    /**
     * Get the user's ASN profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(AsnProfile::class);
    }

    /**
     * Get the user's attendance records.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the user's mutation requests.
     */
    public function mutations(): HasMany
    {
        return $this->hasMany(Mutation::class);
    }

    /**
     * Get mutations reviewed by this user (OPD operator).
     */
    public function opdReviewedMutations(): HasMany
    {
        return $this->hasMany(Mutation::class, 'opd_reviewed_by');
    }

    /**
     * Get mutations reviewed by this user (BKPSDM admin).
     */
    public function bkpsdmReviewedMutations(): HasMany
    {
        return $this->hasMany(Mutation::class, 'bkpsdm_reviewed_by');
    }

    /**
     * Get attendances approved by this user.
     */
    public function approvedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'approved_by');
    }

    /**
     * Scope a query to only include ASN users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAsn($query)
    {
        return $query->where('role', 'asn');
    }

    /**
     * Scope a query to only include OPD operator users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOperatorOpd($query)
    {
        return $query->where('role', 'operator_opd');
    }

    /**
     * Scope a query to only include admin users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Check if the user is an ASN.
     *
     * @return bool
     */
    public function isAsn(): bool
    {
        return $this->role === 'asn';
    }

    /**
     * Check if the user is an OPD operator.
     *
     * @return bool
     */
    public function isOperatorOpd(): bool
    {
        return $this->role === 'operator_opd';
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}