<?php

namespace App\Livewire;

// use App\Exports\UsersExport;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class CallLogManagement extends Component
{
    public bool $exportStatus = false;
    public string $downloadLink = '';
    public string $filePath = '';

    public function mount()
    {
        $this->authorize('userAccess', User::class);
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->take(3)
            ->get();
    }

    public function export()
    {
        $hashedName = md5(now()->timestamp . auth()->id()) . '.xlsx';
        $this->filePath = 'callLogsExport/' . $hashedName;

        (new UsersExport)->store($this->filePath, 'public');
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
