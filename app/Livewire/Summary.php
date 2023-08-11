<?php

namespace App\Livewire;

use App\Models\Configuration;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Laravel\Facades\MenuBar;

class Summary extends Component
{
    public function mount()
    {
        $this->resync();
    }

    #[On('resync')]
    public function resync()
    {
        $provider = Configuration::timeTrackingProvider();
        $provider->syncAttendances();
    }

    public function placeholder()
    {
        return '<div>
            <div class="flex items-end">
                ' . view('components.skeleton', ['class' => 'w-[100px] h-10']) . '
                ' . view('components.skeleton', ['class' => 'ml-2 w-[150px] h-4']) . '
                ' . view('components.skeleton', ['class' => 'w-[100px] h-10 ml-auto']) . '
                ' . view('components.skeleton', ['class' => 'ml-2 w-[150px] h-4']) . '
            </div>
            <ul class="space-y-1 mt-4">
            ' . view('components.skeleton', ['class' => 'h-10']) . '
            ' . view('components.skeleton', ['class' => 'h-10']) . '
            ' . view('components.skeleton', ['class' => 'h-10']) . '
            ' . view('components.skeleton', ['class' => 'h-10']) . '
            ' . view('components.skeleton', ['class' => 'h-10']) . '
        </div>';
    }

    public function render()
    {
        $provider = Configuration::timeTrackingProvider();
        $trackedToday = $provider->timeTrackedToday();
        $trackedThisWeek = $provider->timeTrackedThisWeek();
        $minutesToHoursAndMinutes = function ($minutes) {
            return sprintf('%01dh %02dm', $minutes / 60, $minutes % 60);
        };
        $trackedToday = $minutesToHoursAndMinutes($trackedToday);
        $trackedThisWeek = $minutesToHoursAndMinutes($trackedThisWeek);
        MenuBar::label(' Tracked ' . $trackedToday);

        $attendance = $provider->attendanceOfToday();
        return view('livewire.summary', [
            'attendances' => $attendance,
            'trackedToday' => $trackedToday,
            'trackedThisWeek' => $trackedThisWeek,
        ]);
    }
}
