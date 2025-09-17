<?php

namespace App\View\Components\Wireui;

use Illuminate\View\Component;

class Label extends Component {

    public function __construct( public bool $hasError=false, public ?string $label=null) {

    }


    public function render() {
        return view('components.wireui.label');
    }
}
