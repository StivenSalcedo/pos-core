<?php

namespace App\View\Components\Wireui;

use Illuminate\View\Component;

class Textarea extends Input {

    protected function getView(): string {
        return 'components.wireui.textarea';
    }
}
