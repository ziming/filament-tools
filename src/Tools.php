<?php

namespace RyanChandler\FilamentTools;

use Closure;
use Filament\Pages\Page;
use RyanChandler\FilamentTools\Exceptions\ToolsException;

class Tools extends Page
{
    protected static string $view = 'filament-tools::tools';

    protected static ?string $navigationGroup = null;

    protected static ?string $navigationIcon = null;

    /** @var array<\RyanChandler\FilamentTools\Tool> */
    protected static array $tools = [];

    public $data = [];

    /** @return array<\RyanChandler\FilamentTools\Tool> */
    public function getToolsProperty(): array
    {
        return static::$tools;
    }

    /** @internal */
    public function notification(string $status, string $message): static
    {
        $this->notify($status, $message);

        return $this;
    }

    public function callToolSubmitAction(string $id): void
    {
        /** @var \RyanChandler\FilamentTools\Tool $tool */
        $tool = $this->tools[$id];

        if ($action = $tool->getSubmitAction()) {
            $input = new ToolInput($this->getCachedForm($id)->getState());
            $input->component($this);

            $action($input);
        }
    }

    /** @param \Closure(\Filament\Pages\Page): \Filament\Pages\Page $configure */
    public static function register(Closure $configure): void
    {
        /** @var \RyanChandler\FilamentTools\Tool $tool */
        $tool = app()->call($configure, [
            'tool' => new Tool(),
        ]);

        if (! $tool instanceof Tool) {
            throw ToolsException::expectedTool(actual: $tool);
        }

        $tool->assert();

        static::$tools[$tool->getId()] = $tool;
    }

    protected function getForms(): array
    {
        return collect(static::$tools)->mapWithKeys(function (Tool $tool) {
            return [$tool->getId() => $tool->getForm($this->makeForm())];
        })->merge(parent::getForms())->all();
    }

    public static function navigationGroup(string $group): void
    {
        static::$navigationGroup = $group;
    }

    public static function navigationIcon(string $icon): void
    {
        static::$navigationIcon = $icon;
    }
}
