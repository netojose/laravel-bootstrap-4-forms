<?php

namespace NetoJose\Bootstrap4Forms;

use Illuminate\Support\ViewErrorBag;

class FormBuilder
{

    private $_attrs = [];

    public function set($key, $value)
    {
        $formatter = '_format' . ucfirst($key);
        if (method_exists($this, $formatter)) {
            $value = $this->$formatter($value);
        }
        $this->_attrs[$key] = $value;
    }

    private function _formatFormData($value)
    {
        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }
        return $value;
    }

    public function render(): string
    {
        $render = $this->_attrs['render'];
        $methodName = '_render' . ucfirst($render);
        $output = $this->$methodName();
        $this->_resetAttributes();
        return $output;
    }

    private function _renderFormOpen(): string
    {
        extract($this->_get('method', 'url', 'formMultipart', 'formInline', 'autocomplete'));

        if (!$method) {
            $method = 'post';
        }

        $enctype = $formMultipart ? 'multipart/form-data' : null;

        $attrs = $this->_buildHtmlAttrs([
            'method' => $method,
            'action' => $url,
            'enctype' => $enctype,
            'autocomplete' => $autocomplete,
            'class' => $formInline ? 'form-inline' : null,
        ]);

        $output = '<form ' . $attrs . '>';

        if ($method !== 'get') {
            $output .= csrf_field();
            if ($method !== 'post') {
                $output .= method_field($method);
            }
        }

        return $output;
    }

    private function _renderFormClose(): string
    {
        $this->_resetAttributes(true);
        return '</form>';
    }

    private function _renderFieldsetOpen(): string
    {
        $output = '<fieldset>';
        extract($this->_get('legend'));

        if ($legend) {
            $output .= '<legend>' . $this->_getText($legend) . '</legend>';
        }

        return $output;
    }

    private function _renderFieldsetClose(): string
    {
        return '</fieldset>';
    }

    private function _renderErrors(): string
    {
        $errors = $this->errors()->all();
        if (count($errors) < 1) {
            return '';
        }

        extract($this->_get('errorsHeader'));

        $output = '<div class="alert alert-danger" role="alert"><ul class="list-unstyled">';
        if ($errorsHeader) {
            $output .= '<h4 class="alert-heading">' . $errorsHeader . '</h4>';
        }
        foreach ($errors as $error) {
            $output .= '<li>' . $error . '</li>';
        }
        return $output . '</ul></div>';
    }

    private function _renderInput(): string
    {
        $attributes = $this->getInputAttributes();
        $attrs = $this->_buildHtmlAttrs($attributes);
        return $this->_wrapperInput('<input ' . $attrs . '>');
    }

    private function _renderSelect(): string
    {
        extract($this->_get('options'));

        $fieldValue = $this->_getValue();
        $arrValues = is_array($fieldValue) ? $fieldValue : [$fieldValue];
        $optionsList = '';
        foreach ($options as $value => $label) {
            $attrs = $this->_buildHtmlAttrs(['value' => $value, 'selected' => in_array($value, $arrValues)], false);
            $optionsList .= '<option ' . $attrs . '>' . $label . '</option>';
        }

        $attributes = $this->getInputAttributes();
        $attrs = $this->_buildHtmlAttrs($attributes);
        return $this->_wrapperInput('<select ' . $attrs . '>' . $optionsList . '</select>');
    }

    private function _renderTextarea(): string
    {
        $attributes = $this->getInputAttributes();
        $value = $attributes['value'];
        unset($attributes['value']);
        $attrs = $this->_buildHtmlAttrs($attributes);
        return $this->_wrapperInput('<textarea ' . $attrs . '>' . htmlspecialchars($value) . '</textarea>');
    }

    private function _renderCheckbox(): string
    {
        $attributes = $this->getInputAttributes();
        $attrs = $this->_buildHtmlAttrs($attributes);
        return $this->_wrapperRadioCheckbox('<input ' . $attrs . '>');
    }

    private function _renderRadio(): string
    {
        $attributes = $this->getInputAttributes();
        $attrs = $this->_buildHtmlAttrs($attributes);
        return $this->_wrapperRadioCheckbox('<input ' . $attrs . '>');
    }

    private function _renderAnchor(): string
    {
        extract($this->_get('url', 'value'));
        $class = $this->getBtnAnchorClasses();
        $attrs = $this->_buildHtmlAttrs(['href' => $url, 'class' => $class]);
        return '<a ' . $attrs . '>' . $value . '</a>';
    }

    private function _renderButton(): string
    {
        extract($this->_get('type', 'value', 'disabled'));
        $class = $this->getBtnAnchorClasses();
        $attrs = $this->_buildHtmlAttrs(['type' => $type, 'class' => $class, 'disabled' => $disabled]);
        return '<button ' . $attrs . '>' . $value . '</button>';
    }

    private function getBtnAnchorClasses()
    {
        extract($this->_get('size', 'color', 'outline', 'block', 'type', 'value', 'formInline'));
        return $this->createAttrsList(
            'btn',
            [$size, 'btn-' . $size],
            [$color, 'btn-' . ($outline ? 'outline-' : '') . $color],
            [$block, 'btn-block'],
            [$formInline, 'mx-sm-2']
        );
    }

    private function isRadioOrCheckbox(): bool
    {
        extract($this->_get('render'));
        return in_array($render, ['checkbox', 'radio']);
    }

    private function getInputAttributes(): array
    {
        extract($this->_get('render', 'type', 'multiple', 'name', 'size', 'placeholder', 'help', 'disabled', 'readonly', 'required', 'autocomplete', 'min', 'max', 'checked'));

        $isRadioOrCheckbox = $this->isRadioOrCheckbox();
        $type = $isRadioOrCheckbox ? $render : $type;

        $class = 'form-check-input';
        if (!$isRadioOrCheckbox) {
            $class = 'form-control';
            switch ($type) {
                case 'file':
                    $class .= '-file';
                    break;
                case 'range':
                    $class .= '-range';
                    break;
            }

            if ($size) {
                $class .= ' form-control-' . $size;
            }
        }

        $id = $this->_getId();

        if ($this->errors()->has($name)) {
            $class .= ' is-invalid';
        }

        $attributes = [
            'type' => $type,
            'name' => $name,
            'id' => $id
        ];

        // If the field is a hidden field, we don't need add more attributes
        if ($type === 'hidden') {
            return $attributes;
        }

        if ($render !== 'select') {
            $attributes['value'] = $this->_getValue();
        } else {
            $attributes['multiple'] = $multiple;
        }

        return array_merge($attributes, [
            'class' => $class,
            'min' => $min,
            'max' => $max,
            'autocomplete' => $autocomplete,
            'placeholder' => $this->_getText($placeholder),
            'aria-describedby' => $help ? 'help-' . $id : null,
            'checked' => $checked,
            'disabled' => $disabled,
            'readonly' => $readonly,
            'required' => $required
        ]);
    }

    private function renderLabel(): string
    {
        extract($this->_get('label', 'formInline', 'render'));

        $class = in_array($render, ['checkbox', 'radio']) ? 'form-check-label' : '';
        if ($formInline) {
            $class = join(' ', [$class, 'mx-sm-2']);
        }

        $id = $this->_getId();
        $attrs = $this->_buildHtmlAttrs([
            'for' => $id,
            'class' => $class
        ]);
        return '<label ' . $attrs . '>' . $this->_getText($label) . '</label>';
    }

    private function _getText($key)
    {
        extract($this->_get('formLocale'));
        if ($formLocale) {
            return __($formLocale . '.' . $key);
        }
        return $key;
    }

    private function _resetAttributes($resetAll = false)
    {
        // Remove all attributes
        if ($resetAll) {
            $this->_attrs = [];
            return;
        }

        // Keep attributes which key starting with 'form'
        $this->_attrs = array_filter($this->_attrs, function ($key) {
            return substr($key, 0, 4) === 'form';
        }, ARRAY_FILTER_USE_KEY);
    }

    private function _wrapperInput(string $input): string
    {
        extract($this->_get('type', 'help', 'wrapperAttrs', 'formInline', 'name'));

        if ($type === 'hidden') {
            return $input;
        }

        $id             = $this->_getId();
        $label          = $this->renderLabel();
        $helpText       = $help ? '<small id="help-' . $id . '" class="form-text text-muted">' . $this->_getText($help) . '</small>' : '';
        $error          = $this->getInputErrorMarkup($name);
        $attrs          = $wrapperAttrs ?? [];
        $attrs['class'] = $this->createAttrsList(
            $attrs['class'] ?? null,
            $formInline ? 'input-group' : 'form-group'
        );
        $attributes = $this->_buildHtmlAttrs($attrs, false);

        return '<div ' . $attributes . '>' . $label . $input . $helpText . $error . '</div>';
    }

    private function _wrapperRadioCheckbox(string $input): string
    {
        extract($this->_get('inline', 'name'));

        $class = $this->createAttrsList('form-check', [$inline, 'form-check-inline']);
        $label = $this->renderLabel();
        $error = $this->getInputErrorMarkup($name);
        return '
        <div class="' . $class . '">
            ' . $input . '
            ' . $label . '
            ' . $error . '
        </div>';
    }

    private function getInputErrorMarkup(string $name): string
    {
        return '
        <div class="invalid-feedback">
            ' . $this->errors()->first($name) . '
        </div>';
    }

    private function _getId()
    {
        extract($this->_get('id', 'name', 'formIdPrefix', 'render', 'value'));

        if ($id) {
            return $id;
        }

        return ($formIdPrefix ?? 'inp-') . $name . ($render === 'radio' ? '-' . $value : '');
    }

    private function _getValue()
    {
        extract($this->_get('name', 'value', 'formData'));
        return old($name, $value) ?? ($formData[$name] ?? null);
    }

    private function _buildHtmlAttrs(array $attributes, $appendAttrs = true): string
    {

        if ($appendAttrs) {
            extract($this->_get('attrs'));
            $fieldAttrs = $attrs ?? [];
            $attributes['class'] = $this->createAttrsList($attributes['class'] ?? null, $fieldAttrs['class'] ?? null);
            $attributes = array_merge($fieldAttrs, $attributes);
        }

        return join(' ', array_filter(
            array_map(function ($key) use ($attributes) {
                $value = $attributes[$key];
                if (is_bool($value)) {
                    return $value ? $key : '';
                } elseif ($value !== null) {
                    return $key . '="' . htmlspecialchars($value) . '"';
                }
                return '';
            }, array_keys($attributes))
        ));
    }

    private function createAttrsList(...$items)
    {
        $attrs = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $item = $item[0] ? $item[1] : null;
            }
            $attrs[] = $item;
        }
        return join(' ', array_filter($attrs));
    }

    private function errors(): ViewErrorBag
    {
        return session('errors', app(ViewErrorBag::class));
    }

    private function _get(...$keys): array
    {
        $return = [];
        foreach ($keys as $key) {
            $return[$key] = $this->_attrs[$key] ?? null;
        }
        return $return;
    }
}
