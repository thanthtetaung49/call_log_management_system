<?php

namespace App\Livewire;

// use App\Exports\UsersExport;

use App\Exports\UsersExport;
use App\Models\User;
use App\Models\UserExportLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class CallLogManagement extends Component
{
    public $downloadLink = null;
    public $filePath = null;

    public string $msisdns = '';
    public string $startDate = '';
    public string $endDate = '';

    public function mount()
    {
        $this->authorize('userAccess', User::class);
    }

    public function rules () {
        return [
            'msisdns' => 'required',
            'startDate' => 'required|date',
            'endDate'   => 'required|date|after:startDate',
        ];
    }

    public function export()
    {
        $this->reset('downloadLink');

        $this->validate();

        $msisdns = $this->msisdns;
        $startDate = (int)Carbon::parse($this->startDate)->format('Ymd');
        $endDate = (int)Carbon::parse($this->endDate)->format('Ymd');

        $hashedName = md5(now()->timestamp . auth()->id()) . '.xlsx';
        $this->filePath = 'callLogsExport/' . $hashedName;

        (new UsersExport($msisdns, $startDate, $endDate))->store($this->filePath, 'public');

        $userExportLog = UserExportLog::create([
            'user_id' => auth()->user()->id,
            'file_name' => $hashedName,
            'folder_path' => Storage::disk('public')->url($this->filePath)
        ]);

        $this->reset('msisdns', 'startDate', 'endDate');

        $this->downloadLink = $userExportLog->folder_path;

    }

    public function render()
    {
        return view('livewire.call-log-management');
    }
}
