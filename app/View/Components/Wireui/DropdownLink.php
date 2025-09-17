<?php

namespace App\View\Components\Wireui;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class DropdownLink extends Component {

    public string $label;
    public string $icon;
    public string $color;
    public string $href;
    public string $target;
    public string $tag;

    public function __construct($label='', $icon='', $color='blue', $href='', $target='') {
        $this->label = $label;
        $this->icon = $icon;
        $this->color = $color;
        $this->href = $href;
        $this->target = $target;
        $this->tag = $href ? 'a' : 'button';
    }

    public function render() {
        return function (array $data) {
            return view('components.wireui.dropdown-link', $this->mergeData($data))->render();
        };
    }
    public function mergeData(array $data ){
        $attributes         = $data['attributes'];
        $attributes         = $this->getDefaultClasses($attributes);
        $attributes         = $this->getDefaultAttributes($attributes);
        $data['attributes'] = $attributes;
        return $data;
    }

    public function getDefaultClasses(ComponentAttributeBag $attributes): ComponentAttributeBag {
        return $attributes->class([
            'px-4 py-1 flex items-center block w-full',
        ]);
    }

    public function getDefaultAttributes(ComponentAttributeBag $attributes): ComponentAttributeBag{
        if(!$this->href){
            return $attributes->merge([
                'wire:loading.attr'  => 'disabled',
                'wire:loading.class' => 'cursor-wait',
                'wire:target' => $this->getTarget($attributes),
            ]);
        }
        return $attributes->merge([
            'href' => $this->href,
            'target' => $this->target
        ]);
    }

    private function getTarget(ComponentAttributeBag $attributes){
        return $attributes->wire('click')->value ? $attributes->wire('click')->value . ',' . $this->target : $this->target;
    }
}
