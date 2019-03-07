<?php

namespace NetoJose\Bootstrap4Forms;

/**
 * FormService class
 *
 * @author neto
 */
class FormService {

    /**
     * The Form builder instance
     *
     * @var FormBuilder
     */
    private $_builder;

    /**
     * Render to be used
     *
     * @var string
     */
    private $_render;

    /**
     * Allowed renders
     *
     * @var array
     */
    private $_allowedRenders = ['open', 'close', 'fieldsetOpen', 'fieldsetClose', 'file', 'text', 'range', 'password', 'date', 'time', 'email', 'tel', 'url', 'number', 'hidden', 'select', 'checkbox', 'radio', 'textarea', 'button', 'submit', 'anchor', 'reset'];

    /**
     * Create a new FormSevice instance
     */
    public function __construct()
    {
        $this->_builder = new FormBuilder;
    }

    /**
     * Magic method to return a class string version
     *
     * @return string
     */
    public function __toString()
    {
        $output = '';

        if (in_array($this->_render, $this->_allowedRenders)) {

            $output = $this->_builder->{$this->_render}();
        }

        $this->_render = null;

        return $output;
    }

    /**
     * Open the form
     *
     * @return FormService
     */
    public function open(): FormService
    {
        return $this->render('open');
    }

    /**
     * Close the form
     *
     * @return FormService
     */
    public function close(): FormService
    {
        return $this->render('close');
    }

    /**
     * Set a prefix id for all inputs
     *
     * @param string $prefix
     * @return FormService
     */
    public function idPrefix(string $prefix = ''): FormService
    {
        return $this->_set('FidPrefix', $prefix);
    }

    /**
     * Set multipart attribute for a form
     *
     * @param bool $multipart
     * @return FormService
     */
    public function multipart(bool $multipart = true): FormService
    {
        return $this->_set('Fmultipart', $multipart);
    }

    /**
     * Set a method attribute for the form
     *
     * @param string $method
     * @return FormService
     */
    public function method(string $method): FormService
    {
        return $this->_set('Fmethod', $method);
    }

    /**
     * Set get method for the form attribute
     *
     * @return FormService
     */
    public function get(): FormService
    {
        return $this->method('get');
    }

    /**
     * Set post method for the form attribute
     *
     * @return FormService
     */
    public function post(): FormService
    {
        return $this->method('post');
    }

    /**
     * Set put method for the form attribute
     *
     * @return FormService
     */
    public function put(): FormService
    {
        return $this->method('put');
    }

    /**
     * Set patch method for the form attribute
     *
     * @return FormService
     */
    public function patch(): FormService
    {
        return $this->method('patch');
    }

    /**
     * Set delete method for the form attribute
     *
     * @return FormService
     */
    public function delete(): FormService
    {
        return $this->method('delete');
    }

    /**
     * Fill the form values
     *
     * @param array|object $data
     * @return FormService
     */
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

    /**
     * Set locale file for inputs translations
     *
     * @param string $path
     * @return FormService
     */
    public function locale(string $path): FormService
    {
        return $this->_set('Flocale', $path);
    }

    /**
     * Set inline form to inline inputs
     * @param bool $inline
     * @return FormService
     */
    public function inlineForm(bool $inline = true): FormService
    {
    	if($inline) {
		    return $this->_set('FformStyle', 'inline');
	    }else{
    		return $this;
	    }
    }

	/**
	 * @param bool $wrapper
	 * @return FormService
	 */
	public function noWrapper(bool $wrapper = false)
    {
	    return $this->_set('wrapper', $wrapper);
    }

	/**
	 * Set horizontal form
	 * @param bool $horizontal
	 * @return FormService
	 */
	public function horizontalForm(bool $horizontal = true): FormService
    {
	    if($horizontal) {
		    return $this->_set('FformStyle', 'horizontal');
	    }else{
		    return $this;
	    }
    }

    /**
     * Set autocomplete value ('on', 'off', or one of the permitted values)
     *
     * If set on the form, only 'on' or 'off' are valid and are inherited by input fields.
     * The inherited value can be overridden on individual input fields
     *
     * See: https://html.spec.whatwg.org/multipage/form-control-infrastructure.html#autofill
     *
     * @param string $value
     * @return FormService
     */
    public function autocomplete(string $value = 'on'): FormService
    {
        if ($this->_render == "open") {
            return $this->_set('Fautocomplete', $value);
        }

        return $this->_set('autocomplete', $value);
    }

    /**
     * Set inline style for checkbox and radio inputs
     * @param bool $inline
     * @return FormService
     */
    public function inline(bool $inline = true): FormService
    {
        return $this->_set('checkInline', $inline);
    }

    /**
     * Set url for links and form action
     *
     * @param string $url
     * @return FormService
     */
    public function url(string $url): FormService
    {
        return $this->_set('url', url($url));
    }

    /**
     * Set route for links and form action
     *
     * @param string $route
     * @param array $params
     * @return FormService
     */
    public function route(string $route, array $params = []): FormService
    {
        return $this->_set('url', route($route, $params));
    }

    /**
     * Open a fieldset
     *
     * @param string $legend
     * @return FormService
     */
    public function fieldsetOpen(string $legend = null): FormService
    {
        return $this->_set('meta', ['legend' => $legend])->render('fieldsetOpen');
    }

    /**
     * Close a fieldset
     *
     * @return FormService
     */
    public function fieldsetClose(): FormService
    {
        return $this->render('fieldsetClose');
    }

    /**
     * Set a help text
     *
     * @param string $text
     * @return FormService
     */
    public function help(string $text): FormService
    {
        return $this->_set('help', $text);
    }

    /**
     * Create a file input
     *
     * @param string $name
     * @param string $label
     * @return FormService
     */
    public function file(string $name = null, string $label = null): FormService
    {
        return $this->name($name)->label($label)->type('file');
    }

    /**
     * Create a text input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return FormService
     */
    public function text(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('text')->name($name)->label($label)->value($default);
    }

    /**
     * Create a date input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return FormService
     */
    public function date(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('date')->name($name)->label($label)->value($default);
    }

    /**
     * Create a time input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return FormService
     */
    public function time(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('time')->name($name)->label($label)->value($default);
    }

    /**
     * Create a range input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return FormService
     */
    public function range(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('range')->name($name)->label($label)->value($default);
    }

    /**
     * Create a hidden input
     *
     * @param string $name
     * @param string $default
     * @return FormService
     */
    public function hidden(string $name = null, string $default = null): FormService
    {
        return $this->name($name)->value($default)->type('hidden');
    }

    /**
     * Create a select input
     *
     * @param string $name
     * @param string $label
     * @param array $options
     * @param string|array $default
     * @return FormService
     */
    public function select(string $name = null, string $label = null, $options = [], $default = null): FormService
    {
        return $this->name($name)->label($label)->options($options)->value($default)->type('select');
    }

    /**
     * Set options for a select field
     *
     * @param array $options
     * @return FormService
     */
    public function options(array $options = []): FormService
    {
        $items = is_iterable($options) ? $options : [0 => 'Must be iterable'];
        return $this->_set('options', $items);
    }

    /**
     * Create a checkbox input
     *
     * @param string $name
     * @param string $label
     * @param string $value
     * @param string $default
     * @return FormService
     */
    public function checkbox(string $name = null, string $label = null, string $value = null, string $default = null): FormService
    {
        return $this->_checkboxRadio('checkbox', $name, $label, $value, $default);
    }

    /**
     * Create a radio input
     *
     * @param string $name
     * @param string $label
     * @param string $value
     * @param string $default
     * @return FormService
     */
    public function radio(string $name = null, string $label = null, string $value = null, string $default = null): FormService
    {
        return $this->_checkboxRadio('radio', $name, $label, $value, $default);
    }

    /**
     * Create a textarea input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return FormService
     */
    public function textarea(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('textarea')->name($name)->value($default)->label($label);
    }

    /**
     * Set a label
     *
     * @param string $label
     * @return FormService
     */
    public function label($label): FormService
    {
        return $this->_set('label', $label);
    }

    /**
     * Create a button
     *
     * @param string $value
     * @param string $color
     * @param null $size
     * @return FormService
     */
    public function button(string $value = null, $color = 'primary', $size = null): FormService
    {
        return $this->type('button')->color($color)->size($size)->value($value);
    }

    /**
     * Create a button type submit
     *
     * @param string $value
     * @param string $color
     * @param null $size
     * @return FormService
     */
    public function submit(string $value, $color = 'primary', $size = null): FormService
    {
        return $this->button($value)->type('submit')->color($color)->size($size);
    }

    /**
     * Create a button type reset
     *
     * @param string $value
     * @param string $color
     * @param null $size
     * @return FormService
     */
    public function reset(string $value, $color = 'primary', $size = null): FormService
    {
        return $this->button($value)->type('reset')->color($color)->size($size);
    }

    /**
     * Create a anchor
     *
     * @param string $value
     * @param string $url
     * @return FormService
     */
    public function anchor(string $value, $url = null): FormService
    {
        if ($url) {
            $this->url($url);
        }

        return $this->button($value)->type('anchor');
    }

    /**
     * Flag a checkbox or a radio input as checked
     *
     * @param bool $checked
     * @return FormService
     */
    public function checked(bool $checked = true): FormService
    {
        $type = $this->_builder->get('type');
        $meta = $this->_builder->get('meta');

        if ($type === 'radio' && $checked) {
            $checked = $meta['value'];
        }

        return $this->value($checked);
    }

    /**
     * Set a input value
     *
     * @param string $value
     * @return FormService
     */
    public function value($value = null): FormService
    {
        if ($value !== null) {
            return $this->_set('value', $value);
        }

        return $this;
    }

    /**
     * Set a input type
     *
     * @param string $type
     * @return FormService
     */
    public function type($type): FormService
    {
        return $this->_set('type', $type)->render($type);
    }

    /**
     * Set a render
     *
     * @param string $render
     * @return FormService
     */
    public function render(string $render): FormService
    {
        $this->_render = $render;

        return $this;
    }

    /**
     * Set a field id
     *
     * @param string $id
     * @return FormService
     */
    public function id($id): FormService
    {
        return $this->_set('id', $id);
    }

    /**
     * Set a field class
     *
     * @param string $class
     * @return FormService
     */
    public function class($class): FormService
    {
        return $this->_set('class', $class);
    }

    /**
     * Set a field name
     *
     * @param string $name
     * @return FormService
     */
    public function name($name): FormService
    {
        return $this->_set('name', $name);
    }

    /**
     * Set the size
     *
     * @param string $size
     * @return FormService
     */
    public function size(string $size = null): FormService
    {
        return $this->_set('size', $size);
    }

    /**
     * Set the size as lg
     *
     * @return FormService
     */
    public function lg(): FormService
    {
        return $this->size('lg');
    }

    /**
     * Set the size as sm
     *
     * @return FormService
     */
    public function sm(): FormService
    {
        return $this->size('sm');
    }

    /**
     * Set the color
     *
     * @param string $color
     * @return FormService
     */
    public function color(string $color = null): FormService
    {
        return $this->_set('color', $color);
    }

    /**
     * Set primary color
     *
     * @return FormService
     */
    public function primary(): FormService
    {
        return $this->color('primary');
    }

    /**
     * Set secondary color
     *
     * @return FormService
     */
    public function secondary(): FormService
    {
        return $this->color('secondary');
    }

    /**
     * Set success color
     *
     * @return FormService
     */
    public function success(): FormService
    {
        return $this->color('success');
    }

    /**
     * Set danger color
     *
     * @return FormService
     */
    public function danger(): FormService
    {
        return $this->color('danger');
    }

    /**
     * Set warning color
     *
     * @return FormService
     */
    public function warning(): FormService
    {
        return $this->color('warning');
    }

    /**
     * Set info color
     *
     * @return FormService
     */
    public function info(): FormService
    {
        return $this->color('info');
    }

    /**
     * Set light color
     *
     * @return FormService
     */
    public function light(): FormService
    {
        return $this->color('light');
    }

    /**
     * Set dark color
     *
     * @return FormService
     */
    public function dark(): FormService
    {
        return $this->color('dark');
    }

    /**
     * Set link style
     *
     * @return FormService
     */
    public function link(): FormService
    {
        return $this->color('link');
    }

    /**
     * Set outline style
     *
     * @param bool $outline
     * @return FormService
     */
    public function outline(bool $outline = true): FormService
    {
        return $this->_set('outline', $outline);
    }

    /**
     * Set block style
     *
     * @param bool $status
     * @return FormService
     */
    public function block(bool $status= true): FormService
    {
        return $this->_set('block', $status);
    }

    /**
     * Set readonly style
     *
     * @param bool $status
     * @return FormService
     */
    public function readonly($status = true): FormService
    {
        return $this->_set('readonly', $status);
    }

    /**
     * Set the input disabled status
     *
     * @param bool $status
     * @return FormService
     */
    public function disabled($status = true): FormService
    {
        return $this->_set('disabled', $status);
    }

    /**
     * Set the input required status
     *
     * @param bool $status
     * @return FormService
     */
    public function required($status = true) : FormService
    {
        return $this->_set('required', $status);
    }

    /**
     * Set the input placeholder
     *
     * @param string $placeholder
     * @return FormService
     */
    public function placeholder($placeholder): FormService
    {
        return $this->_set('placeholder', $placeholder);
    }

    /**
     * Set custom attributes for an input
     *
     * @param array $attrs
     * @return FormService
     */
    public function attrs(array $attrs = []): FormService
    {
        return $this->_set('attrs', $attrs);
    }

    /**
     * Set custom attributes for a wrapper input
     *
     * @param array $attrs
     * @return FormService
     */
    public function wrapperAttrs(array $attrs = []): FormService
    {
        return $this->_set('wrapperAttrs', $attrs);
    }

    /**
     * Set a multiple select attribute
     *
     * @param bool $multiple
     * @return FormService
     */
    public function multiple(bool $multiple = true): FormService
    {
        return $this->_set('multiple', $multiple);
    }

    /**
     * Set input group prefix
     *
     * @param string $prefix
     * @return FormService
     */
    public function prefix(string $prefix): FormService
    {
        return $this->_set('prefix', $prefix);
    }

    /**
     * Set input group suffix
     *
     * @param string $suffix
     * @return FormService
     */
    public function suffix(string $suffix): FormService
    {
        return $this->_set('suffix', $suffix);
    }

    /**
     * Set a form builder attribute
     *
     * @param string $attr
     * @param mixed $value
     * @return FormService
     */
    private function _set($attr, $value): FormService
    {
        $this->_builder->set($attr, $value);

        return $this;
    }

    /**
     * Render a checkbox or a radio input
     *
     * @param string $type
     * @param string $name
     * @param string $label
     * @param mixed $value
     * @param string $default
     * @return FormService
     */
    private function _checkboxRadio($type, $name, $label, $value, $default): FormService
    {
        $inputValue = $value === null ? $name : $value;

        if ($default) {
            $default = $inputValue;
        }

        return $this->_set('meta', ['value' => $inputValue])->type($type)->name($name)->label($label)->value($default);
    }

}
