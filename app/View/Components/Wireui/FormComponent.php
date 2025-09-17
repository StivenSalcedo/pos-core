<?php

namespace App\View\Components\Wireui;

use Illuminate\View\Component;

abstract class FormComponent extends Component {

    abstract protected function getView(): string;

    public function render() {

        return function (array $data) {
            return view($this->getView(), $this->mergeAttributes($data))->render();
        };

    }

    protected function sharedAttributes(): array {

        return ['id', 'name', 'disabled', 'readonly'];
    }

    protected function mergeAttributes(array $data): array {
        $attributes = $data['attributes'];

        $model = $attributes->wire('model')->value();

        if (!$attributes->has('name') && $model) {
            $attributes->offsetSet('name', $model);
        }

        if (!$attributes->has('id') && $model) {
            $attributes->offsetSet('id', md5($model));
        }

        foreach ($this->sharedAttributes() as $attribute) {
            $value = property_exists($this, $attribute) ? data_get($this, $attribute) : $attributes->get($attribute);

            $data[$attribute] = $value;
            $attributes->offsetSet($attribute, $value);
        }

        return $data;
    }

}
