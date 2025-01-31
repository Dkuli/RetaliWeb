<?php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourLeader extends Authenticatable implements HasMedia
{
    use HasFactory,HasApiTokens, Notifiable, InteractsWithMedia;


    protected $appends = ['avatar_url'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'fcm_token',
        'is_active',
        'current_group_id',
        'activation_start',
        'activation_end',
        'activation_code',
        'last_active_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'media'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activation_start' => 'date',
        'activation_end' => 'date',
    ];

    public function getAvatarUrlAttribute()
    {
        return $this->getFirstMediaUrl('avatar') ?: null;
    }

    public function currentGroup()
    {
        return $this->belongsTo(Group::class, 'current_group_id');
    }

    public function locations()
    {
        return $this->hasMany(TrackingLocation::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function assignedQuestionnaires()
    {
    return $this->belongsToMany(Questionnaire::class, 'questionnaire_tour_leaders')
        ->withPivot(['status', 'assigned_at', 'completed_at']);
    }

    public function questionnaireResponses()
    {
    return $this->hasMany(QuestionnaireResponse::class);
    }

 
}
