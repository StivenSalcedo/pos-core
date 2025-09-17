<?php

namespace App\View\Components\Wireui;

use Illuminate\Support\{Str, Stringable};

class Checkbox extends FormComponent {

    public bool $sm;

    public bool $md;

    public bool $lg;

    public ?string $label;

    public ?string $leftLabel;

    public ?string $description;

    public function __construct(
        bool $md = false,
        bool $lg = false,
        ?string $label = null,
        ?string $leftLabel = null,
        ?string $description = null
    ) {

        $this->sm           = !$md && !$lg;
        $this->md           = $md;
        $this->lg           = $lg;
        $this->label        = $label;
        $this->leftLabel    = $leftLabel;
        $this->description  = $description;
    }

    protected function getView(): string
    {
        return 'components.wireui.checkbox';
    }

    public function getClasses(bool $hasError): string
    {
        return Str::of("form-checkbox rounded transition ease-in-out duration-100")->unless(
            $hasError,
            function (Stringable $stringable) {
                return $stringable->append('
                    border-slate-300 text-blue-600 focus:ring-blue-600 focus:border-blue-400
                ');
            },
            function (Stringable $stringable) {
                return $stringable->append('
                    focus:ring-red-500 ring-red-500 border-red-400 text-red-600
                    focus:border-red-400
                ');
            },
        );
    }
}
