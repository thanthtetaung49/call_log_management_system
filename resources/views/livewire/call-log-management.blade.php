<div>
    <h3 class="font-bold text-lg capitalize">call log</h3>

    <div class="mt-5">
        <flux:callout variant="warning" icon="exclamation-circle"
            heading="Fill in the required MSISDNs and date range to generate and export call logs." />
    </div>
    <div class="mt-5 bg-gray-100 p-5 rounded-lg shadow-sm dark:bg-zinc-900">


        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-1">
            <div>
                <flux:field>
                    <flux:label>MSISDNs</flux:label>
                    <flux:input wire:model.live="msisdns" placeholder="MSISDNs" />

                    <flux:error name="msisdns" />
                </flux:field>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-2 lg:grid-cols-2">
            <div>
                <flux:field>
                    <flux:input wire:model.live="startDate" type="date" max="2999-12-31" label="Start Date" />
                </flux:field>
            </div>
            <div>
                <flux:field>
                    <flux:input wire:model.live="endDate" type="date" max="2999-12-31" label="End Date" />
                </flux:field>
            </div>
        </div>

        <div class="flex justify-end mt-5">
            @if (empty($msisdns) || empty($startDate) || empty($endDate))
                <flux:modal.trigger>
                    <flux:button icon="arrow-down-tray">Export</flux:button>
                </flux:modal.trigger>
            @else
                <flux:modal.trigger name="export">
                    <flux:button icon="arrow-down-tray" wire:click="export">Export</flux:button>
                </flux:modal.trigger>
            @endif
        </div>
    </div>

    {{-- model here --}}
    <flux:modal name="export" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Call Log Export Ready!</flux:heading>
                <flux:text class="mt-2">
                    <p class="text-sm">You can now download the file using the link below.</p>
                    @if ($downloadLink)
                        <a wire:loading.remove wire:target="export" href="{{ $downloadLink }}" target="_blank"
                            class="text-xs text-blue-500 hover:underline">{{ $downloadLink }}</a>
                    @endif
                </flux:text>

                <div wire:loading wire:target="export" class="flex justify-center mt-3">
                    <flux:icon.loading />
                </div>
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
