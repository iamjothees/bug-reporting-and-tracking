<x-filament::modal slide-over>
    <x-slot name="trigger">
        <x-filament::button color="warning" size="sm" outlined>
            History
        </x-filament::button>
    </x-slot>
    <x-slot name="heading">
        History
    </x-slot>
    <ul class="list-disc ps-4 text-sm">
        @foreach ($bug->histories->reverse() as $instance)
            <li class="mb-2">
                {{ " {$instance->updater->name} {$instance->description} {$instance->created_at->diffForHumans()}" }}
            </li>
        @endforeach    
    </ul>
</x-filament::modal>
