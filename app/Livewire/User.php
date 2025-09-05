<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User as ModelsUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\Rule;

class User extends Component
{
    use WithPagination;

    public string $query = '';
    public string $filterRole = '';
    public string $filterStatus = '';

    public string $name = '';
    public string $email = '';
    public ?string $employee_id = '';
    public string $role = '';
    public string $status = '';

    public ?ModelsUser $user;

    public function mount()
    {
        $this->authorize('viewAny', ModelsUser::class);
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')->ignore($this->user)],
            'employee_id' => ['required', Rule::unique('users', 'employee_id')->ignore($this->user)],
            'role' => 'required',
            'status' => 'required',
        ];
    }

    public function edit($id)
    {
        $user = ModelsUser::findOrFail($id);

        $this->user = $user;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->employee_id = $user->employee_id;
        $this->role = $user->role;
        $this->status  = $user->status;
    }

    public function save()
    {
        $this->validate();

        $this->user->update($this->only([
            'name',
            'email',
            'employee_id',
            'role',
            'status'
        ]));

        $this->redirectRoute('userManagement', navigate: true);
    }

    public function delete()
    {
        $this->user->delete();

        $this->redirectRoute('userManagement', navigate: true);
    }

    public function render()
    {
        $users = ModelsUser::when(isset($this->query) && $this->query !== '', function ($query) {
            $query->where('name', 'like', '%' . $this->query . '%')
                ->orWhere('email', 'like', '%' . $this->query . '%');
        })
            ->when(isset($this->filterRole) && $this->filterRole !== '', function ($query) {
                $query->where('role', $this->filterRole);
            })
            ->when(isset($this->filterStatus) && $this->filterStatus !== '', function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->paginate(8);

        return view('livewire.user', [
            'users' => $users,
        ]);
    }
}
