<?php

namespace App\View\Components\Wireui;

use Illuminate\Support\{Str, Stringable};

class Input extends FormComponent {

    public bool $onlyNumbers;

    public bool $borderless;

    public bool $shadowless;

    public ?string $label;

    public ?string $hint;

    public ?string $icon;

    public ?string $rightIcon;

    public ?string $prefix;

    public bool $errorless;

    public function __construct(
        bool $onlyNumbers = false,
        bool $borderless = false,
        bool $shadowless = false,
        ?string $label = null,
        ?string $hint = null,
        ?string $icon = null,
        ?string $rightIcon = null,
        ?string $prefix = null,
        bool $errorless = false
    ) {
        $this->onlyNumbers= $onlyNumbers;
        $this->borderless = $borderless;
        $this->shadowless = $shadowless;
        $this->label      = $label;
        $this->hint       = $hint;
        $this->icon       = $icon ? 'ico icon-' . $icon : null;
        $this->rightIcon  = $rightIcon;
        $this->prefix     = $prefix;
        $this->errorless  = $errorless;
    }

    protected function getView(): string {
        return 'components.wireui.input';
    }

    public function getInputClasses(bool $hasError = false): string {

        $defaultClasses = $this->getDefaultClasses();

        if ($this->prefix || $this->icon) {
            $defaultClasses .= ' pl-8';
        }

        if ($hasError) {
            $defaultClasses .= ' pr-8';
        }

        if ($hasError) {
            return "{$this->getErrorClasses()} {$defaultClasses}";
        }

        return "{$this->getDefaultColorClasses()} {$defaultClasses}";
    }

    protected function getErrorClasses(): string {

        return Str::of('text-red-900 placeholder-red-300')
            ->unless($this->borderless, function (Stringable $stringable) {
                return $stringable
                    ->append(' border border-red-300 focus:ring-red-500 focus:border-red-500');
            });
    }

    protected function getDefaultColorClasses(): string {

        return Str::of('placeholder-slate-400')
            ->unless($this->borderless, function (Stringable $stringable) {
                return $stringable
                    ->append(' border border-slate-300 focus:ring-cyan-400 focus:border-cyan-400');
            });
    }

    protected function getDefaultClasses(): string {

        return Str::of('block w-full text-sm sm:text-tiny py-1 sm:py-2 rounded-md transition ease-in-out duration-100 focus:outline-none read-only:bg-slate-50')
            ->unless($this->shadowless, fn (Stringable $stringable) => $stringable->append(' shadow-sm'))
            ->when($this->borderless, function (Stringable $stringable) {
                return $stringable->append(' border-transparent focus:border-transparent focus:ring-transparent');
            });
    }
}
