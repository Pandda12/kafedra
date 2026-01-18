<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTO\DashboardRatingChartParams;
use App\Enums\{TaskStatus, UserSelectType};
use App\Models\{AcademicYear, Event, Publication, TaskUser};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class GetDashboardRatingChartDataAction
{
    public function execute( DashboardRatingChartParams $params ): array
    {
        $academicYear = AcademicYear::whereSlug($params->academicYearSlug)->firstOrFail();
        $academicYearId = $academicYear->id;

        $publicationRating = (int) ($academicYear->settings['publication_rating'] ?? 0);
        $eventRating = (int) ($academicYear->settings['event_rating'] ?? 0);

        $isPersonal = $params->assignedType === UserSelectType::PERSONAL->value;
        $isRoleFilter = in_array($params->assignedType, [
            UserSelectType::FULL_TIME->value,
            UserSelectType::PART_TIME->value
        ], true);

        $tasksQuery = TaskUser::query()
            ->from('task_user')
            ->join('tasks', 'tasks.id', '=', 'task_user.task_id')
            ->where('task_user.status', TaskStatus::CLOSED->value)
            ->where('tasks.academic_year_id', $academicYearId)
            ->whereMonth('task_user.completed_at', $params->month);

        $publicationsQuery = Publication::query()
            ->from('publications')
            ->where('publications.academic_year_id', $academicYearId)
            ->whereMonth('publications.created_at', $params->month);

        $eventsQuery = Event::query()
            ->from('events')
            ->where('events.academic_year_id', $academicYearId)
            ->whereMonth('events.created_at', $params->month);

        if ( $isPersonal ) {
            $assignedAt = $params->assignedAt;

            $tasksQuery->select(['tasks.name', 'tasks.rating',  'tasks.ends_on', 'task_user.completed_at'])
                ->whereIn('task_user.user_id', $assignedAt);

            $publicationsQuery->select(['publications.name'])
                ->whereIn('publications.user_id', $assignedAt);

            $eventsQuery->select(['events.name'])
                ->whereIn('events.user_id', $assignedAt);

        } else {
            $applyUserJoins = function ( Builder $q, string $userIdColumn ) use ($isRoleFilter, $params) {
                $q->join('academic_year_user', $userIdColumn, '=', 'academic_year_user.user_id')
                    ->join('users', $userIdColumn, '=', 'users.id')
                    ->groupBy('users.id')
                    ->select([
                        'users.id',
                        'users.first_name',
                        'users.second_name',
                        'users.last_name',
                    ]);

                if ( $isRoleFilter ) {
                    $q->where('academic_year_user.role', $params->assignedType);
                }

                return $q;
            };

            $tasksQuery
                ->join('academic_year_user', 'task_user.user_id', '=', 'academic_year_user.user_id')
                ->join('users', 'task_user.user_id', '=', 'users.id')
                ->select([
                    'users.id',
                    'users.first_name',
                    'users.second_name',
                    'users.last_name',
                    'tasks.rating',
                    'tasks.ends_on',
                    'task_user.completed_at'
                ]);

            if ( $isRoleFilter ) {
                $tasksQuery->where('academic_year_user.role', $params->assignedType);
            }

            $applyUserJoins($publicationsQuery, 'publications.user_id');
            $publicationsQuery->addSelect(DB::raw('COUNT(*) as cnt'));

            $applyUserJoins($eventsQuery, 'events.user_id');
            $eventsQuery->addSelect(DB::raw('COUNT(*) as cnt'));

        }

        $items = [];

        if ( $isPersonal ) {

            foreach ( $tasksQuery->get() as $row ) {
                $items[] = [
                    'name' => ['Задача:', $row->name],
                    'rating' => $row->ends_on < $row->completed_at ? 0 - $row->rating : $row->rating
                ];
            }

            foreach ( $publicationsQuery->get() as $row ) {
                $items[] = ['name' => ['Публикация:', $row->name], 'rating' => $publicationRating];
            }

            foreach ( $eventsQuery->get() as $row ) {
                $items[] = ['name' => ['Мероприятие:', $row->name], 'rating' => $eventRating];
            }

        } else {

            $aggregate = [];

            $add = function ( int $userId, string $fullName, int $rating ) use (&$aggregate): void {
                if ( !isset($aggregate[$userId]) ) {
                    $aggregate[$userId] = ['name' => $fullName, 'rating' => 0];
                }

                $aggregate[$userId]['rating'] += $rating;
            };

            foreach ( $tasksQuery->get() as $row ) {
                $fullName = trim($row->second_name . ' ' . $row->first_name);

                if ( !isset($aggregate[$row->id]) ) {
                    $aggregate[$row->id] = ['name' => $fullName, 'rating' => 0];
                }

                $rating =  $row->ends_on < $row->completed_at ? 0 - $row->rating : $row->rating;

                $aggregate[$row->id]['rating'] += $rating;
            }

            foreach ( $publicationsQuery->get() as $row ) {
                $fullName = trim($row->second_name . ' ' . $row->first_name);
                $add((int) $row->id, $fullName, (int) $row->cnt * $publicationRating);
            }
            foreach ( $eventsQuery->get() as $row ) {
                $fullName = trim($row->second_name . ' ' . $row->first_name);
                $add((int) $row->id, $fullName, (int) $row->cnt * $eventRating);
            }

            $items = array_values($aggregate);
        }

        $data = ['items' => [], 'rating' => []];

        foreach ( $items as $item ) {
            $data['items'][]  = $item['name'];
            $data['rating'][] = $item['rating'];
        }

        return $data;
    }

    public function prepareToExport( array $data ): array
    {
        $export = [
            ['name', 'rating']
        ];

        foreach ( $data['items'] as $key => $value ) {

            $export[] = [
                is_array( $value ) ? implode(' ', $value) : $value, $data['rating'][$key]
            ];
        }

        return $export;
    }
}
