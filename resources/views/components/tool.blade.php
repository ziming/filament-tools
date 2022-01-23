@props(['tool'])

<x-filament::card :heading="$tool->getLabel()" class="col-span-{{ $tool->getColumnSpan() }}">
    @if($tool->hasView())
        {{ $tool->getView() }}
    @else
        <x-filament::form wire:submit.prevent="callToolSubmitAction({{ json_encode($tool->getId()) }})">
            {{ $this->getCachedForm($tool->getId()) }}

            <x-filament::button type="submit">
                {{ $tool->getSubmitButtonLabel() }}
            </x-filament::button>
        </x-filament::form>
    @endif
</x-filament::card>
