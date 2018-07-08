<?php

namespace NetoJose\Bootstrap4Forms;

class FormBuilder {

    /**
     * Form input labels locale
     *
     * @var string
     */
    private $_Flocale;

    /**
     * Form method
     *
     * @var string
     */
    private $_Fmethod;

    /**
     * Multipart flag
     *
     * @var boolean
     */
    private $_Fmultipart;

    /**
     * Form array data
     *
     * @var array
     */
    private $_Fdata;

    /**
     * Inputs id prefix
     * @var string
     */
    private $_FidPrefix;

    /**
     * Input meta data
     *
     * @var array
     */
    private $_meta;

    /**
     * Input attributes
     *
     * @var array
     */
    private $_attrs;

    /**
     * Form control type
     *
     * @var string
     */
    private $_type;

    /**
     * Form/Link
     *
     * @var string
     */
    private $_url;

    /**
     * Input placeholder
     *
     * @var string
     */
    private $_placeholder;

    /**
     * Flag to determine checkbox/radio style
     *
     * @var boolean
     */
    private $_checkInline;

    /**
     * Flag to determine inputs inline style
     *
     * @var boolean
     */
    private $_checkInlineForm;

    /**
     * Input size
     *
     * @var string
     */
    private $_size;

    /**
     * Readonly flag
     *
     * @var boolean
     */
    private $_readonly;

    /**
     * Disabled flag
     *
     * @var boolean
     */
    private $_disabled;

    /**
     * Input id
     *
     * @var string
     */
    private $_id;

    /**
     * Input name
     *
     * @var string
     */
    private $_name;

    /**
     * Input label
     *
     * @var string
     */
    private $_label;

    /**
     * Select options
     *
     * @var array
     */
    private $_options;

    /**
     * Input help text
     *
     * @var string
     */
    private $_help;

    /**
     * Input color
     *
     * @var string
     */
    private $_color;

    /**
     * Input outline flag
     *
     * @var boolean
     */
    private $_outline;

    /**
     * Input block flag
     *
     * @var boolean
     */
    private $_block;

    /**
     * Input value
     *
     * @var boolean
     */
    private $_value;

    /**
     * Select multiple flag
     *
     * @var boolean
     */
    private $_multiple;

    public function __construct()
    {
        $this->_resetFlags();
        $this->_resetFormFlags();
    }

    /**
     * Set a class attribute
     *
     * @param string $attr
     * @param mixed $value
     */
    public function set(string $attr, $value)
    {
        $this->{'_' . $attr} = $value;
    }

    /**
     * Retrieve a class attribute
     *
     * @param string $attr
     * @return mixed
     */
    public function get(string $attr)
    {
        return $this->{'_' . $attr};
    }

    /**
     * Return a open form tag
     *
     * @return string
     */
    public function open(): string
    {
        $props = [
            'action' => $this->_url,
            'method' => $this->_Fmethod === 'get' ? 'get' : 'post'
        ];

        if($this->_Fmultipart){
            $props['enctype'] = 'multipart/form-data';
        }

        $attrs = $this->_buildAttrs($props, ['class-form-control']);

        $ret = '<form ' . $attrs . '>';

        if ($this->_Fmethod !== 'get') {
            $ret .= csrf_field();

            if ($this->_Fmethod !== 'post') {
                $ret .= method_field($this->_Fmethod);
            }
        }

        $this->_resetFlags();

        return $ret;
    }

    /**
     * Return a close form tag
     *
     * @return string
     */
    public function close(): string
    {
        $ret = '</form>';

        $this->_resetFormFlags();
        $this->_resetFlags();

        return $ret;
    }

    /**
     * Return a open fieldset tag
     *
     * @return string
     */
    public function fieldsetOpen(): string
    {
        $attrs = $this->_buildAttrs();
        $ret = '<fieldset' . ($attrs ? (' ' . $attrs) : '') . '>';

        if ($this->_meta['legend']) {
            $ret .= '<legend>' . $this->_e($this->_meta['legend']) . '</legend>';
        }

        $this->_resetFlags();

        return $ret;
    }

    /**
     * Return a close fieldset tag
     *
     * @return string
     */
    public function fieldsetClose(): string
    {
        $this->_resetFlags();

        return '</fieldset>';
    }

    /**
     * Return a file input tag
     *
     * @return string
     */
    public function file(): string
    {
        $attrs = $this->_buildAttrs();

        return $this->_renderWarpperCommomField('<input ' . $attrs . '>');
    }

    /**
     * Return a text input tag
     *
     * @return string
     */
    public function text(): string
    {
        return $this->_renderInput();
    }

    /**
     * Return a password input tag
     *
     * @return string
     */
    public function password(): string
    {
        return $this->_renderInput('password');
    }


    /**
     * Return a email input tag
     *
     * @return string
     */
    public function email(): string
    {
        return $this->_renderInput('email');
    }

    /**
     * Return a number input tag
     *
     * @return string
     */
    public function number(): string
    {
        return $this->_renderInput('number');
    }

    /**
     * Return a hidden input tag
     *
     * @return string
     */
    public function hidden(): string
    {
        $value = $this->_getValue();
        $attrs = $this->_buildAttrs(['value' => $value]);

        $this->_resetFlags();

        return '<input ' . $attrs . '>';
    }

    /**
     * Return a textarea tag
     *
     * @return string
     */
    public function textarea(): string
    {
        $attrs = $this->_buildAttrs(['rows' => 3]);
        $value = $this->_getValue();

        return $this->_renderWarpperCommomField('<textarea ' . $attrs . '>' . $value . '</textarea>');
    }

    /**
     * Return a select tag
     *
     * @return string
     */
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

    /**
     * Return a checkbox tag
     *
     * @return string
     */
    public function checkbox(): string
    {
        return $this->_renderCheckboxOrRadio();
    }

    /**
     * Return a radio tag
     *
     * @return string
     */
    public function radio(): string
    {
        return $this->_renderCheckboxOrRadio();
    }

    /**
     * Return a button tag
     *
     * @return string
     */
    public function button(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    /**
     * Return a submit input tag
     *
     * @return string
     */
    public function submit(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    /**
     * Return a reset button tag
     *
     * @return string
     */
    public function reset(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    /**
     * Return a anchor tag
     *
     * @return string
     */
    public function anchor(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    /**
     * Return a generic input tag
     *
     * @param string $type
     * @return string
     */
    private function _renderInput($type = 'text'): string
    {
        $value = $this->_getValue();
        $attrs = $this->_buildAttrs(['value' => $value, 'type' => $type]);

        return $this->_renderWarpperCommomField('<input ' . $attrs . '>');
    }

    /**
     * Return a button or anchor tag
     *
     * @return string
     */
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

        $this->_resetFlags();

        return $ret;
    }

    /**
     * Return a label tag
     *
     * @return string
     */
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

    /**
     * Return a string with HTML element attributes
     *
     * @param array $props
     * @return string
     */
    private function _buildAttrs(array $props = [], array $ignore = []): string
    {

        $ret = '';

        $props['type'] = $this->_type;
        $props['name'] = $this->_name;
        $props['autocomplete'] = $props['name'];
        $props['id'] = $this->_getId();

        if ($this->_type == 'select' && $this->_multiple) {
            $props['name'] = $props['name'] . '[]';
        }

        if ($this->_placeholder) {
            $props['placeholder'] = $this->_placeholder;
        }

        if ($this->_help) {
            $props['aria-describedby'] = $this->_getIdHelp();
        }

        $props['class'] = in_array('class-form-control', $ignore) ? '' : " form-control";

        if ($this->_size) {
            $props['class'] .= ' form-control-' . $this->_size;
        }
        $props['class'] .= ' ' . $this->_getValidationFieldClass();

        if (isset($this->_attrs['class'])) {
            $props['class'] .= ' ' . $this->_attrs['class'];
        }

        $props['class'] = trim($props['class']);

        if(!$props['class']) {
            $props['class'] = null;
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

    /**
     * Return a input value
     *
     * @return mixed
     */
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

    /**
     * Check if has a old request
     *
     * @return boolean
     */
    private function _hasOldInput()
    {
        return count((array) old()) != 0;
    }

    /**
     * Return a element id
     *
     * @return string
     */
    private function _getId()
    {
        $id = $this->_id;

        if (!$id && $this->_name) {
            $id = $this->_name;
            if ($this->_type == 'radio') {
                $id .= '-' . str_slug($this->_meta['value']);
            }
        }

        if(!$id) {
            return null;
        }

        return $this->_FidPrefix . $id;
    }

    /**
     * Return a help text id HTML element
     *
     * @return string
     */
    private function _getIdHelp()
    {
        $id = $this->_getId();

        return $id ? 'help-' . $id : '';
    }

    /**
     * Return a help text
     *
     * @return string
     */
    private function _getHelpText(): string
    {
        $id = $this->_getIdHelp();

        return $this->_help ? '<small id="' . $id . '" class="form-text text-muted">' . $this->_e($this->_help) . '</small>' : '';
    }

    /**
     * Return a text with translations, if available
     *
     * @param string $key
     *
     * @return string
     */
    private function _e($key): string
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

    /**
     * Return a checkbox or radio HTML element
     *
     * @return string
     */
    private function _renderCheckboxOrRadio(): string
    {
        $attrs  = $this->_buildAttrs(["class" => "form-check-input", "type" => $this->_type, "value" => $this->_meta['value']]);
        $inline = $this->_checkInline ? ' form-check-inline' : '';
        $label  = $this->_e($this->_label);

        $this->_resetFlags();

        return '<div class="form-check' . $inline . '"><label class="form-check-label"><input ' . $attrs . '>' . $label . '</label></div>';
    }

    /**
     * Return a input with a wrapper HTML markup
     *
     * @param type $field
     * @return string
     */
    private function _renderWarpperCommomField(string $field): string
    {
        $label = $this->_getLabel();
        $help = $this->_getHelpText();
        $error = $this->_getValidationFieldMessage();
        $inline = $this->_checkInlineForm ? true : false;

        $this->_resetFlags();

        if(!$inline)
            return '<div class="form-group ">' . $label . $field . $help . $error . '</div>';
        else
            return '<div class="form-group "><div class="col col-md-3  col-lg-2">' . $label .'</div><div class="col-12 col-md-9  col-lg-10">' . $field . $help . $error . '</div></div>';
    }

    /**
     * Return a validation error message
     *
     * @param string $prefix
     * @param string $sufix
     * @return string|mull
     */
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

    /**
     * Reset input flags
     */
    private function _resetFlags()
    {

        $this->_render = null;
        $this->_meta = [];
        $this->_attrs = [];
        $this->_type = null;
        $this->_url = null;
        $this->_placeholder = null;
        $this->_checkInline = false;
        $this->_checkInlineForm = false;
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

    /**
     * Reset form flags
     */
    private function _resetFormFlags()
    {

        $this->_Flocale = null;
        $this->_Fmethod = 'post';
        $this->_Fmultipart = false;
        $this->_Fdata = null;
        $this->_FidPrefix = '';
    }

}
