<?php

namespace RyanChandler\FilamentTools;

use Illuminate\Support\Collection;

class ToolInput extends Collection
{
    protected Tools $component;

    /** @internal */
    public function component(Tools $component): void
    {
        $this->component = $component;
    }

    public function notify(string $status, string $message): static
    {
        $this->component->notification($status, $message);

        return $this;
    }
}
