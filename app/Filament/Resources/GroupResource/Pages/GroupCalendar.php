<?php
// app/Filament/Resources/GroupResource/Pages/GroupCalendar.php
namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use App\Models\Group;
use Filament\Resources\Pages\Page;

class GroupCalendar extends Page
{
    protected static string $resource = GroupResource::class;
    protected static string $view = 'filament.resources.group.pages.calendar';

    public Group $record;  // Changed from $group to $record for Filament convention

    public function mount(Group $record): void  // Proper model binding
    {
        $this->record = $record;
    }

    protected function getViewData(): array
    {
        return [
            'events' => $this->record->schedules()
                ->with('activities')
                ->get()
                ->flatMap(function ($schedule) {
                    return $schedule->activities->map(function ($activity) use ($schedule) {
                        return [
                            'title' => $activity->title,
                            'start' => $schedule->date->format('Y-m-d') . 'T' . $activity->time->format('H:i:s'),
                            'className' => 'bg-' . ($activity->category ?? 'default'),
                            'extendedProps' => [
                                'location' => $activity->location,
                                'category' => $activity->category,
                                'description' => $activity->description,
                            ]
                        ];
                    });
                }),
        ];
    }
}
