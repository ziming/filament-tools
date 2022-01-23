<?php

namespace RyanChandler\FilamentTools;

use Closure;
use Error;
use Filament\Forms\ComponentContainer;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Illuminate\View\View;
use RyanChandler\FilamentTools\Exceptions\ToolsException;

final class Tool
{
    use Macroable;

    protected string $label;

    protected array $schema = [];

    protected ?Closure $onSubmitCallback = null;

    protected ?string $view = null;

    protected array $viewData = [];

    protected int $columnSpan = 4;

    protected string $submitButtonLabel = 'Submit';

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function schema(array $schema): static
    {
        $this->schema = $schema;

        return $this;
    }

    public function onSubmit(Closure $callback): static
    {
        $this->onSubmitCallback = $callback;

        return $this;
    }

    public function view(string $view, array $data = []): static
    {
        $this->view = $view;
        $this->viewData = $data;

        return $this;
    }

    public function columnSpan(int $columns): static
    {
        $this->columnSpan = $columns;

        return $this;
    }

    public function submitButtonLabel(string $label): static
    {
        $this->submitButtonLabel = $label;

        return $this;
    }

    /** @internal */
    public function getSubmitButtonLabel(): string
    {
        return __($this->submitButtonLabel);
    }

    /** @internal */
    public function hasView(): bool
    {
        return $this->view !== null;
    }

    /** @internal */
    public function getView(): View
    {
        return view($this->view, $this->viewData);
    }

    /** @internal */
    public function getStatePath(): string
    {
        return 'data.' . $this->getId();
    }

    /** @internal */
    public function getId(): string
    {
        return Str::slug($this->label);
    }

    /** @internal */
    public function getForm(ComponentContainer $baseForm): ComponentContainer
    {
        return $baseForm
            ->statePath($this->getStatePath())
            ->schema($this->schema);
    }

    /** @internal */
    public function getSubmitAction(): ?Closure
    {
        return $this->onSubmitCallback;
    }

    /** @internal */
    public function getLabel(): string
    {
        return $this->label;
    }

    /** @internal */
    public function getColumnSpan(): int
    {
        return $this->columnSpan;
    }

    /** @internal */
    public function assert(): void
    {
        // This is required since `$this->label` is a typed-property.
        // Making it nullable feels weird since it's a required value.
        try {
            assert($this->label);
        } catch (Error $_) {
            throw ToolsException::missingLabel();
        }
    }
}
