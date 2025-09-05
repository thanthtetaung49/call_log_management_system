<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, ShouldAutoSize, ShouldQueue, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return User::query()->select('name', 'email', 'employee_id', 'created_at');
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Employee ID', 'Created At'];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->employee_id,
            $user->created_at?->format('Y-m-d H:i:s') ?? '',
        ];
    }
}
