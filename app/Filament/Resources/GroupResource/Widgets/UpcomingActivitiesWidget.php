<?php
// app/Filament/Resources/Group/Widgets/GroupStatsWidget.php
namespace App\Filament\Resources\GroupResource\Widgets;

use App\Models\ScheduleActivity;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;


class UpcomingActivitiesWidget extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return ScheduleActivity::query()
            ->whereHas('groupSchedule', function ($query) {
                $query->whereDate('date', '>=', now())
                    ->orderBy('date')
                    ->orderBy('time');
            })
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('groupSchedule.date')
                ->date(),
            Tables\Columns\TextColumn::make('time')
                ->time(),
            Tables\Columns\TextColumn::make('title'),
            Tables\Columns\BadgeColumn::make('category')
                ->colors([
                    'primary' => 'prayer',
                    'success' => 'tour',
                    'warning' => 'transport',
                    'danger' => 'meal',
                    'secondary' => 'rest',
                ]),
        ];
    }
}