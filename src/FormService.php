<?php

namespace NetoJose\Bootstrap4Forms;

/**
 * Description of FormService
 *
 * @author neto
 */
class FormService {
    private $_Flocale;
    private $_Fmethod;
    private $_Fmultipart;
    private $_Fdata;
    private $_render;
    private $_props;
    private $_params;
    private $_type;
    private $_url;
    private $_placeholder;
    private $_checkInline;
    private $_size;
    private $_readonly;
    private $_disabled;
    private $_id;
    private $_help;
    private $_color;
    private $_outline;
    private $_block;
    private $_value;
    
    public function __construct() {
        $this->_resetFlags();
        $this->_resetFormFlags();
    }
    
    public function __toString(){
        $output = $this->{'_render'.$this->_render}();
        $this->_resetFlags();
        return $output;
    }
    
    private function _resetFlags(){
        $this->_render      = null;
        $this->_props       = [];
        $this->_params      = [];
        $this->_type        = null;
        $this->_url         = null;
        $this->_placeholder = null;
        $this->_checkInline = false;        
        $this->_size        = null;        
        $this->_readonly    = false;
        $this->_disabled    = false;
        $this->_id          = null;
        $this->_help        = null;
        $this->_color       = "primary";
        $this->_outline     = false;
        $this->_block       = false;
        $this->_value       = "";
    }

    private function _resetFormFlags(){
        $this->_Flocale     = null;
        $this->_Fmethod     = 'post';
        $this->_Fmultipart  = false;
        $this->_Fdata       = null;
    }
    
    public function open() : FormService {
        $this->_render = 'Open';
        return $this;
    }

    public function close() : FormService {
        $this->_render = 'Close';
        return $this;
    }

    public function multipart(bool $multipart = true) : FormService {
        $this->_Fmultipart = $multipart;
        return $this;
    }

    public function get() : FormService {
        $this->_Fmethod = 'get';
        return $this;
    }

    public function post() : FormService {
        $this->_Fmethod = 'post';
        return $this;
    }

    public function put() : FormService {
        $this->_Fmethod = 'put';
        return $this;
    }

    public function patch() : FormService {
        $this->_Fmethod = 'patch';
        return $this;
    }

    public function delete() : FormService {
        $this->_Fmethod = 'delete';
        return $this;
    }

    public function fill($data) : FormService {
        if(method_exists($data, 'toArray')){
            $data = $data->toArray();
        }
        $this->_Fdata = $data;
        return $this;
    }

    public function locale($path) : FormService {
        $this->_Flocale = $path;
        return $this;
    }

    public function inline(bool $inline = true) : FormService {
        $this->_checkInline = $inline;
        return $this;
    }

    public function url(string $url) : FormService {
        $this->_url = url($url);
        return $this;
    }

    public function route(string $route, array $params = []) : FormService {
        $this->_url = route($route, $params);
        return $this;
    }

    public function fieldsetOpen(string $legend = null) : FormService {
        $this->_render = 'FieldsetOpen';
        $this->_props  = ['legend' => $legend];
        return $this;
    }

    public function fieldsetClose() : FormService {
        $this->_render = 'FieldsetClose';
        return $this;
    }

    public function help(string $text) : FormService {
        $this->_help = $text;
        return $this;
    }

    public function file(string $name, string $label = null) : FormService {
        $this->type('file');
        $this->_render = 'File';
        $this->_props  = ['name' => $name, 'label' => $label];
        return $this;
    }

    public function text(string $name, string $label = null) : FormService {
        $this->type('text');
        $this->_render = 'Text';
        $this->_props  = ['name' => $name, 'label' => $label];
        return $this;
    }

    public function button(string $value) : FormService {
        $this->type('button');
        $this->_render = 'ButtonOrAnchor';
        $this->_props  = ['value' => $value, 'name' => null];
        return $this;
    }
    
    public function submit(string $value) : FormService {
        $this->button($value);
        $this->type('submit');
        return $this;
    }

    public function reset(string $value) : FormService {
        $this->button($value);
        $this->type('reset');
        return $this;
    }

    public function anchor(string $value, $url = null) : FormService {
        $this->button($value);
        $this->type('anchor');
        if($url){
            $this->url($url);
        }
        $this->_props  = ['value' => $value, 'name' => null];
        return $this;
    }
    
    public function select(string $name, array $options = [], string $label = null) : FormService {
        $this->_render = 'Select';
        $this->_props  = ['name' => $name, 'label' => $label, 'options' => $options];
        return $this;
    }
    
    public function checkbox(string $name, string $label = null, string $value = 'on') : FormService {
        $this->type('checkbox');
        $this->_render = 'CheckboxRadio';
        $this->_props  = ['name' => $name, 'label' => $label, 'value' => $value];
        return $this;
    }
    
    public function radio(string $name, string $label = null, string $value = 'on') : FormService {
        $this->type('radio');
        $this->_render = 'CheckboxRadio';
        $this->_props  = ['name' => $name, 'label' => $label, 'value' => $value];
        return $this;
    }
    
    public function textarea(string $name, string $label) : FormService {
        $this->_render = 'Textarea';
        $this->_props  = ['name' => $name, 'label' => $label];
        return $this;
    }

    public function type($type) : FormService {
        $this->_type = $type;
        return $this;
    }
    
    public function id($id) : FormService {
        $this->_id = $id;
        return $this;
    }
    
    public function lg() : FormService {
        $this->_size = 'lg';
        return $this;
    }
    
    public function sm() : FormService {
        $this->_size = 'sm';
        return $this;
    }

    public function primary() : FormService {
        $this->_color = 'primary';
        return $this;
    }

    public function secondary() : FormService {
        $this->_color = 'secondary';
        return $this;
    }
    
    public function success() : FormService {
        $this->_color = 'success';
        return $this;
    }
    
    public function danger() : FormService {
        $this->_color = 'danger';
        return $this;
    }
    
    public function warning() : FormService {
        $this->_color = 'warning';
        return $this;
    }
    
    public function info() : FormService {
        $this->_color = 'info';
        return $this;
    }
    
    public function light() : FormService {
        $this->_color = 'light';
        return $this;
    }
    
    public function dark() : FormService {
        $this->_color = 'dark';
        return $this;
    }
    
    public function link() : FormService {
        $this->_color = 'link';
        return $this;
    }
    
    public function outline(bool $outline = true) : FormService {
        $this->_outline = $outline;
        return $this;
    }
    
    public function block(bool $block = true) : FormService {
        $this->_block = $block;
        return $this;
    }
    
    public function readonly($status = true) : FormService{
        $this->_readonly = $status;
        return $this;
    }
    
    public function disabled($status = true) : FormService{
        $this->_disabled = $status;
        return $this;
    }
    
    public function placeholder($placeholder) : FormService {
        $this->_placeholder = $placeholder;
        return $this;
    }
    
    public function params(array $params = []) : FormService {
        $this->_params = $params;
        return $this;
    }
    
    private function _renderOpen() : string {
        $method = $this->_Fmethod === 'get' ? 'get' : 'post';
        $multipart = $this->_Fmultipart ? ' enctype="multipart/form-data"': '';
        $ret = '<form method="'.$method.'" action="'.$this->_url.'"'.$multipart.'>';
        if($this->_Fmethod !== 'get'){
            $ret .= csrf_field();
            if ($this->_Fmethod !== 'post'){
                $ret .= method_field($this->_Fmethod);
            }
        }
        return $ret;
    }
    
    private function _renderClose() : string {
        $ret = '</form>';
        $this->_resetFormFlags();
        return $ret;
    }
    
    private function _renderFieldsetOpen() : string {
        $attrs = $this->_pts();
        $ret = '<fieldset'.($attrs ? (' '.$attrs) : '').'>';
        if($this->_props['legend']){
            $ret .= '<legend>'.$this->_e($this->_props['legend']).'</legend>';
        }
        return $ret;
    }
    
    private function _renderFieldsetClose() : string {
        $ret = '</fieldset>';
        return $ret;
    }

    private function _renderFile() : string {
        $attrs = $this->_pts();
        return $this->_renderWarpperCommomField('<input '.$attrs.'>');
    }
    
    private function _renderText() : string {
        $value = $this->_getValue();
        $attrs = $this->_pts(['value' => $value]);
        return $this->_renderWarpperCommomField('<input '.$attrs.'>');
    }

    private function _renderTextarea() : string {
        $attrs = $this->_pts(['rows' => 3]);
        $value = $this->_getValue();
        return $this->_renderWarpperCommomField('<textarea '.$attrs.'>'.$value.'</textarea>');
    }
    
    private function _renderSelect() : string {
        $attrs = $this->_pts();
        $value = $this->_getValue();
        $options = '';
        foreach ($this->_props['options'] as $optvalue => $label){
            $checked = $optvalue == $value ? ' selected' : '';
            $options .= '<option value="'.$optvalue.'"'.$checked.'>'.$label.'</option>';
        }
        return $this->_renderWarpperCommomField('<select '.$attrs.'>'.$options.'</select>');
    }

    private function _renderWarpperCommomField($field) : string {
        $label = $this->_getLabel();
        $help  = $this->_getHelpText();
        $error = $this->_getValidationFieldMessage();
        return '<div class="form-group">'.$label.$field.$help.$error.'</div>';
    }

    private function _renderCheckboxRadio() : string {
        $checked = $this->_getValue() == $this->_props['value'] ? 'checked' : null;
        $attrs = $this->_pts(["class" => "form-check-input", "type" => $this->_type, "value" => $this->_props['value'], 'checked' => $checked]);
        $inline = $this->_checkInline ? ' form-check-inline': '';
        return '<div class="form-check'.$inline.'"><label class="form-check-label"><input '.$attrs.'>'.$this->_e($this->_props['label']).'</label></div>';
    }
    
    private function _renderButtonOrAnchor() : string {
        $size = $this->_size ? ' btn-'.$this->_size : '';
        $outline = $this->_outline ? 'outline-' : '';
        $block = $this->_block ? ' btn-block' : '';
        $disabled = $this->_disabled ? ' disabled' : '';
        $value = $this->_e($this->_props['value']);
        $cls = 'btn btn-'.$outline.$this->_color.$size.$block;
        if($this->_type == 'anchor'){
            $href = $this->_url ?: 'javascript:void(0)';
            $attrs = $this->_pts(['class' => $cls.$disabled, 'href' => $href, 'role' => 'button', 'aria-disabled' => $disabled ? 'true' : null]);
            $ret = '<a '.$attrs.'>'.$value.'</a>';
        } else {
            $attrs = $this->_pts(['class' => $cls, 'type' => $this->_type]);
            $ret = '<button '.$attrs.' '.$disabled.'>'.$value.'</button>';
        }
        return $ret;
    }
    
    private function _getLabel() : string {
        $for = $this->_id ?: $this->_props['name'];
        return '<label for="'.$for.'">'.$this->_e($this->_props['label']).'</label>';
    }
    
    private function _pts(array $props = []) : string {
        $ret = "";

        $props['type']          = $this->_type;
        $props['name']          = isset($this->_props['name']) ? $this->_props['name'] : null;
        $props['id']            = $this->_getId();
        $props['autocomplete']  = $props['name'];
        
        if($this->_placeholder){
            $props['placeholder'] = $this->_placeholder;
        }

        if($this->_help){
            $props['aria-describedby'] = $this->_getIdHelp();
        }
        
        if(!isset($props['class'])){
            $props['class'] = "form-control".($this->_size ? ' form-control-'.$this->_size : '');
        }
        $props['class'] .= $this->_getValidationFieldClass();

        if(isset($this->_params['class'])){
            $props['class'] .= ' ' . $this->_params['class'];
        }

        $allProps = array_merge($this->_params, $props);
        
        foreach($allProps as $key => $value){
            if($value !== null){
                $ret .= $key.'="'.htmlspecialchars($value).'" ';
            }
        }
        
        if($this->_readonly){
            $ret .= 'readonly ';
        }
        
        if($this->_disabled){
            $ret .= 'disabled';
        }
        
        return trim($ret);
    }

    private function _getValue(){
        $name = $this->_props['name'];
        $default = isset($this->_Fdata[$name]) ? $this->_Fdata[$name] : null;
        return old($name, $default);
    }

    private function _getId(){
        $id = $this->_id;
        if(!$id && isset($this->_props['name'])){
            $id = $this->_props['name'];
            if($this->_type == 'radio'){
                $id .= '-' . str_slug($this->_props['value']);
            }
        }
        return $id;
    }

    private function _getIdHelp(){
        $id = $this->_getId();
        return $id ? 'help-'.$id : '';
    }

    private function _getHelpText() : string {
        $id = $this->_getIdHelp();
        return $this->_help ? '<small id="'.$id.'" class="form-text text-muted">'.$this->_e($this->_help).'</small>' : '';
    }

    private function _e($key){
        $fieldKey = $key ?: $this->_props['name'];
        return $this->_Flocale ? __($this->_Flocale . '.' . $fieldKey) : $fieldKey;
    }

    private function _getValidationFieldClass() : string {
        if(!isset($this->_props['name']) || !$this->_props['name']){
            return '';
        }

        if(session('errors') === null){
            return '';
        }

        if($this->_getValidationFieldMessage()){
            return ' is-invalid';
        }

        return ' is-valid';
    }

    private function _getValidationFieldMessage(string $prefix = '<div class="invalid-feedback">', string $sufix = '</div>') {
        $errors = session('errors');
        if(!$errors){
            return null;
        }
        $error = $errors->first($this->_props['name']);

        if(!$error){
            return null;
        }

        return $prefix . $error . $sufix;
    }
}
