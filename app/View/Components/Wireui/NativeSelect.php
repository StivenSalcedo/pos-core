<?php

namespace App\View\Components\Wireui;

use Illuminate\View\Component;
use Exception;
use Illuminate\Support\Collection;

class NativeSelect extends FormComponent {

    public const PRIMITIVE_VALUES = [
        'string',
        'integer',
        'double',
        'boolean',
        'NULL',
    ];

    public Collection $options;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $placeholder = null,
        public ?string $optionValue = null,
        public ?string $optionLabel = null,
        public ?string $optionDescription = null,
        public bool $flipOptions = false,
        public bool $optionKeyValue = false,
        Collection|array|null $options = null,
    ) {
        $this->options = collect($options)->when(
            $flipOptions,
            fn (Collection $collection) => $collection->flip()
        );

        $this->validateConfig();
    }

    /**
     * Validate if the select options is set correctly.
     * @return void
     * @throws Exception
     */
    private function validateConfig(): void
    {
        if (($this->optionValue && !$this->optionLabel) || (!$this->optionValue && $this->optionLabel)) {
            throw new Exception('The {option-value} and {option-label} attributes must be set together.');
        }

        if ($this->flipOptions && ($this->optionValue || $this->optionLabel)) {
            throw new Exception('The {flip-options} attribute cannot be used with {option-value} and {option-label} attributes.');
        }

        if (
            !($this->optionValue && $this->optionLabel)
            && $this->options->isNotEmpty()
            && !in_array(gettype($this->options->first()), self::PRIMITIVE_VALUES, true)
        ) {
            throw new Exception(
                'Inform the {option-value} and {option-label} to use array, model, or object option.'
                    . ' <x-select [...] option-value="id" option-label="name" />'
            );
        }

        if (
            ($this->optionValue && $this->optionLabel)
            && $this->options->isNotEmpty()
            && in_array(gettype($this->options->first()), self::PRIMITIVE_VALUES, true)
        ) {
            throw new Exception(
                'The {option-value} and {option-label} attributes cannot be used with primitive options values: '
                    . implode(', ', self::PRIMITIVE_VALUES)
            );
        }
    }

    protected function getView(): string
    {
        return 'components.wireui.native-select';
    }

    public function defaultClasses(): string
    {
        return 'block pl-3 pr-10 py-1.5 text-xs sm:text-sm shadow-sm
                rounded-md border bg-white focus:ring-1 focus:outline-none';
    }

    public function colorClasses(): string
    {
        return 'border-slate-300 focus:ring-cyan-500 focus:border-cyan-500';
    }

    public function errorClasses(): string
    {
        return 'border-red-400 focus:ring-red-500 focus:border-red-500 text-red-500';
    }

    public function getOptionValue(int|string $key, mixed $option): mixed
    {
        if ($this->optionKeyValue) {
            return $key;
        }

        return data_get($option, $this->optionValue);
    }

    public function getOptionLabel(mixed $option): ?string
    {
        $label = data_get($option, $this->optionLabel);

        if ($this->optionDescription || data_get($option, 'description')) {
            return "{$label} - {$this->getOptionDescription($option)}";
        }

        return $label;
    }

    public function getOptionDescription(mixed $option): ?string
    {
        if ($this->optionDescription) {
            return data_get($option, $this->optionDescription);
        }

        return data_get($option, 'description');
    }
}
