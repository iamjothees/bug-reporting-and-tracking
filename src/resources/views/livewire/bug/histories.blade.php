<x-filament::section>
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
</x-filament::section>
