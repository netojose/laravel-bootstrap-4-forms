<?php

namespace NetoJose\Bootstrap4Forms;

/**
 * FormService class
 *
 * @author neto
 */
class FormService
{
    private $_Flocale;
    private $_Fmethod;
    private $_Fmultipart;
    private $_Fdata;
    private $_FidPrefix;

    private $_render;
    private $_props;
    private $_attrs;
    private $_type;
    private $_url;
    private $_placeholder;
    private $_checkInline;
    private $_size;
    private $_readonly;
    private $_disabled;
    private $_id;
    private $_name;
    private $_label;
    private $_options;
    private $_help;
    private $_color;
    private $_outline;
    private $_block;
    private $_value;
    private $_multiple;

    private $_allowedRenders = ['open', 'close', 'file', 'text', 'email', 'number', 'hidden', 'select', 'checkbox', 'radio', 'textarea', 'button', 'submit', 'anchor', 'reset'];

    public function __construct() {

        $this->_resetFlags();
        $this->_resetFormFlags();

    }

    public function __toString() {

        $output = '';

        if(in_array($this->_render, $this->_allowedRenders)) {
            switch($this->_render){
                case 'checkbox':
                case 'radio':
                    $render = 'checkboxOrRadio';
                    break;
                case 'button':
                case 'submit':
                case 'reset':
                case 'anchor':
                    $render = 'buttonOrAnchor';
                    break;
                default:
                    $render = $this->_render;
                    break;
            }


            $output = $this->{'_render' . ucfirst($render)}();
        }

        $this->_resetFlags();

        return $output;
    }

    private function _resetFlags() {

        $this->_render = null;
        $this->_props = [];
        $this->_attrs = [];
        $this->_type = null;
        $this->_url = null;
        $this->_placeholder = null;
        $this->_checkInline = false;
        $this->_size = null;
        $this->_readonly = false;
        $this->_disabled = false;
        $this->_id = null;
        $this->_name = null;
        $this->_label = null;
        $this->_options = [];
        $this->_help = null;
        $this->_color = "primary";
        $this->_outline = false;
        $this->_block = false;
        $this->_value = null;
        $this->_multiple = false;

    }

    private function _resetFormFlags() {

        $this->_Flocale = null;
        $this->_Fmethod = 'post';
        $this->_Fmultipart = false;
        $this->_Fdata = null;
        $this->_FidPrefix = '';
    }

    private function _set($attr, $value): FormService {
        $this->{'_' . $attr} = $value;
        return $this;
    }

    public function open(): FormService {
        return $this->render('open');
    }

    public function close(): FormService {
        return $this->render('close');
    }

    public function idPrefix(string $prefix = ''): FormService {
        return $this->_set('FidPrefix', $prefix);
    }

    public function multipart(bool $multipart = true): FormService {
        return $this->_set('Fmultipart', $multipart);
    }

    public function method(string $method): FormService {
        return $this->_set('Fmethod', $method);
    }

    public function get(): FormService {
        return $this->method('get');
    }

    public function post(): FormService {
        return $this->method('post');
    }

    public function put(): FormService {
        return $this->method('put');
    }

    public function patch(): FormService {
        return $this->method('patch');
    }

    public function delete(): FormService {
        return $this->method('delete');
    }

    public function fill($data): FormService {

        if (method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        if(!is_array($data)){
            $data = [];
        }

        return $this->_set('Fdata', $data);
    }

    public function locale(string $path): FormService {
        return $this->_set('Flocale', $path);
    }

    public function inline(bool $inline = true): FormService {
        return $this->_set('checkInline', $inline);
    }

    public function url(string $url): FormService {
        return $this->_set('url', url($url));
    }

    public function route(string $route, array $params = []): FormService {
        return $this->_set('url', route($route, $params));
    }

    public function fieldsetOpen(string $legend = null): FormService {
        return $this->_set('props', ['legend' => $legend])->render('fieldsetOpen');
    }

    public function fieldsetClose(): FormService {
        return $this->render('fieldsetClose');
    }

    public function help(string $text): FormService {
        return $this->_set('help', $text);
    }

    public function file(string $name = null, string $label = null): FormService {
        return $this->name($name)->label($label)->type('file');
    }

    public function text(string $name = null, $label = null, string $default = null): FormService {
        return $this->type('text')->name($name)->label($label)->value($default);
    }

    public function hidden(string $name = null, string $default = null): FormService {
        return $this->name($name)->value($default)->type('hidden');
    }

    public function select(string $name = null, string $label = null, $options = [], $default = null): FormService {
        return $this->name($name)->label($label)->options($options)->value($default)->type('select');
    }

    public function options(array $options = []): FormService {        
        $items = is_iterable($options) ? $options : [0 => 'Must be iterable'];
        return $this->_set('options', $items);
    }

    public function checkbox(string $name = null, string $label = null, string $value = null, string $default = null): FormService { 
        return $this->_checkboxRadio('checkbox', $name, $label, $value, $default);
    }

    public function radio(string $name = null, string $label = null, string $value = null, string $default = null): FormService {
        return $this->_checkboxRadio('radio', $name, $label, $value, $default);
    }

    private function _checkboxRadio($type, $name, $label, $value, $default ): FormService {
        $value = $value === null ? $name : $value;
        
        if($default) {
            $default = $value;
        }

        return $this->_set('props', ['value' => $value])->type($type)->name($name)->label($label)->value($default);
    }

    public function textarea(string $name = null, $label = null, string $default = null): FormService {
        return $this->type('textarea')->name($name)->value($default)->label($label);
    }

    public function label($label): FormService {
        return $this->_set('label', $label);
    }

    public function button(string $value = null): FormService {
        return $this->type('button')->value($value);
    }

    public function submit(string $value): FormService {
        return $this->button($value)->type('submit');
    }

    public function reset(string $value): FormService {
        return $this->type('reset')->button($value);
    }

    public function anchor(string $value, $url = null): FormService {
        if ($url) {
            $this->url($url);
        }

        return $this->button($value)->type('anchor');
    }

    public function checked(bool $checked = true) : FormService {
        if($this->_type === 'radio' && $checked){
            $checked = $this->_props['value'];
        }
        return $this->value($checked);
    }

    public function value($value = null) : FormService {

        if($value !== null){
            return $this->_set('value', $value);
        }

        return $this;
    }

    public function type($type): FormService {
        return $this->_set('type', $type)->render($type);
    }

    public function render(string $render): FormService {        
        return $this->_set('render', $render);
    }

    public function id($id): FormService {
        return $this->_set('id', $id);
    }
    
    public function name($name): FormService {
        return $this->_set('name', $name);
    }

    public function lg(): FormService {
        return $this->_set('size', 'lg');
    }

    public function sm(): FormService {
        return $this->_set('size', 'sm');
    }

    public function primary(): FormService {
        return $this->_set('color', 'primary');
    }

    public function secondary(): FormService {
        return $this->_set('color', 'secondary');
    }

    public function success(): FormService {
        return $this->_set('color', 'success');
    }

    public function danger(): FormService {
        return $this->_set('color', 'danger');
    }

    public function warning(): FormService {
        return $this->_set('color', 'warning');
    }

    public function info(): FormService {
        return $this->_set('color', 'info');
    }

    public function light(): FormService {
        return $this->_set('color', 'light');
    }

    public function dark(): FormService {
        return $this->_set('color', 'dark');
    }

    public function link(): FormService {
        return $this->_set('color', 'link');
    }

    public function outline(bool $outline = true): FormService {
        return $this->_set('outline', $outline);
    }

    public function block(bool $block = true): FormService {
        return $this->_set('block', $block);
    }

    public function readonly($status = true): FormService {
        return $this->_set('readonly', $status);
    }

    public function disabled($status = true): FormService {
        return $this->_set('disabled', $status);
    }

    public function placeholder($placeholder): FormService {
        return $this->_set('placeholder', $placeholder);
    }

    public function attrs(array $attrs = []): FormService {
        return $this->_set('attrs', $attrs);
    }

    public function multiple(bool $multiple = true): FormService {
        return $this->_set('multiple', $multiple);
    }

    private function _renderOpen(): string {

        $method = $this->_Fmethod === 'get' ? 'get' : 'post';
        $multipart = $this->_Fmultipart ? ' enctype="multipart/form-data"' : '';
        $ret = '<form method="' . $method . '" action="' . $this->_url . '"' . $multipart . '>';
        if ($this->_Fmethod !== 'get') {
            $ret .= csrf_field();
            if ($this->_Fmethod !== 'post') {
                $ret .= method_field($this->_Fmethod);
            }
        }

        return $ret;
    }

    private function _renderClose(): string {

        $ret = '</form>';
        $this->_resetFormFlags();

        return $ret;
    }

    private function _renderFieldsetOpen(): string {

        $attrs = $this->_pts();
        $ret = '<fieldset' . ($attrs ? (' ' . $attrs) : '') . '>';
        if ($this->_props['legend']) {
            $ret .= '<legend>' . $this->_e($this->_props['legend']) . '</legend>';
        }

        return $ret;
    }

    private function _renderFieldsetClose(): string {
        return '</fieldset>';
    }

    private function _renderFile(): string {

        $attrs = $this->_pts();

        return $this->_renderWarpperCommomField('<input ' . $attrs . '>');
    }

    private function _renderInput($type = 'text'): string {

        $value = $this->_getValue();
        $attrs = $this->_pts(['value' => $value, 'type' => $type]);

        return $this->_renderWarpperCommomField('<input ' . $attrs . '>');
    }

    private function _renderText(): string {
        return $this->_renderInput();
    }

    private function _renderEmail(): string {
        return $this->_renderInput('email');
    }
    
    private function _renderNumber(): string {
        return $this->_renderInput('number');
    }

    private function _renderHidden(): string {

        $value = $this->_getValue();
        $attrs = $this->_pts(['value' => $value]);

        return '<input ' . $attrs . '>';
    }

    private function _renderTextarea(): string {

        $attrs = $this->_pts(['rows' => 3]);
        $value = $this->_getValue();

        return $this->_renderWarpperCommomField('<textarea ' . $attrs . '>' . $value . '</textarea>');
    }

    private function _renderSelect(): string {

        $attrs = $this->_pts();
        $value = $this->_getValue();
        $options = '';

        if ($this->_multiple) {
            if(!is_array($value)) {
                $value = [$value];
            }
            
            foreach ($this->_options as $key => $label) {

                if (array_key_exists($key, $value)) {
                    $match = true;
                } else {
                    $match = false;
                }

                $checked = ($match) ? ' selected' : '';
                $options .= '<option value="' . $key . '"' . $checked . '>' . $label . '</option>';
            }
        } else {
            foreach ($this->_options as $optvalue => $label) {
                $checked = $optvalue == $value ? ' selected' : '';
                $options .= '<option value="' . $optvalue . '"' . $checked . '>' . $label . '</option>';
            }
        }

        return $this->_renderWarpperCommomField('<select ' . $attrs . '>' . $options . '</select>');
    }

    private function _renderWarpperCommomField($field): string {

        $label = $this->_getLabel();
        $help  = $this->_getHelpText();
        $error = $this->_getValidationFieldMessage();

        return '<div class="form-group">' . $label . $field . $help . $error . '</div>';
    }

    private function _renderCheckboxOrRadio(): string {
        $attrs  = $this->_pts(["class" => "form-check-input", "type" => $this->_type, "value" => $this->_props['value']]);
        $inline = $this->_checkInline ? ' form-check-inline' : '';

        return '<div class="form-check' . $inline . '"><label class="form-check-label"><input ' . $attrs . '>' . $this->_e($this->_label) . '</label></div>';
    }

    private function _renderButtonOrAnchor(): string {

        $size = $this->_size ? ' btn-' . $this->_size : '';
        $outline = $this->_outline ? 'outline-' : '';
        $block = $this->_block ? ' btn-block' : '';
        $disabled = $this->_disabled ? ' disabled' : '';
        $value = $this->_e($this->_value);
        $cls = 'btn btn-' . $outline . $this->_color . $size . $block;
        if ($this->_type == 'anchor') {
            $href = $this->_url ?: 'javascript:void(0)';
            $attrs = $this->_pts(['class' => $cls . $disabled, 'href' => $href, 'role' => 'button',
                                    'aria-disabled' => $disabled ? 'true' : null]);
            $ret = '<a ' . $attrs . '>' . $value . '</a>';
        } else {
            $attrs = $this->_pts(['class' => $cls, 'type' => $this->_type]);
            $ret = '<button ' . $attrs . ' ' . $disabled . '>' . $value . '</button>';
        }

        return $ret;
    }

    private function _getLabel(): string {

        $label = $this->_label === true ? $this->_name : $this->_label;
        $result = '';

        if ($label) {
            $id = $this->_getId();
            $result = '<label for="' . $id . '">' . $this->_e($label) . '</label>';
        }

        return $result;

    }

    private function _pts(array $props = []): string {

        $ret = '';

        $props['type'] = $this->_type;
        $props['name'] = $this->_name;
        $props['id'] = $this->_getId();
        $props['autocomplete'] = $props['name'];

        if ($this->_type == 'select' && $this->_multiple) {
            $props['name'] = $props['name'] . '[]';
        }

        if ($this->_placeholder) {
            $props['placeholder'] = $this->_placeholder;
        }

        if ($this->_help) {
            $props['aria-describedby'] = $this->_getIdHelp();
        }

        if (!isset($props['class'])) {
            $props['class'] = "form-control" . ($this->_size ? ' form-control-' . $this->_size : '');
        }
        $props['class'] .= $this->_getValidationFieldClass();

        if(isset($this->_attrs['class'])){
            $props['class'] .= ' ' . $this->_attrs['class'];
        }

        if ($this->_type == 'select' && $this->_multiple) {
            $ret .= 'multiple ';
        }

        if ($this->_readonly) {
            $ret .= 'readonly ';
        }

        if ($this->_disabled) {
            $ret .= 'disabled ';
        }

        if (in_array($this->_type, ['radio', 'checkbox'])) {
            $value = $this->_getValue();
            if(
                $value && (
                    $this->_type === 'checkbox'
                    || $this->_type === 'radio' && $value === $this->_props['value']
                )
            ) {
                $ret .= 'checked ';
            }
        }

        if ($this->_type == 'hidden') {
            unset($props['autocomplete']);
            unset($props['class']);
        }

        $allProps = array_merge($this->_attrs, $props);
        foreach($allProps as $key => $value){
            if($value === null){
                continue;
            }
            $ret .= $key.'="'.htmlspecialchars($value).'" ';
        }

        return trim($ret);
    }

    private function _getValue() {
        $name = $this->_name;

        if($this->_hasOldInput()){
            return old($name);
        }

        if($this->_value !== null){
            return $this->_value;
        }

        if (isset($this->_Fdata[$name])) {
            return $this->_Fdata[$name];
        }
    }

    private function _hasOldInput(){
        return count((array) old()) != 0;
    }

    private function _getId() {

        $id = $this->_id;
        if (!$id && $this->_name) {
            $id = $this->_name;
            if ($this->_type == 'radio') {
                $id .= '-' . str_slug($this->_props['value']);
            }
        }

        return $this->_FidPrefix . $id;
    }

    private function _getIdHelp() {

        $id = $this->_getId();

        return $id ? 'help-' . $id : '';
    }

    private function _getHelpText(): string {

        $id = $this->_getIdHelp();

        return $this->_help ? '<small id="' . $id . '" class="form-text text-muted">' . $this->_e($this->_help) . '</small>' : '';
    }

    private function _e($key) {

        $fieldKey = $key ?: $this->_name;

        return $this->_Flocale ? __($this->_Flocale . '.' . $fieldKey) : $fieldKey;
    }

    private function _getValidationFieldClass(): string {

        if (!$this->_name) {
            return '';
        }

        if (session('errors') === null) {
            return '';
        }

        if ($this->_getValidationFieldMessage()) {
            return ' is-invalid';
        }

        return ' is-valid';
    }

    private function _getValidationFieldMessage(string $prefix = '<div class="invalid-feedback">', string $sufix = '</div>') {

        $errors = session('errors');
        if (!$errors) {
            return null;
        }
        $error = $errors->first($this->_name);

        if (!$error) {
            return null;
        }

        return $prefix . $error . $sufix;
    }
}
