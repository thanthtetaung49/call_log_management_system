<div>
    <h3 class="font-bold text-lg capitalize">user management</h3>

    <div class="flex justify-end mt-4">
        <div class="w-1/2 flex">
            <flux:input icon="magnifying-glass" wire:model.live.debounce.300ms="query" placeholder="Search..." />

            <flux:select wire:model.live.debounce.300ms="filterRole" placeholder="Role" class="ms-3">
                <flux:select.option value="admin">Admin</flux:select.option>
                <flux:select.option value="user">User</flux:select.option>
            </flux:select>

            <flux:select wire:model.live.debounce.300ms="filterStatus" placeholder="Status" class="ms-3">
                <flux:select.option value="approved">Approved</flux:select.option>
                <flux:select.option value="pending">Pending</flux:select.option>
            </flux:select>

            @if ($filterRole !== '' || $filterStatus !== '')
                <flux:button variant="danger" icon="x-mark" class="ms-3" x-data
                    x-on:click="
                    $wire.set('filterRole', '');
                    $wire.set('filterStatus', '');
                ">Clear All</flux:button>
            @endif
        </div>
    </div>

    <div class="mt-3 overflow-x-auto rounded-lg text-sm">
        @include('callLogTable.callLog', [
            'users' => $users,
        ])
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
