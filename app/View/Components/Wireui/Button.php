<?php

namespace App\View\Components\Wireui;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class Button extends Component {

    public bool $load;
    public string $text;
    public string $textLoad;
    public string $target;
    public string $disabledTarget;
    public string $href;
    public string $tag;
    public bool $isDisabled;
    public string $icon;

    public function __construct(
        bool $load          = false,
        string $text        = '',
        string $textLoad    = '',
        string $target      = '',
        string $href      = '',
        string $disabledTarget = '',
        bool $isDisabled = false,
        string $icon = ''
        ){
        $this->load     = $load;
        $this->text     = $text;
        $this->textLoad = $textLoad ? $textLoad : $text;
        $this->target   = $target;
        $this->disabledTarget = $disabledTarget;
        $this->href     = $href;
        $this->tag = $href ? 'a' : 'button';
        $this->isDisabled = $isDisabled;
        $this->icon = $icon;
    }

    public function render() {
        return function (array $data) {
            return view('components.wireui.button', $this->mergeData($data))->render();
        };
    }

    public function getIconLoad($target){
        return <<<EOT
            <svg wire:target="{{ '$target' }}" wire:loading class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        EOT;
    }

    public function mergeData(array $data ){
        $attributes         = $data['attributes'];
        $attributes         = $this->getDefaultClasses($attributes);
        $attributes         = $this->getDefaultAttributes($attributes);
        $data['attributes'] = $attributes;
        return $data;
    }

    private function getDefaultClasses(ComponentAttributeBag $attributes): ComponentAttributeBag {
        return $attributes->class([
            'inline-flex items-center border border-transparent leading-6 font-medium rounded-md text-white transition ease-in-out duration-150',
            $this->getSize($attributes),
            $this->getColor($attributes),
            'disabled:opacity-60'
        ]);
    }

    private function getDefaultAttributes(ComponentAttributeBag $attributes): ComponentAttributeBag{
        if(!$this->href){
            if($this->isDisabled) return $attributes;
            return $attributes->merge([
                'wire:loading.attr'  => 'disabled',
                'wire:loading.class' => 'cursor-wait',
                'wire:target' => $this->getTarget($attributes),
            ]);
        }

        return $attributes->merge(['href' => $this->href]);
    }

    private function getTarget(ComponentAttributeBag $attributes){
        return $attributes->wire('click')->value ? $attributes->wire('click')->value . ',' . $this->target : $this->target;
    }

    private function getSize(ComponentAttributeBag $attributes){
        return $this->modifierClasses($attributes, $this->sizes());
    }

    private function getColor(ComponentAttributeBag $attributes){
        return $this->modifierClasses($attributes, $this->defaultColors());
    }

    private function modifierClasses(ComponentAttributeBag $attributes, array $modifiers): string {
        $modifier = $this->findModifier($attributes, $modifiers);
        return $modifiers[$modifier];
    }

    private function findModifier(ComponentAttributeBag $attributes, array $modifiers): string {
        $keys      = collect($modifiers)->keys()->except('default')->toArray();
        $modifiers = $attributes->only($keys)->getAttributes();
        $modifier  = collect($modifiers)->filter()->keys()->first();

        return $modifier ?? 'default';
    }

    public function defaultColors() : array{
        return [
            'default' => <<<EOT
            bg-blue-500 hover:bg-blue-600 hover:ring-blue-500
            EOT,

            'secondary' => <<<EOT
                bg-slate-500 hover:bg-slate-400 hover:ring-slate-500
            EOT,

            'success' => <<<EOT
                bg-green-500 hover:bg-green-400 hover:ring-green-500
            EOT,

            'danger' => <<<EOT
                bg-red-500 hover:bg-red-400 hover:ring-red-500
            EOT,
        ];
    }

    public function sizes(): array
    {
        return [
            'xs'          => 'text-xs px-2.5 py-0.5',
            'sm'          => 'text-xs leading-4 px-3 py-1',
            'default'          => 'text-xs sm:text-sm px-4 py-1 sm:py-1.5',
            'md'     => 'text-base px-4 py-1.5 font-semibold',
            'lg'          => 'text-base px-6 py-3 font-semibold',
            'xl'          => 'text-base px-7 py-4 font-semibold',
        ];
    }


}
