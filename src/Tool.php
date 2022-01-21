<?php

namespace RyanChandler\FilamentTools;

use Error;
use Illuminate\Support\Traits\Macroable;
use RyanChandler\FilamentTools\Exceptions\ToolsException;

final class Tool
{
    use Macroable;

    protected string $label;

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
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
