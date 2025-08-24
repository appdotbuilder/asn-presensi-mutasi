<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Mutation
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $mutation_number
 * @property string $type
 * @property int $from_opd_id
 * @property int $to_opd_id
 * @property string $current_position
 * @property string $proposed_position
 * @property string|null $current_rank
 * @property string|null $proposed_rank
 * @property string $reason
 * @property \Illuminate\Support\Carbon $proposed_date
 * @property string $status
 * @property int|null $opd_reviewed_by
 * @property \Illuminate\Support\Carbon|null $opd_reviewed_at
 * @property string|null $opd_review_notes
 * @property int|null $bkpsdm_reviewed_by
 * @property \Illuminate\Support\Carbon|null $bkpsdm_reviewed_at
 * @property string|null $bkpsdm_review_notes
 * @property \Illuminate\Support\Carbon|null $effective_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Opd $fromOpd
 * @property-read \App\Models\Opd $toOpd
 * @property-read \App\Models\User|null $opdReviewer
 * @property-read \App\Models\User|null $bkpsdmReviewer
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereBkpsdmReviewNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereBkpsdmReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereBkpsdmReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereCurrentPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereCurrentRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereFromOpdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereMutationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereOpdReviewNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereOpdReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereOpdReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereProposedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereProposedPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereProposedRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereToOpdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation submitted()
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation opdReview()
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation bkpsdmReview()
 * @method static \Illuminate\Database\Eloquent\Builder|Mutation completed()
 * @method static \Database\Factories\MutationFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Mutation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'mutation_number',
        'type',
        'from_opd_id',
        'to_opd_id',
        'current_position',
        'proposed_position',
        'current_rank',
        'proposed_rank',
        'reason',
        'proposed_date',
        'status',
        'opd_reviewed_by',
        'opd_reviewed_at',
        'opd_review_notes',
        'bkpsdm_reviewed_by',
        'bkpsdm_reviewed_at',
        'bkpsdm_review_notes',
        'effective_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'proposed_date' => 'date',
        'opd_reviewed_at' => 'datetime',
        'bkpsdm_reviewed_at' => 'datetime',
        'effective_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the mutation request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the source OPD.
     */
    public function fromOpd(): BelongsTo
    {
        return $this->belongsTo(Opd::class, 'from_opd_id');
    }

    /**
     * Get the destination OPD.
     */
    public function toOpd(): BelongsTo
    {
        return $this->belongsTo(Opd::class, 'to_opd_id');
    }

    /**
     * Get the OPD reviewer.
     */
    public function opdReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opd_reviewed_by');
    }

    /**
     * Get the BKPSDM reviewer.
     */
    public function bkpsdmReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bkpsdm_reviewed_by');
    }

    /**
     * Scope a query to only include draft mutations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include submitted mutations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope a query to only include mutations under OPD review.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpdReview($query)
    {
        return $query->where('status', 'opd_review');
    }

    /**
     * Scope a query to only include mutations under BKPSDM review.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBkpsdmReview($query)
    {
        return $query->where('status', 'bkpsdm_review');
    }

    /**
     * Scope a query to only include completed mutations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}