<div>
    <h3 class="font-bold text-lg capitalize">call log</h3>

    <div class="mt-4 bg-gray-100 p-5 rounded-lg shadow-sm dark:bg-zinc-900">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-1">
            <div>
                <flux:field>
                    <flux:label>MSISDNs</flux:label>
                    <flux:input placeholder="MSISDNs" />
                    {{-- <flux:error name="username" /> --}}
                </flux:field>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-2 lg:grid-cols-2">
            <div>
                <flux:field>
                    <flux:input type="date" max="2999-12-31" label="Start Date" />
                    {{-- <flux:error name="username" /> --}}
                </flux:field>
            </div>
            <div>
                <flux:field>
                    <flux:input type="date" max="2999-12-31" label="End Date" />
                    {{-- <flux:error name="username" /> --}}
                </flux:field>
            </div>
        </div>

        <div class="flex justify-end">
            <flux:button variant="primary" class="mt-4">Generate</flux:button>
        </div>
    </div>

    <flux:separator class="mt-5" />

    <div class="mt-5 overflow-x-auto rounded-lg text-sm">
        <h3 class="font-bold text-lg capitalize">preview call log</h3>

        <div class="flex justify-end">
            <flux:modal.trigger name="export">
                <flux:button icon="arrow-down-tray" wire:click="export">Export</flux:button>
            </flux:modal.trigger>
        </div>

        @include('callLogTable.callLog', [
            'users' => $this->users,
        ])
    </div>

    {{-- model here --}}
    <flux:modal name="export" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Call Log Export Ready!</flux:heading>
                <flux:text class="mt-2">
                    <p class="text-sm">You can now download the file using the link below.</p>
                    <p class="text-xs text-blue-500 cursor-pointer hover:underline" wire:click="downloadCallLog">{{ $downloadLink }}</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>
</div>
