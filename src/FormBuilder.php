<?php

namespace NetoJose\Bootstrap4Forms;

class FormBuilder {

    /**
     * List of allowed single word
     * values for autocomplete attribute
     *
     * @var array
     */
    private $_allowedAutoComplete;

    /**
     * Form input labels locale
     *
     * @var string
     */
    private $_Flocale;

    /**
     * Form style
     *
     * @var string
     */
    private $_FformStyle;

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
     * Form autocomplete attribute
     * @var string|null
     */
    private $_Fautocomplete;

    /**
     * Input autocomplete attribute
     * @var string|null
     */
    private $_autocomplete;

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
     * Input wrapper attributes
     *
     * @var array
     */
    private $_wrapperAttrs;

	/**
	 * Input wrapper flag
	 *
	 * @var array
	 */
	private $_wrapper;

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
     * Required flag
     *
     * @var boolean
     */
    private $_required;

    /**
     * Input id
     *
     * @var string
     */
    private $_id;

    /**
     * Input class
     *
     * @var string
     */
    private $_class;

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

    /**
     * Input prefix
     *
     * @var string
     */
    private $_prefix;

    /**
     * Input suffix
     *
     * @var string
     */
    private $_suffix;

    public function __construct()
    {
        $this->_resetFlags();
        $this->_resetFormFlags();

        //All allowed single word autocomplete values. If the name of input field matches,
        //they will be automatically set as autocomplete value
        //Flip the array to make searches faster
        $this->_allowedAutoComplete = array_flip(['name', 'honorific-prefix', 'given-name', 'additional-name',
            'family-name', 'honorific-suffix', 'nickname', 'username', 'new-password', 'current-password',
            'organization-title', 'organization', 'street-address', 'address-line1', 'address-line2', 'address-line3',
            'address-level4', 'address-level3', 'address-level2', 'address-level1', 'country', 'country-name',
            'postal-code', 'cc-name', 'cc-given-name', 'cc-additional-name', 'cc-family-name', 'cc-number', 'cc-exp',
            'cc-exp-month', 'cc-exp-year', 'cc-csc', 'cc-type', 'transaction-currency', 'transaction-amount', 'language',
            'bday', 'bday-day', 'bday-month', 'bday-year', 'sex', 'url', 'photo', 'tel', 'tel-country-code', 'tel-national',
            'tel-area-code', 'tel-local', 'tel-local-prefix', 'tel-local-suffix', 'tel-extension', 'email', 'impp']);
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

        if ($this->_FformStyle === 'inline') {
            $props['class'] = 'form-inline';
        }

        if (!is_null($this->_Fautocomplete)) {
            $props['autocomplete'] = $this->_Fautocomplete;
        }

        $attrs = $this->_buildAttrs($props, ['class-form-control']);

        $ret = '<form ' . $attrs . '>';

        if ($this->_Fmethod !== 'get') {
            $ret .= csrf_field();

            if ($this->_Fmethod !== 'post' && $this->_Fmethod !== 'get') {
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
        $this->_class .= ' form-group';
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
        $id = $this->_getId();
        $placeholder = $this->_placeholder ?: 'Choose file';

        $input = '<div class="custom-file"><input ' . $attrs . '><label class="custom-file-label" for="' . $id . '">' . $placeholder . '</label></div>';

        return $this->_renderWrapperCommonField($input);
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
     * Return a range input tag
     *
     * @return string
     */
    public function range(): string
    {
        return $this->_renderInput('range');
    }

    /**
     * Return a date input tag
     *
     * @return string
     */
    public function date(): string
    {
        return $this->_renderInput('date');
    }

    /**
     * Return a time input tag
     *
     * @return string
     */
    public function time(): string
    {
        return $this->_renderInput('time');
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
     * Return a tel input tag
     *
     * @return string
     */
    public function tel(): string
    {
        return $this->_renderInput('tel');
    }

    /**
     * Return a url input tag
     *
     * @return string
     */
    public function url(): string
    {
        return $this->_renderInput('url');
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

        return $this->_renderWrapperCommonField('<textarea ' . $attrs . '>' . $value . '</textarea>');
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

        return $this->_renderWrapperCommonField('<select ' . $attrs . '>' . $options . '</select>');
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

        return $this->_renderWrapperCommonField('<input ' . $attrs . '>');
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
            $this->_class .= ' ' . $cls . $disabled;
            $attrs = $this->_buildAttrs(
                [
                    'href' => $href,
                    'role' => 'button',
                    'aria-disabled' => $disabled ? 'true' : null
                ], ['class-form-control']
            );
            $ret = '<a ' . $attrs . '>' . $value . '</a>';
        } else {
            $this->_class .= ' ' . $cls;
            $attrs = $this->_buildAttrs(['type' => $this->_type], ['class-form-control']);
            $ret = '<button ' . $attrs . ' ' . $disabled . '>' . $value . '</button>';
        }

        return $this->_renderWrapperCommonField($ret, true);
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

            $classStr = '';
            if ($this->_FformStyle === 'inline') {
                $classStr = ' class="sr-only"';
            } elseif($this->_FformStyle === 'horizontal') {
            	$classStr = ' class="col-sm-2 col-form-label"';
            }

            $id = $this->_getId();
            $result = '<label for="' . $id . '"'.$classStr.'>' . $this->_e($label) . '</label>';
        }

        return $result;
    }

    /**
     * Return a string with HTML element attributes
     *
     * @param array $props
     * @param array $ignore
     * @return string
     */
    private function _buildAttrs(array $props = [], array $ignore = []): string
    {
        $props = array_merge($props,
                             array_filter($this->_attrs, function($k){
                                          return $k != 'class';
                                         }, ARRAY_FILTER_USE_KEY));

        if($this->_type){
            $props['type'] = $this->_type;
        }

        if($this->_name){
            $props['name'] = $this->_name;
        }

        if (!is_null($this->_autocomplete)) {
            $props['autocomplete'] = $this->_autocomplete;
        } else if ($this->_name && isset($this->_allowedAutoComplete[$this->_name])) {
            $props['autocomplete'] = $this->_name;
        }

        $id = $this->_getId();
        if($id){
            $props['id'] = $this->_getId();
        }

        $props['class'] = $this->_class ?: '';

        if ($this->_type == 'select' && $this->_multiple && $this->_name) {
            $props['name'] = $props['name'] . '[]';
        }

        if ($this->_placeholder) {
            $props['placeholder'] = $this->_placeholder;
        } elseif($this->_FformStyle === 'inline') {
	        $props['placeholder'] = $this->_label;
        }

        if ($this->_help) {
            $props['aria-describedby'] = $this->_getIdHelp();
        }

        if($this->_required === true) {
            $props['required'] = true;
        }

        switch($this->_type) {
            case 'file':
                $formControlClass = ' custom-file-input';
                break;
            case 'range':
                $formControlClass = ' form-control-range';
                break;
            default:
                $formControlClass = ' form-control';
                break;
        }

        if (!in_array('class-form-control', $ignore)) {
            $props['class'] .= $formControlClass;
        }

        if ($this->_size) {
            $props['class'] .= ' '.$formControlClass.'-' . $this->_size;
        }

        if ($this->_FformStyle === 'inline') {
            $props['class'] .= ' mb-2 mr-sm-2';
        }

        $props['class'] .= ' ' . $this->_getValidationFieldClass();

        if (isset($this->_attrs['class'])) {
            $props['class'] .= ' ' . $this->_attrs['class'];
        }

        $props['class'] = trim($props['class']);

        if(!$props['class']) {
            $props['class'] = false;
        }

        if ($this->_type == 'select' && $this->_multiple) {
            $props['multiple'] = true;
        }

        if ($this->_readonly) {
            $props['readonly'] = true;
        }

        if ($this->_disabled) {
            $props['disabled'] = true;
        }

        if (in_array($this->_type, ['radio', 'checkbox'])) {
            $value = $this->_getValue();
            if (
                    $value && (
                    $this->_type === 'checkbox' || $this->_type === 'radio' && $value === $this->_meta['value']
                    )
            ) {
                $props['checked'] = true;
            }
        }

        if ($this->_type == 'hidden') {
            $props['autocomplete'] = false;
            $props['class'] = false;
        }

        return $this->_arrayToHtmlAttrs($props);
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
            if ($this->_type === 'radio') {
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
     * Return a prefix id HTML element
     *
     * @return string
     */
    private function _getIdPrefix()
    {
        $id = $this->_getId();

        return $id ? 'prefix-' . $id : '';
    }

    /**
     * Return a prefix
     *
     * @return string
     */
    private function _getPrefix(): string
    {
        $id = $this->_getIdPrefix();

        return $this->_prefix ? '  <div class="input-group-prepend"><span class="input-group-text" id="' . $id . '">' . $this->_e($this->_prefix) . '</span></div>' : '';
    }

    /**
     * Return a suffix id HTML element
     *
     * @return string
     */
    private function _getIdSuffix()
    {
        $id = $this->_getId();

        return $id ? 'suffix-' . $id : '';
    }

    /**
     * Return a suffix
     *
     * @return string
     */
    private function _getSuffix(): string
    {
        $id = $this->_getIdSuffix();

        return $this->_suffix ? '  <div class="input-group-append"><span class="input-group-text" id="' . $id . '">' . $this->_e($this->_suffix) . '</span></div>' : '';
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
        $this->_class .= ' custom-control-input';
        $attrs = $this->_buildAttrs(["type" => $this->_type, "value" => $this->_meta['value']], ['class-form-control']);
        $inline = $this->_checkInline ? ' form-check-inline' : '';
        $label  = $this->_e($this->_label);
        $id = $this->_getId();
        $type = $this->_type;

        return $this->_renderWrapperCommonField('<div class="custom-control custom-' . $type . ' ' . $inline . '"><input ' . $attrs . '><label class="custom-control-label" for="' . $id . '">' . $label . '</label></div>', true);
    }

    private function _arrayToHtmlAttrs($attributes){
        return join(' ', array_map(function($key) use ($attributes) {
            $value = $attributes[$key];
            if(is_bool($value)){
                return $value ? $key : '';
            } else {
                return $key.'="'.htmlspecialchars($value).'"';
            }
            }, array_keys($attributes))
        );
    }

	/**
	 * Return a input with a wrapper HTML markup
	 *
	 * @param string $field
	 * @param bool $hide_label
	 * @return string
	 */
    private function _renderWrapperCommonField(string $field, $hide_label = false): string
    {
        $label = $hide_label ? '' : $this->_getLabel();
        $help = $this->_getHelpText();
        $prefix = $this->_getPrefix();
        $suffix = $this->_getSuffix();
        $error = $this->_getValidationFieldMessage();

	    $formGroupOpen = $formGroupClose = '';
        if ($this->_wrapper && $this->_FformStyle !== 'inline') {
	        $classList = isset($this->_wrapperAttrs['class']) ? $this->_wrapperAttrs['class'] : '';
	        $this->_wrapperAttrs['class'] = "form-group " . $classList;

	        if ($this->_FformStyle === 'horizontal'  && !$hide_label) {
		        $this->_wrapperAttrs['class'] .= ' row';
	        }

	        $wrapperAttrs = $this->_arrayToHtmlAttrs($this->_wrapperAttrs);

	        $formGroupOpen = '<div ' . $wrapperAttrs . '>';
	        $formGroupClose = '</div>';
        }

	    $inputGroupOpen = $inputGroupClose = '';

	    if ($this->_wrapper && $this->_FformStyle === 'horizontal' && !$hide_label) {
		    $inputGroupOpen .= '<div class="col-sm-10">';
		    $inputGroupClose .= '</div>';
	    }

        if ($prefix || $suffix) {
            $inputGroupOpen .= '<div class="input-group">';
            $inputGroupClose .= '</div>';
        }

	    $this->_resetFlags();

	    return $formGroupOpen . $label . $inputGroupOpen . $prefix . $field . $suffix . $inputGroupClose . $help . $error . $formGroupClose;
    }

    /**
     * Return a validation error message
     *
     * @param string $prefix
     * @param string $sufix
     * @return string|null
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
        $this->_required = null;
        $this->_wrapper = true;
        $this->_render = null;
        $this->_meta = [];
        $this->_attrs = [];
        $this->_wrapperAttrs = [];
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
        $this->_class = null;
        $this->_prefix = null;
        $this->_suffix = null;
        $this->_color = "primary";
        $this->_outline = false;
        $this->_block = false;
        $this->_value = null;
        $this->_multiple = false;
        $this->_autocomplete = null;
    }

    /**
     * Reset form flags
     */
    private function _resetFormFlags()
    {

        $this->_Flocale = null;
        $this->_Fmethod = 'post';
        $this->_Fmultipart = false;
        $this->_FformStyle = 'standard';
        $this->_Fdata = null;
        $this->_FidPrefix = '';
        $this->_Fautocomplete = null;
    }

}
