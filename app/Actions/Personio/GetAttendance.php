<?php

namespace App\Actions\Personio;

use App\Models\Attendance;
use App\Personio\PersonioClient;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAttendance
{
    use AsAction;

    public function handle(array $config, string $personioId, ?string $from = null, ?string $to = null)
    {
        $token = GetToken::run($config, refresh: true);
        $page = 0;
        $limit = 200;
        do {
            $response = retry(3, fn () => PersonioClient::make($config, $token)->get('/company/attendances', [
                'employees' => [$personioId],
                'limit' => $limit,
                'includePending' => 'true',
                'offset' => $page * $limit,
                'start_date' => $from ?? '2020-01-01',
                'end_date' => $to ?? '2050-01-01',
            ]), 1000);

            if ($page === 0) {
                Attendance::query()->delete();
            }

            $token = RefreshToken::run($response);
            $json = $response->throw()->json();
            $data = [];
            foreach ($json['data'] as $attendanceRecord) {
                $data[] = [
                    'external_id' => $attendanceRecord['id'],
                    'date' => Arr::get($attendanceRecord, 'attributes.date'),
                    'start_time' => Arr::get($attendanceRecord, 'attributes.start_time'),
                    'end_time' => Arr::get($attendanceRecord, 'attributes.end_time'),
                    'description' => Arr::get($attendanceRecord, 'attributes.comment'),
                    'status' => Arr::get($attendanceRecord, 'attributes.status'),
                    'project' => Arr::get($attendanceRecord, 'attributes.project.attributes.name'),
                    'is_break' => Arr::get($attendanceRecord, 'attributes.break', false),
                    'is_holiday' => Arr::get($attendanceRecord, 'attributes.is_holiday', false),
                    'is_time_off' => Arr::get($attendanceRecord, 'attributes.is_on_time_off', false),
                ];
            }

            if (!empty($data)) {
                Attendance::upsert($data, ['external_id']);
            }
            $loadMore = Arr::get($json, 'metadata.current_page') < Arr::get($json, 'metadata.total_pages');
            if ($loadMore) {
                $page += 1;
            }
        } while ($loadMore);
    }
}
