<?php

namespace App\Livewire;

use App\Exports\UsersExport;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class CallLogManagement extends Component
{
    public function mount() {
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
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function render()
    {
        return view('livewire.call-log-management');
    }
}
