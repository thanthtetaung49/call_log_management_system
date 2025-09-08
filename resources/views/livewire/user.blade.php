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
                ">
                    Clear All</flux:button>
            @endif
        </div>
    </div>

    <div class="mt-3 overflow-x-auto rounded-lg text-sm">
        <div>
            <table class="w-full border-collapse mt-5">
                <thead>
                    <tr class="bg-black text-white">
                        <th class="p-2 border border-gray-300 text-left">No.</th>
                        <th class="p-2 border border-gray-300 text-left">Name</th>
                        <th class="p-2 border border-gray-300 text-left">Email</th>
                        <th class="p-2 border border-gray-300 text-left">Role</th>
                        <th class="p-2 border border-gray-300 text-left">Status</th>
                        <th class="p-2 border border-gray-300 text-left">Created at</th>
                        <th class="p-2 border border-gray-300 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-100">
                            <td class="p-2 border border-gray-300">{{ $loop->iteration }}</td>
                            <td class="p-2 border border-gray-300">{{ $user->name }}</td>
                            <td class="p-2 border border-gray-300">{{ $user->email }}</td>
                            <td class="p-2 border border-gray-300">
                                @if ($user->role === 'admin')
                                    <flux:badge color="blue">{{ $user->role }}</flux:badge>
                                @else
                                    <flux:badge color="orange">{{ $user->role }}</flux:badge>
                                @endif
                            </td>
                            <td class="p-2 border border-gray-300">
                                @if ($user->status === 'approved')
                                    <flux:badge color="green">{{ $user->status }}</flux:badge>
                                @else
                                    <flux:badge color="yellow">{{ $user->status }}</flux:badge>
                                @endif
                            </td>
                            <td class="p-2 border border-gray-300">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, H:i A') }}</td>
                            <td class="p-2 border border-gray-300">
                                <flux:modal.trigger name="edit">
                                    <flux:button wire:click="edit({{ $user->id }})">Edit</flux:button>
                                </flux:modal.trigger>

                                <flux:modal.trigger name="delete">
                                    <flux:button wire:click="edit({{ $user->id }})" variant="danger" class="ms-3">
                                        Delete</flux:button>
                                </flux:modal.trigger>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>

        {{-- modal here --}}
        <flux:modal name="edit" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update profile</flux:heading>
                    <flux:text class="mt-2">Make changes to your personal details.</flux:text>
                </div>

                <flux:input wire:model="name" label="Name" placeholder="Your name" />
                <flux:input wire:model="email" label="Email" placeholder="Your email" />
                <flux:input wire:model="employee_id" label="Employee ID" placeholder="Your TID" />

                <flux:select wire:model="role" placeholder="Role" label="Role">
                    <flux:select.option value="admin">Admin</flux:select.option>
                    <flux:select.option value="user">User</flux:select.option>
                </flux:select>

                <flux:select wire:model="status" placeholder="Status" label="Status">
                    <flux:select.option value="approved">Approved</flux:select.option>
                    <flux:select.option value="pending">Pending</flux:select.option>
                </flux:select>

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary" wire:click="save">Save changes</flux:button>
                </div>
            </div>
        </flux:modal>


        <flux:modal name="delete" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete user?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You're about to delete this user.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger" wire:click="delete">Delete user</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>
