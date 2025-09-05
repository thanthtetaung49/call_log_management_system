<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromView, ShouldAutoSize
{
    public function view(): \Illuminate\Contracts\View\View
    {
        return view('exports.users', [
            'users' => User::all()
        ]);
    }
}
