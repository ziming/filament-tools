<?php

namespace RyanChandler\FilamentTools;

use Closure;
use Error;
use Illuminate\Support\Traits\Macroable;
use RyanChandler\FilamentTools\Exceptions\ToolsException;

final class Tool
{
    use Macroable;

    protected string $label;

    protected array $schema = [];

    protected ?Closure $onSubmitCallback = null;

    protected ?string $view = null;

    protected array $viewData = [];

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
        $this->onSubmit = $callback;

        return $this;
    }

    public function view(string $view, array $data = []): static
    {
        $this->view = $view;
        $this->viewData = $data;

        return $this;
    }

    /** @internal */
    public function hasSchema(): bool
    {
        return count($this->schema) > 0;
    }

    /** @internal */
    public function hasView(): bool
    {
        return $this->view !== null;
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
