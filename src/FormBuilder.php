<?php

namespace NetoJose\Bootstrap4Forms;

use Illuminate\Support\ViewErrorBag;

class FormBuilder
{

    private $_attrs = [];

    public function set($key, $value)
    {
        $formatter = 'format' . ucfirst($key);
        if (method_exists($this, $formatter)) {
            $value = $this->$formatter($value);
        }
        $this->attrs[$key] = $value;
    }

    protected function formatMethod($value)
    {
        return strtolower($value);
    }

    protected function formatFormData($value)
    {
        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }
        return $value;
    }

    protected function formatOptions($value)
    {
        extract($this->get('optionIdKey', 'optionValueKey'));

        $idKey = $optionIdKey ?? 'id';
        $valueKey = $optionValueKey ?? 'name';

        $options = [];
        foreach ($value as $key => $item) {
            if (is_object($item)) {
                $options[$item->{$idKey}] = $item->{$valueKey};
                continue;
            }
            $options[$key] = $item;
        }
        return $options;
    }

    public function render(): string
    {
        $render = $this->attrs['render'];
        $methodName = 'render' . ucfirst($render);
        $output = $this->$methodName();
        $this->resetAttributes();
        return $output;
    }

    protected function renderFormOpen(): string
    {
        extract($this->get('id', 'method', 'url', 'formMultipart', 'formInline', 'autocomplete'));

        if (!$method) {
            $method = 'post';
        }

        $enctype = $formMultipart ? 'multipart/form-data' : null;

        $attrs = $this->buildHtmlAttrs([
            'method' => in_array($method, ['get', 'post']) ? $method : 'post',
            'action' => $url,
            'enctype' => $enctype,
            'autocomplete' => $autocomplete,
            'class' => $formInline ? 'form-inline' : null,
            'id' => $id
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

    protected function renderFormClose(): string
    {
        $this->resetAttributes(true);
        return '</form>';
    }

    protected function renderFieldsetOpen(): string
    {
        $output = '<fieldset>';
        extract($this->get('legend'));

        if ($legend) {
            $output .= '<legend>' . $this->getText($legend) . '</legend>';
        }

        return $output;
    }

    protected function renderFieldsetClose(): string
    {
        return '</fieldset>';
    }

    protected function renderErrors(): string
    {
        $errors = $this->errors()->all();
        if (count($errors) < 1) {
            return '';
        }

        extract($this->get('errorsHeader', 'id'));
        $attrs = $this->buildHtmlAttrs(['class' => 'alert alert-danger', 'role' => 'alert', 'id' => $id]);
        $output = '<div ' . $attrs . '><ul class="list-unstyled">';
        if ($errorsHeader) {
            $output .= '<h4 class="alert-heading">' . $this->getText($errorsHeader) . '</h4>';
        }
        foreach ($errors as $error) {
            $output .= '<li>' . $error . '</li>';
        }
        return $output . '</ul></div>';
    }

    protected function renderInput(): string
    {
        $attributes = $this->getInputAttributes();
        $attrs = $this->buildHtmlAttrs($attributes);
        return $this->wrapperInput('<input ' . $attrs . '>');
    }

    protected function renderSelect(): string
    {
        extract($this->get('options'));

        $fieldValue = $this->getValue();
        $arrValues = is_array($fieldValue) ? $fieldValue : [$fieldValue];
        $optionsList = '';
        foreach ($options as $value => $label) {
            $attrs = $this->buildHtmlAttrs(['value' => $value, 'selected' => in_array($value, $arrValues)], false);
            $optionsList .= '<option ' . $attrs . '>' . $label . '</option>';
        }

        $attributes = $this->getInputAttributes();
        $attrs = $this->buildHtmlAttrs($attributes);
        return $this->wrapperInput('<select ' . $attrs . '>' . $optionsList . '</select>');
    }

    protected function renderTextarea(): string
    {
        $attributes = $this->getInputAttributes();
        $value = $attributes['value'];
        unset($attributes['value']);
        $attrs = $this->buildHtmlAttrs($attributes);
        return $this->wrapperInput('<textarea ' . $attrs . '>' . htmlspecialchars($value) . '</textarea>');
    }

    protected function renderCheckbox(): string
    {
        $attributes = $this->getInputAttributes();
        $attrs = $this->buildHtmlAttrs($attributes);
        return $this->wrapperRadioCheckbox('<input ' . $attrs . '>');
    }

    protected function renderRadio(): string
    {
        $attributes = $this->getInputAttributes();
        $attrs = $this->buildHtmlAttrs($attributes);
        return $this->wrapperRadioCheckbox('<input ' . $attrs . '>');
    }

    protected function renderAnchor(): string
    {
        extract($this->get('url', 'value'));
        $class = $this->getBtnAnchorClasses();
        $attrs = $this->buildHtmlAttrs(['href' => $url, 'class' => $class]);
        return '<a ' . $attrs . '>' . $value . '</a>';
    }

    protected function renderButton(): string
    {
        extract($this->get('type', 'value', 'disabled'));
        $class = $this->getBtnAnchorClasses();
        $attrs = $this->buildHtmlAttrs(['type' => $type, 'class' => $class, 'disabled' => $disabled]);
        return '<button ' . $attrs . '>' . $value . '</button>';
    }

    protected function getBtnAnchorClasses()
    {
        extract($this->get('size', 'color', 'outline', 'block', 'type', 'value', 'formInline'));
        return $this->createAttrsList(
            'btn',
            [$size, 'btn-' . $size],
            [$color, 'btn-' . ($outline ? 'outline-' : '') . $color],
            [$block, 'btn-block'],
            [$formInline, 'mx-sm-2']
        );
    }

    protected function isRadioOrCheckbox(): bool
    {
        extract($this->get('render'));
        return in_array($render, ['checkbox', 'radio']);
    }

    protected function getInputAttributes(): array
    {
        extract($this->get('render', 'type', 'multiple', 'name', 'size', 'placeholder', 'help', 'disabled', 'readonly', 'required', 'autocomplete', 'min', 'max', 'value', 'checked', 'formData', 'disableValidation'));

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

        $id = $this->getId();

        if (!$disableValidation && $this->errors()->count() > 0) {
            $class .= $this->errors()->has($name) ? ' is-invalid' : ' is-valid';
        }

        $attributes = [
            'type' => $type,
            'name' => $name,
            'id' => $id
        ];

        if ($render !== 'select') {
            $attributes['value'] = $this->getValue();
        } else {
            $attributes['multiple'] = $multiple;
        }

        // If the field is a hidden field, we don't need add more attributes
        if ($type === 'hidden') {
            return $attributes;
        }

        if ($this->isRadioOrCheckbox()) {
            if ($this->hasOldInput()) {
                $isChecked = old($name) === $value;
            } else {
                $value = $value === 'on' ? true : false;
                $isChecked = isset($formData[$name]) ? $formData[$name] === $value : $checked;
            }
            $attributes['checked'] = $isChecked;
        }

        return array_merge($attributes, [
            'class' => $class,
            'min' => $min,
            'max' => $max,
            'autocomplete' => $autocomplete,
            'placeholder' => $this->getText($placeholder),
            'aria-describedby' => $help ? 'help-' . $id : null,
            'disabled' => $disabled,
            'readonly' => $readonly,
            'required' => $required
        ]);
    }

    protected function renderLabel(): string
    {
        extract($this->get('label', 'formInline', 'render'));

        $class = in_array($render, ['checkbox', 'radio']) ? 'form-check-label' : '';
        if ($formInline) {
            $class = join(' ', [$class, 'mx-sm-2']);
        }

        $id = $this->getId();
        $attrs = $this->buildHtmlAttrs([
            'for' => $id,
            'class' => $class
        ], false);
        return '<label ' . $attrs . '>' . $this->getText($label) . '</label>';
    }

    protected function getText($key)
    {
        extract($this->get('formLocale'));
        if ($formLocale) {
            return __($formLocale . '.' . $key);
        }
        return $key;
    }

    protected function resetAttributes($resetAll = false)
    {
        // Remove all attributes
        if ($resetAll) {
            $this->attrs = [];
            return;
        }

        // Keep attributes which key starting with 'form'
        $this->attrs = array_filter($this->attrs, function ($key) {
            return substr($key, 0, 4) === 'form';
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function wrapperInput(string $input): string
    {
        extract($this->get('type', 'help', 'wrapperAttrs', 'formInline', 'name'));

        if ($type === 'hidden') {
            return $input;
        }

        $id             = $this->getId();
        $label          = $this->renderLabel();
        $helpText       = $help ? '<small id="help-' . $id . '" class="form-text text-muted">' . $this->getText($help) . '</small>' : '';
        $error          = $this->getInputErrorMarkup($name);
        $attrs          = $wrapperAttrs ?? [];
        $attrs['class'] = $this->createAttrsList(
            $attrs['class'] ?? null,
            $formInline ? 'input-group' : 'form-group'
        );
        $attributes = $this->buildHtmlAttrs($attrs, false);

        return '<div ' . $attributes . '>' . $label . $input . $helpText . $error . '</div>';
    }

    protected function wrapperRadioCheckbox(string $input): string
    {
        extract($this->get('inline', 'name', 'wrapperAttrs'));

        $attrs = $wrapperAttrs ?? [];
        $attrs['class'] = $this->createAttrsList(
            'form-check',
            [$inline, 'form-check-inline'],
            $attrs['class'] ?? null
        );
        $attributes = $this->buildHtmlAttrs($attrs, false);
        $label = $this->renderLabel();
        $error = $this->getInputErrorMarkup($name);
        return '<div ' . $attributes . '>' . $input . $label . $error . '</div>';
    }

    protected function getInputErrorMarkup(string $name): string
    {
        extract($this->get('disableValidation'));

        if ($disableValidation) {
            return '';
        }

        $error = $this->errors()->first($name);
        if (!$error) {
            return '';
        }
        return '<div class="invalid-feedback">' . $this->errors()->first($name) . '</div>';
    }

    protected function getId()
    {
        extract($this->get('id', 'name', 'formIdPrefix', 'render', 'value'));

        if ($id) {
            return $id;
        }

        return ($formIdPrefix ?? 'inp-') . $name . ($render === 'radio' ? '-' . $value : '');
    }

    protected function hasOldInput()
    {
        return count((array) old()) != 0;
    }

    protected function getValue()
    {
        extract($this->get('name', 'value', 'formData'));
        if ($this->isRadioOrCheckbox()) {
            return $value;
        }

        if ($this->hasOldInput()) {
            return old(preg_replace("/\\[\\]/mui","",$name), $value);
        }

        $fromFill = $formData[$name] ?? null;

        return $value ?? $fromFill;
    }

    protected function buildHtmlAttrs(array $attributes, $appendAttrs = true): string
    {

        if ($appendAttrs) {
            extract($this->get('attrs'));
            $fieldAttrs = $attrs ?? [];
            $class = $this->createAttrsList($attributes['class'] ?? null, $fieldAttrs['class'] ?? null);
            if ($class) {
                $attributes['class'] = $class;
            }
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

    protected function createAttrsList(...$items)
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

    protected function errors()
    {
        $errors = session('errors', app(ViewErrorBag::class));
        extract($this->get('formErrorBag'));
        if ($formErrorBag) {
            $errors = $errors->{$formErrorBag};
        }
        return $errors;
    }

    protected function get(...$keys): array
    {
        $return = [];
        foreach ($keys as $key) {
            $return[$key] = $this->attrs[$key] ?? null;
        }
        return $return;
    }
}
