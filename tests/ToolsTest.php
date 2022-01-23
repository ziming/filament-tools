<?php

use Filament\Forms\Components\TextInput;
use Livewire\Livewire;
use RyanChandler\FilamentTools\Exceptions\ToolsException;
use RyanChandler\FilamentTools\Tool;
use RyanChandler\FilamentTools\Tools;

beforeEach(fn () => Tools::can(function () {
    return true;
}));

it('can be mounted', function () {
    Livewire::test(Tools::class)
        ->assertOk();
});

it('can register a tool', function () {
    $fixture = (new Tool())->label('Foo');

    Tools::register(function (Tool $tool) use ($fixture): Tool {
        return $fixture;
    });

    expect(Livewire::test(Tools::class)->get('tools'))
        ->toContain($fixture);
});

it('throws an exception if no tool returned', function () {
    Tools::register(function () {
        return 'Foo';
    });
})->throws(ToolsException::class, 'Expected an instance of ' . Tool::class . ', got string instead.');

it('throws an exception if no label is set', function () {
    Tools::register(function (Tool $tool): Tool {
        return $tool;
    });
})->throws(ToolsException::class, 'All tools must have a label. Please set one by calling the `Tool::label()` method.');

it('validates tool forms', function () {
    Tools::register(function (Tool $tool) {
        return $tool
            ->label('Foo')
            ->schema([
                TextInput::make('bar')
                    ->required(),
            ])
            ->onSubmit(fn () => []);
    });

    Livewire::test(Tools::class)
        ->assertSee('Foo')
        ->call('callToolSubmitAction', 'foo')
        ->assertHasErrors([
            'data.foo.bar' => 'required',
        ]);
});

it('can restrict access', function () {
    Tools::can(function () {
        return false;
    });

    Livewire::test(Tools::class)
        ->assertForbidden();
});
