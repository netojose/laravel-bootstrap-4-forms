<?php

namespace NetoJose\Bootstrap4Forms;

class FormBuilder {

    private $_attrs = [];

    public function set($key, $value) {
        $formatter = '_format'.ucfirst($key);
        if(method_exists($this, $formatter)){
            $value = $this->$formatter($value);
        }
        $this->_attrs[$key] = $value;
    }

    private function _formatFormData($value){
        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }
        return $value;
    }

    public function render(): string {
        $render = $this->_attrs['render'];
        $methodName = '_render' . ucfirst($render);
        $output = $this->$methodName();
        $this->_resetAttributes();
        return $output;
    }

    private function _renderFormOpen() : string {
        extract($this->_get('method', 'formUrl', 'formMultipart'));

        if(!$method) {
            $method = 'post';
        }

        $enctype = $formMultipart ? 'multipart/form-data' : null;

        $attrs = $this->_buildHtmlAttrs(['method' => $method, 'action' => $formUrl, 'enctype' => $enctype]);
        $output = '<form '.$attrs.'>';

        if ($method !== 'get') {
            $output .= csrf_field();
            if ($method !== 'post') {
                $output .= method_field($method);
            }
        }

        return $output;
    }

    private function _renderFormClose() : string {
        $this->_resetAttributes(true);
        return '</form>';
    }

    private function _renderButton() : string {
        extract($this->_get('size', 'color', 'type', 'value', 'disabled'));
        $sizeCls = !$size ? '' : 'btn-'.$size;
        $colorCls = !$color ? '' : 'btn-'.$color;
        $class = join(' ', array_filter(['btn', $colorCls, $sizeCls]));
        $attrs = $this->_buildHtmlAttrs(['type' => $type, 'class' => $class, 'disabled' => $disabled]);
        return '<button '.$attrs.'>'.$value.'</button>';
    }

    private function _renderInput() : string {
        extract($this->_get('type', 'name', 'placeholder', 'help', 'disabled'));
        $class = 'form-control';
        $id = $this->_getId();
        $attrs = $this->_buildHtmlAttrs([
            'type' => $type, 
            'name' => $name, 
            'value' => $this->_getValue(), 
            'class' => $class, 
            'id' => $id,
            'placeholder' => $placeholder,
            'aria-describedby' => $help ? 'help-'.$id : null,
            'disabled' => $disabled
        ]);
        return $this->_wrapperInput('<input '.$attrs.'>');
    }

    private function renderLabel() : string {
        extract($this->_get('label'));
        $id = $this->_getId();
        return '<label for="'.$id.'">'.$this->_getText($label).'</label>';
    }

    private function _getText($key){
        extract($this->_get('formLocale'));
        if($formLocale){
            // ...
        }
        return $key;
    }

    private function _resetAttributes($resetAll = false) {
        // Remove all attributes
        if($resetAll) {
            $this->_attrs = [];
            return;
        }
        
        // Keep attributes which key starting with 'form'
        $keys = array_keys($this->_attrs);
        foreach($keys as $key){
            if(substr($key, 0, 4) == 'form'){
                continue;
            }
            unset($this->_attrs[$key]);
        }
    }

    private function _wrapperInput(string $input) : string {
        extract($this->_get('help'));
        $id = $this->_getId();
        $label = $this->renderLabel();
        $helpText = $help ? '<small id="help-'.$id.'" class="form-text text-muted">'.$this->_getText($help).'</small>' : '';
        return '<div class="form-group">'.$label.$input.$helpText.'</div>';
    }

    private function _getId() {
        extract($this->_get('name', 'formIdPrefix'));
        return ($formIdPrefix ?? 'inp-') . $name;
    }

    private function _getValue() {
        extract($this->_get('name', 'value', 'formData'));
        return old($name, $value) ?? ($formData[$name] ?? null);
    }

    private function _buildHtmlAttrs($attributes) : string {
        return join(' ', array_filter(
            array_map(function($key) use ($attributes) {
                $value = $attributes[$key];
                if(is_bool($value)){
                    return $value ? $key : '';
                } elseif($value) {
                    return $key.'="'.htmlspecialchars($value).'"';
                }
                return '';
            }, array_keys($attributes))
        ));
    }

    private function _get(...$keys) : array {
        $return = [];
        foreach($keys as $key){
            $return[$key] = $this->_attrs[$key] ?? null;
        }
        return $return;
    }

}