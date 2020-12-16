<?php

namespace Msouto\NovaFlexibleContent\Layouts;

use Msouto\NovaFlexibleContent\Flexible;

abstract class Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     */
    abstract public function handle(Flexible $field);

}
