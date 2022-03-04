<?php

namespace RyanChandler\FilamentTools;

use Illuminate\Support\Collection;

class ToolInput extends Collection
{
    protected Tools $component;

    protected Tool $tool;

    /** @internal */
    public function component(Tools $component): static
    {
        $this->component = $component;

        return $this;
    }

    /** @internal */
    public function tool(Tool $tool): static
    {
        $this->tool = $tool;

        return $this;
    }

    public function notify(string $status, string $message): static
    {
        $this->component->notification($status, $message);

        return $this;
    }

    public function clear(): void
    {
        data_set($this->component, $this->tool->getStatePath(), []);
    }
}
