<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pilgrim extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'gender',
        'health_notes',
        'photo'  // pastikan photo ada di fillable
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'health_notes' => 'string', // ubah dari array ke string
    ];

    /**
     * Relationship with Group
     *
     * @return BelongsToMany
     */
    public function group(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Register media collections for photo management
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_photo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
    }

    /**
     * Get formatted health notes
     *
     * @return string
     */
    public function getFormattedHealthNotesAttribute(): string
    {
        if (empty($this->health_notes)) {
            return 'No specific health notes';
        }

        return collect($this->health_notes)
            ->map(fn($note, $key) => ucfirst($key) . ': ' . $note)
            ->implode("\n");
    }

    /**
     * Scope to filter pilgrims by group
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $groupId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    /**
     * Get photo attribute
     *
     * @param string $value
     * @return string|null
     */
    public function getPhotoAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
