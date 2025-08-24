<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Opd
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mutation> $fromMutations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mutation> $toMutations
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Opd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Opd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Opd query()
 * @method static \Illuminate\Database\Eloquent\Builder|Opd whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Opd active()
 * @method static \Database\Factories\OpdFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Opd extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'address',
        'phone',
        'email',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the users that belong to this OPD.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get mutations where this OPD is the source.
     */
    public function fromMutations(): HasMany
    {
        return $this->hasMany(Mutation::class, 'from_opd_id');
    }

    /**
     * Get mutations where this OPD is the destination.
     */
    public function toMutations(): HasMany
    {
        return $this->hasMany(Mutation::class, 'to_opd_id');
    }

    /**
     * Scope a query to only include active OPDs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}