<?php

namespace NetoJose\Bootstrap4Forms;

/**
 * FormService class
 *
 * @author neto
 */
class FormService
{

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
        return $this->_builder->render();
    }

    /**
     * Set error bag name
     * 
     * @param string $value
     * @return FormService
     */
    public function errorBag(string $value = null): FormService
    {
        return $this->_set('formErrorBag', $value);
    }

    /**
     * Open the form
     *
     * @return FormService
     */
    public function open(): FormService
    {
        return $this->_set('render', 'formOpen');
    }

    /**
     * Close the form
     *
     * @return FormService
     */
    public function close(): FormService
    {
        return $this->_set('render', 'formClose');
    }

    /**
     * Show all validation errors
     *
     * @param string $title
     * @return FormService
     */
    public function errors(string $title = null): FormService
    {
        return $this->_set('render', 'errors')->_set('errorsHeader', $title);
    }

    /**
     * Set a prefix id for all inputs
     *
     * @param string $prefix
     * @return FormService
     */
    public function idPrefix(string $prefix = ''): FormService
    {
        return $this->_set('formIdPrefix', $prefix);
    }

    /**
     * Set multipart attribute for a form
     *
     * @param bool $multipart
     * @return FormService
     */
    public function multipart(bool $multipart = true): FormService
    {
        return $this->_set('formMultipart', $multipart);
    }

    /**
     * Set a method attribute for the form
     *
     * @param string $method
     * @return FormService
     */
    public function method(string $method): FormService
    {
        return $this->_set('method', $method);
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
        return $this->_set('formData', $data);
    }

    /**
     * Set locale file for inputs translations
     *
     * @param string $path
     * @return FormService
     */
    public function locale(string $path): FormService
    {
        return $this->_set('formLocale', $path);
    }

    /**
     * Set autocomplete attribute on form, or on individual input fields
     *
     * @param string $value
     * @return FormService
     */
    public function autocomplete($value = true): FormService
    {
        return $this->_set('autocomplete', $value);
    }

    /**
     * Set inline form style
     * 
     * @param bool $inline
     * @return FormService
     */
    public function formInline(bool $inline = true): FormService
    {
        return $this->_set('formInline', $inline);
    }

    /**
     * Set url for links and form action
     *
     * @param string $url
     * @return FormService
     */
    public function url(string $url = null): FormService
    {
        return $this->_set('url', url($url ?? ''));
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
        return $this->render('fieldsetOpen')->_set('legend', $legend);
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
        return $this->render('input')->type('file')->name($name)->label($label);
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
        return $this->render('input')->type('text')->name($name)->label($label)->value($default);
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
        return $this->render('input')->type('date')->name($name)->label($label)->value($default);
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
        return $this->render('input')->type('time')->name($name)->label($label)->value($default);
    }

    /**
     * Create a telephone input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return FormService
     */
    public function tel(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->render('input')->type('tel')->name($name)->label($label)->value($default);
    }

    /**
     * Create a url input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return FormService
     */
    public function urlInput(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->render('input')->type('url')->name($name)->label($label)->value($default);
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
        return $this->render('input')->type('range')->name($name)->label($label)->value($default);
    }

    /**
     * Set a minimum value for a field
     * 
     * @param string $value
     * @return FormService
     */
    public function min($value)
    {
        return $this->_set('min', $value);
    }

    /**
     * Set a maximum value for a field
     * 
     * @param string $value
     * @return FormService
     */
    public function max($value)
    {
        return $this->_set('max', $value);
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
        return $this->render('input')->type('hidden')->name($name)->value($default);
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
        return $this->render('select')->name($name)->label($label)->options($options)->value($default);
    }

    /**
     * Set options for a select field
     *
     * @param mixed  $options
     * @param string $valueKey
     * @param string $idKey
     * @return FormService
     */
    public function options($options = [], string $valueKey = null, string $idKey = null): FormService
    {
        return $this->_set('optionValueKey', $valueKey)->_set('optionIdKey', $idKey)->_set('options', $options);
    }

    /**
     * Set a multiple select attribute
     *
     * @param bool $multiple
     * @return FormService
     */
    public function multiple(bool $status = true): FormService
    {
        return $this->_set('multiple', $status);
    }

    /**
     * Create a checkbox input
     *
     * @param string $name
     * @param string $value
     * @param string $label
     * @param bool   $checked
     * @return FormService
     */
    public function checkbox(string $name = null, string $label = null, string $value = 'on', bool $checked = null): FormService
    {
        return $this->_radioOrCheckbox('checkbox', $name, $label, $value, $checked);
    }

    /**
     * Create a radio input
     *
     * @param string $name
     * @param string $value
     * @param string $label
     * @param bool   $checked
     * @return FormService
     */
    public function radio(string $name = null, string $label = null, string $value = null, bool $checked = null): FormService
    {
        return $this->_radioOrCheckbox('radio', $name, $label, $value, $checked);
    }

    /**
     * Set inline input style
     * @param bool $inline
     * @return FormService
     */
    public function inline(bool $inline = true): FormService
    {
        return $this->_set('inline', $inline);
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
        return $this->_set('render', 'textarea')->name($name)->label($label)->value($default);
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
        return $this->type('button')->_set('render', 'button')->value($value)->color($color)->size($size);
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
        return $this->button($value, $color, $size)->type('submit');
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
        return $this->button($value, $color, $size)->type('reset');
    }

    /**
     * Create a anchor
     *
     * @param string $value
     * @param string $url
     * @return FormService
     */
    public function anchor(string $value, $url = null, $color = 'primary', $size = null): FormService
    {
        return $this->_set('render', 'anchor')->value($value)->url($url)->color($color)->size($size);
    }

    /**
     * Flag a checkbox or a radio input as checked
     *
     * @param bool $checked
     * @return FormService
     */
    public function checked(bool $checked = true): FormService
    {
        return $this->_set('checked', $checked);
    }

    /**
     * Set a input value
     *
     * @param string $value
     * @return FormService
     */
    public function value($value = null): FormService
    {
        return $this->_set('value', $value);
    }

    /**
     * Set a input type
     *
     * @param string $type
     * @return FormService
     */
    public function type($type): FormService
    {
        return $this->_set('type', $type);
    }

    /**
     * Set a render
     *
     * @param string $render
     * @return FormService
     */
    public function render(string $render): FormService
    {
        return $this->_set('render', $render);
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
     * @param bool $status
     * @return FormService
     */
    public function outline(bool $status = true): FormService
    {
        return $this->_set('outline', $status);
    }

    /**
     * Set block style
     *
     * @param bool $status
     * @return FormService
     */
    public function block(bool $status = true): FormService
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
    public function required($status = true): FormService
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
     * Disable input states (valid and invalid classes) and error message
     *
     * @param string $disable
     * @return FormService
     */
    public function disableValidation(bool $disable = true): FormService
    {
        return $this->_set('disableValidation', $disable);
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
     * Create radio or checkbox input
     *
     * @param string $render
     * @param string $name
     * @param string $value
     * @param string $label
     * @param mixed  $checked
     * @return FormService
     */
    private function _radioOrCheckbox($render, $name, $label, $value, $checked): FormService
    {
        if (is_bool($checked)) {
            $this->checked($checked);
        }
        return $this->_set('render', $render)->name($name)->label($label)->value($value);
    }

    /**
     * Set the size
     *
     * @param string $size
     * @return FormService
     */
    private function _set(string $key, $value): FormService
    {
        $this->_builder->set($key, $value);
        return $this;
    }
}
