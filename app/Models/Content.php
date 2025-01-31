<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Content extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'tour_leader_id',
        'group_id',
        'title',
        'description',
        'type',

    ];

    public function tourLeader()
    {
        return $this->belongsTo(TourLeader::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->useDisk('media')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumbnail')
                    ->width(400)
                    ->height(400);
            });

        $this->addMediaCollection('videos')
            ->useDisk('media')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumbnail')
                    ->extractVideoFrameAtSecond(1)
                    ->width(400)
                    ->height(400)
                    ->performOnCollections('videos');
            });

        $this->addMediaCollection('content_files')
            ->useDisk('media');
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('photos', 'thumbnail') ?:
               $this->getFirstMediaUrl('videos') ?:
               null;
    }
}
