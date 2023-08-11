<?php

namespace App\Personio\DTO;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class PersonioAttendance implements Arrayable
{
    private function __construct(
        public int $id,
        public Carbon $start,
        public Carbon $end,
        public int $break,
        public ?string $comment,
        public string $status,
        public bool $isHoliday,
        public bool $isTimeOff,
        public ?PersonioProject $project,
    ) {

    }

    public static function make(array $data): self
    {
        return new self(
            id: $data['id'],
            start: Carbon::parse($data['attributes']['date'].' '.$data['attributes']['start_time'].':00'),
            end: Carbon::parse($data['attributes']['date'].' '.$data['attributes']['end_time'].':00'),
            break: $data['attributes']['break'],
            comment: $data['attributes']['comment'],
            status: $data['attributes']['status'],
            isHoliday: $data['attributes']['is_holiday'],
            isTimeOff: $data['attributes']['is_on_time_off'],
            project: isset($data['attributes']['project']) ? PersonioProject::make($data['attributes']['project']) : null,
        );
    }

    public function durationInMinutes(): int
    {
        return $this->start->diffInMinutes($this->end) - $this->break;
    }

    public function formattedTime(): string
    {
        $hours = floor($this->durationInMinutes() / 60);
        $minutes = $this->durationInMinutes() % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'start' => $this->start->toIso8601String(),
            'end' => $this->end->toIso8601String(),
            'break' => $this->break,
            'comment' => $this->comment,
            'status' => $this->status,
            'is_holiday' => $this->isHoliday,
            'is_time_off' => $this->isTimeOff,
            'project' => $this->project?->toArray(),
        ];
    }
}
