<?php

namespace NetoJose\Bootstrap4Forms;

class FormBuilder {

    private $_Flocale;
    private $_Fmethod;
    private $_Fmultipart;
    private $_Fdata;
    private $_FidPrefix;
    private $_meta;
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

    public function __construct()
    {
        $this->resetFlags();
        $this->resetFormFlags();
    }

    public function resetFlags()
    {

        $this->_render = null;
        $this->_meta = [];
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

    public function resetFormFlags()
    {

        $this->_Flocale = null;
        $this->_Fmethod = 'post';
        $this->_Fmultipart = false;
        $this->_Fdata = null;
        $this->_FidPrefix = '';
    }

    public function set($attr, $value)
    {
        $this->{'_' . $attr} = $value;
    }

    public function get($attr)
    {
        return $this->{'_' . $attr};
    }

    public function open(): string
    {

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

    public function close(): string
    {

        $ret = '</form>';
        $this->resetFormFlags();

        return $ret;
    }

    public function fieldsetOpen(): string
    {

        $attrs = $this->_buildAttrs();
        $ret = '<fieldset' . ($attrs ? (' ' . $attrs) : '') . '>';

        if ($this->_meta['legend']) {
            $ret .= '<legend>' . $this->_e($this->_meta['legend']) . '</legend>';
        }

        return $ret;
    }

    public function fieldsetClose(): string
    {
        return '</fieldset>';
    }

    public function file(): string
    {

        $attrs = $this->_buildAttrs();

        return $this->_renderWarpperCommomField('<input ' . $attrs . '>');
    }

    public function text(): string
    {
        return $this->_renderInput();
    }

    public function email(): string
    {
        return $this->_renderInput('email');
    }

    public function number(): string
    {
        return $this->_renderInput('number');
    }

    public function hidden(): string
    {

        $value = $this->_getValue();
        $attrs = $this->_buildAttrs(['value' => $value]);

        return '<input ' . $attrs . '>';
    }

    public function textarea(): string
    {

        $attrs = $this->_buildAttrs(['rows' => 3]);
        $value = $this->_getValue();

        return $this->_renderWarpperCommomField('<textarea ' . $attrs . '>' . $value . '</textarea>');
    }

    public function select(): string
    {

        $attrs = $this->_buildAttrs();
        $value = $this->_getValue();
        $options = '';

        if ($this->_multiple) {
            if (!is_array($value)) {
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

    public function checkbox(): string
    {
        return $this->_renderCheckboxOrRadio();
    }

    public function radio(): string
    {
        return $this->_renderCheckboxOrRadio();
    }

    public function button(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    public function submit(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    public function reset(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    public function anchor(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    private function _renderInput($type = 'text'): string
    {

        $value = $this->_getValue();
        $attrs = $this->_buildAttrs(['value' => $value, 'type' => $type]);

        return $this->_renderWarpperCommomField('<input ' . $attrs . '>');
    }

    private function _renderButtonOrAnchor(): string
    {
        $size = $this->_size ? ' btn-' . $this->_size : '';
        $outline = $this->_outline ? 'outline-' : '';
        $block = $this->_block ? ' btn-block' : '';
        $disabled = $this->_disabled ? ' disabled' : '';
        $value = $this->_e($this->_value);
        $cls = 'btn btn-' . $outline . $this->_color . $size . $block;

        if ($this->_type == 'anchor') {
            $href = $this->_url ?: 'javascript:void(0)';
            $attrs = $this->_buildAttrs(
                    [
                        'class' => $cls . $disabled,
                        'href' => $href,
                        'role' => 'button',
                        'aria-disabled' => $disabled ? 'true' : null
                    ]
            );
            $ret = '<a ' . $attrs . '>' . $value . '</a>';
        } else {
            $attrs = $this->_buildAttrs(['class' => $cls, 'type' => $this->_type]);
            $ret = '<button ' . $attrs . ' ' . $disabled . '>' . $value . '</button>';
        }

        return $ret;
    }

    private function _getLabel(): string
    {

        $label = $this->_label === true ? $this->_name : $this->_label;
        $result = '';

        if ($label) {
            $id = $this->_getId();
            $result = '<label for="' . $id . '">' . $this->_e($label) . '</label>';
        }

        return $result;
    }

    private function _buildAttrs(array $props = []): string
    {

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

        if (isset($this->_attrs['class'])) {
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
            if (
                    $value && (
                    $this->_type === 'checkbox' || $this->_type === 'radio' && $value === $this->_meta['value']
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
        foreach ($allProps as $key => $value) {
            if ($value === null) {
                continue;
            }
            $ret .= $key . '="' . htmlspecialchars($value) . '" ';
        }

        return trim($ret);
    }

    private function _getValue()
    {
        $name = $this->_name;

        if ($this->_hasOldInput()) {
            return old($name);
        }

        if ($this->_value !== null) {
            return $this->_value;
        }

        if (isset($this->_Fdata[$name])) {
            return $this->_Fdata[$name];
        }
    }

    private function _hasOldInput()
    {
        return count((array) old()) != 0;
    }

    private function _getId()
    {
        $id = $this->_id;

        if (!$id && $this->_name) {
            $id = $this->_name;
            if ($this->_type == 'radio') {
                $id .= '-' . str_slug($this->_meta['value']);
            }
        }

        return $this->_FidPrefix . $id;
    }

    private function _getIdHelp()
    {
        $id = $this->_getId();

        return $id ? 'help-' . $id : '';
    }

    private function _getHelpText(): string
    {
        $id = $this->_getIdHelp();

        return $this->_help ? '<small id="' . $id . '" class="form-text text-muted">' . $this->_e($this->_help) . '</small>' : '';
    }

    private function _e($key)
    {
        $fieldKey = $key ?: $this->_name;

        return $this->_Flocale ? __($this->_Flocale . '.' . $fieldKey) : $fieldKey;
    }

    private function _getValidationFieldClass(): string
    {
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

    private function _renderCheckboxOrRadio(): string
    {
        $attrs = $this->_buildAttrs(["class" => "form-check-input", "type" => $this->_type, "value" => $this->_meta['value']]);
        $inline = $this->_checkInline ? ' form-check-inline' : '';

        return '<div class="form-check' . $inline . '"><label class="form-check-label"><input ' . $attrs . '>' . $this->_e($this->_label) . '</label></div>';
    }

    private function _renderWarpperCommomField($field): string
    {
        $label = $this->_getLabel();
        $help = $this->_getHelpText();
        $error = $this->_getValidationFieldMessage();

        return '<div class="form-group">' . $label . $field . $help . $error . '</div>';
    }

    private function _getValidationFieldMessage(string $prefix = '<div class="invalid-feedback">', string $sufix = '</div>')
    {
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
