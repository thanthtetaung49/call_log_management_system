<?php

namespace App\Livewire;

// use App\Exports\UsersExport;

use App\Exports\UsersExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class CallLogManagement extends Component
{
    public bool $exportStatus = false;
    public string $downloadLink = '';
    public string $filePath = '';

    public string $msisdns = '';
    public string $startDate = '';
    public string $endDate = '';

    public function mount()
    {
        $this->authorize('userAccess', User::class);
    }

    public function export()
    {
        $msisdns = $this->msisdns;
        $startDate = (int)Carbon::parse($this->startDate)->format('Ymd');
        $endDate = (int)Carbon::parse($this->endDate)->format('Ymd');

        $hashedName = md5(now()->timestamp . auth()->id()) . '.xlsx';
        $this->filePath = 'callLogsExport/' . $hashedName;

        (new UsersExport($msisdns, $startDate, $endDate))->store($this->filePath, 'public');

        $this->downloadLink = Storage::disk('public')->url($this->filePath);
        $this->exportStatus = Storage::disk('public')->exists($this->filePath);
    }

    public function downloadCallLog()
    {
        return Storage::disk('public')->download($this->filePath);
    }

    public function render()
    {
        return view('livewire.call-log-management');
    }
}
