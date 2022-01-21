@props(['tool'])

<x-filament::card :heading="$tool->getLabel()" class="col-span-{{ $tool->getColumnSpan() }}">
    @if($tool->hasView())
        {{ $tool->getView() }}
    @else
        <form wire:submit.prevent="callToolSubmitAction({{ json_encode($tool->getId()) }})">
            {{ $tool->getForm($this) }}

            <x-filament::button type="submit">
                {{ $tool->getSubmitButtonLabel() }}
            </x-filament::button>
        </form>
    @endif
</x-filament::card>
