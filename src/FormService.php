<?php

namespace NetoJose\Bootstrap4Forms;

/**
 * FormService class
 *
 * @author neto
 */
class FormService {

    private $_builder;
    private $_render;
    private $_allowedRenders = ['open', 'close', 'file', 'text', 'email', 'number', 'hidden', 'select', 'checkbox', 'radio', 'textarea', 'button', 'submit', 'anchor', 'reset'];

    public function __construct()
    {
        $this->_builder = new FormBuilder;
    }

    public function __toString()
    {
        $output = '';

        if (in_array($this->_render, $this->_allowedRenders)) {

            $output = $this->_builder->{$this->_render}();
        }

        $this->_render = null;

        $this->_builder->resetFlags();

        return $output;
    }

    public function open(): FormService
    {
        return $this->render('open');
    }

    public function close(): FormService
    {
        return $this->render('close');
    }

    public function idPrefix(string $prefix = ''): FormService
    {
        return $this->_set('FidPrefix', $prefix);
    }

    public function multipart(bool $multipart = true): FormService
    {
        return $this->_set('Fmultipart', $multipart);
    }

    public function method(string $method): FormService
    {
        return $this->_set('Fmethod', $method);
    }

    public function get(): FormService
    {
        return $this->method('get');
    }

    public function post(): FormService
    {
        return $this->method('post');
    }

    public function put(): FormService
    {
        return $this->method('put');
    }

    public function patch(): FormService
    {
        return $this->method('patch');
    }

    public function delete(): FormService
    {
        return $this->method('delete');
    }

    public function fill($data): FormService
    {

        if (method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        if (!is_array($data)) {
            $data = [];
        }

        return $this->_set('Fdata', $data);
    }

    public function locale(string $path): FormService
    {
        return $this->_set('Flocale', $path);
    }

    public function inline(bool $inline = true): FormService
    {
        return $this->_set('checkInline', $inline);
    }

    public function url(string $url): FormService
    {
        return $this->_set('url', url($url));
    }

    public function route(string $route, array $params = []): FormService
    {
        return $this->_set('url', route($route, $params));
    }

    public function fieldsetOpen(string $legend = null): FormService
    {
        return $this->_set('meta', ['legend' => $legend])->render('fieldsetOpen');
    }

    public function fieldsetClose(): FormService
    {
        return $this->render('fieldsetClose');
    }

    public function help(string $text): FormService
    {
        return $this->_set('help', $text);
    }

    public function file(string $name = null, string $label = null): FormService
    {
        return $this->name($name)->label($label)->type('file');
    }

    public function text(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('text')->name($name)->label($label)->value($default);
    }

    public function hidden(string $name = null, string $default = null): FormService
    {
        return $this->name($name)->value($default)->type('hidden');
    }

    public function select(string $name = null, string $label = null, $options = [], $default = null): FormService
    {
        return $this->name($name)->label($label)->options($options)->value($default)->type('select');
    }

    public function options(array $options = []): FormService
    {
        $items = is_iterable($options) ? $options : [0 => 'Must be iterable'];
        return $this->_set('options', $items);
    }

    public function checkbox(string $name = null, string $label = null, string $value = null, string $default = null): FormService
    {
        return $this->_checkboxRadio('checkbox', $name, $label, $value, $default);
    }

    public function radio(string $name = null, string $label = null, string $value = null, string $default = null): FormService
    {
        return $this->_checkboxRadio('radio', $name, $label, $value, $default);
    }

    public function textarea(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('textarea')->name($name)->value($default)->label($label);
    }

    public function label($label): FormService
    {
        return $this->_set('label', $label);
    }

    public function button(string $value = null): FormService
    {
        return $this->type('button')->value($value);
    }

    public function submit(string $value): FormService
    {
        return $this->button($value)->type('submit');
    }

    public function reset(string $value): FormService
    {
        return $this->type('reset')->button($value);
    }

    public function anchor(string $value, $url = null): FormService
    {
        if ($url) {
            $this->url($url);
        }

        return $this->button($value)->type('anchor');
    }

    public function checked(bool $checked = true): FormService
    {
        $type = $this->_builder->get('type');
        $meta = $this->_builder->get('meta');

        if ($type === 'radio' && $checked) {
            $checked = $meta['value'];
        }

        return $this->value($checked);
    }

    public function value($value = null): FormService
    {
        if ($value !== null) {
            return $this->_set('value', $value);
        }

        return $this;
    }

    public function type($type): FormService
    {
        return $this->_set('type', $type)->render($type);
    }

    public function render(string $render): FormService
    {
        $this->_render = $render;

        return $this;
    }

    public function id($id): FormService
    {
        return $this->_set('id', $id);
    }

    public function name($name): FormService
    {
        return $this->_set('name', $name);
    }

    public function lg(): FormService
    {
        return $this->_set('size', 'lg');
    }

    public function sm(): FormService
    {
        return $this->_set('size', 'sm');
    }

    public function primary(): FormService
    {
        return $this->_set('color', 'primary');
    }

    public function secondary(): FormService
    {
        return $this->_set('color', 'secondary');
    }

    public function success(): FormService
    {
        return $this->_set('color', 'success');
    }

    public function danger(): FormService
    {
        return $this->_set('color', 'danger');
    }

    public function warning(): FormService
    {
        return $this->_set('color', 'warning');
    }

    public function info(): FormService
    {
        return $this->_set('color', 'info');
    }

    public function light(): FormService
    {
        return $this->_set('color', 'light');
    }

    public function dark(): FormService
    {
        return $this->_set('color', 'dark');
    }

    public function link(): FormService
    {
        return $this->_set('color', 'link');
    }

    public function outline(bool $outline = true): FormService
    {
        return $this->_set('outline', $outline);
    }

    public function block(bool $block = true): FormService
    {
        return $this->_set('block', $block);
    }

    public function readonly($status = true): FormService
    {
        return $this->_set('readonly', $status);
    }

    public function disabled($status = true): FormService
    {
        return $this->_set('disabled', $status);
    }

    public function placeholder($placeholder): FormService
    {
        return $this->_set('placeholder', $placeholder);
    }

    public function attrs(array $attrs = []): FormService
    {
        return $this->_set('attrs', $attrs);
    }

    public function multiple(bool $multiple = true): FormService
    {
        return $this->_set('multiple', $multiple);
    }

    private function _set($attr, $value): FormService
    {
        $this->_builder->set($attr, $value);

        return $this;
    }

    private function _checkboxRadio($type, $name, $label, $value, $default): FormService
    {
        $inputValue = $value === null ? $name : $value;

        if ($default) {
            $default = $inputValue;
        }

        return $this->_set('meta', ['value' => $inputValue])->type($type)->name($name)->label($label)->value($default);
    }

}
