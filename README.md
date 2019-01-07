# Bootstrap 4 forms for Laravel 5

This is a package for creating Bootstrap 4 styled form elements in Laravel 5.

## Features

-   Labels
-   Error messages
-   Bootstrap 4 markup and classes (including state, colors, and sizes)
-   Error validation messages
-   Form fill (using Model instance, array or after form submission when a validation error occurs)
-   Internationalization
-   Add parameters using php chaining approach
-   Zero dependences (no Laravel Collective dependency)

## Introduction

### Before

```html
<div class="form-group">
    <label for="username">Username</label>
    <input
        type="text"
        class="form-control @if($errors->has('username')) is-invalid @endif "
        id="username"
        value="{{old('username', $username)}}"
    />
    @if($errors->has('username'))
    <div class="invalid-feedback">{{$errors->first('username')}}</div>
    @endif
</div>
```

### After

```php
Form::text('username', 'Username')
```

## Installation

#### Require the package using Composer.

```bash
composer require netojose/laravel-bootstrap-4-forms
```

### Laravel 5.5 or above

If you is using Laravel 5.5, the auto discovery feature will make everything for you and your job is done, you can start using now. Else, follow the steps below to install.

### Laravel 5.4

#### Add the service provider to your config/app.php file

```php
'providers' => [
    //...
	NetoJose\Bootstrap4Forms\Bootstrap4FormsServiceProvider::class,
],
```

#### Add the BootForm facade to the aliases array in config/app.php:

```php
'aliases' => [
    //...
    'Form' => NetoJose\Bootstrap4Forms\Bootstrap4FormsFacade::class,
],
```

## Usage

### Basic form controls

#### Opening and closing a form

```php
// Opening a form using POST method

{!!Form::open()!!}
// ... Form components here
{!!Form::close()!!}
```

> Opening the form will add \_token field automatically for you

#### Inline form

```php
// Making all inputs inline
{!!Form::inlineForm()!!}
```

#### Fieldset

| Param    | Type   | Default | Description     |
| -------- | ------ | ------- | --------------- |
| \$legend | string | null    | Fieldset Legend |

```php
// Example
{!!Form::fieldsetOpen('Legend title')!!}
// ... fieldset content
{!!Form::fieldsetClose()!!}
```

### Basic inputs

#### Text inputs

| Param     | Type   | Default | Description   |
| --------- | ------ | ------- | ------------- |
| \$name    | string | null    | Input name    |
| \$label   | string | null    | Input label   |
| \$default | string | null    | Default value |

```php
// Example
{!!Form::text('name', 'User name')!!}
```

##### Textarea

| Param     | Type   | Default | Description   |
| --------- | ------ | ------- | ------------- |
| \$name    | string | null    | Input name    |
| \$label   | string | null    | Input label   |
| \$default | string | null    | Default value |

```php
// Example
{!!Form::textarea('description', 'Description')!!}
```

##### Select

| Param     | Type   | Default | Description    |
| --------- | ------ | ------- | -------------- |
| \$name    | string | null    | Input name     |
| \$label   | string | null    | Input label    |
| \$options | array  | []      | Select options |
| \$default | string | null    | Default value  |

```php
// Example
{!!Form::select('city', 'Choose your city', [1 => 'Gotham City', 2 => 'Springfield'])!!}
```

#### Select Default Value

```php
{!!Form::select('city', 'Choose your city', [''=>'--choose your city---',1 => 'Gotham City', 2 => 'Springfield'])!!}
```

##### Checkbox

| Param     | Type    | Default | Description   |
| --------- | ------- | ------- | ------------- |
| \$name    | string  | null    | Input name    |
| \$label   | string  | null    | Input label   |
| \$value   | string  | null    | Input value   |
| \$default | boolean | null    | Default value |

```php
// Example
{!!Form::checkbox('orange', 'Orange')!!}
```

##### Radio

| Param     | Type    | Default | Description   |
| --------- | ------- | ------- | ------------- |
| \$name    | string  | null    | Input name    |
| \$label   | string  | null    | Input label   |
| \$value   | string  | null    | Input value   |
| \$default | boolean | null    | Default value |

```php
// Example
{!!Form::radio('orange', 'Orange')!!}
```

##### File

| Param   | Type   | Default | Description |
| ------- | ------ | ------- | ----------- |
| \$name  | string | null    | Input name  |
| \$label | string | null    | Input label |

```php
// Example
{!!Form::file('doc', 'Document')!!}
```

#### Date inputs

| Param     | Type   | Default | Description   |
| --------- | ------ | ------- | ------------- |
| \$name    | string | null    | Input name    |
| \$label   | string | null    | Input label   |
| \$default | string | null    | Default value |

```php
// Example
{!!Form::date('birthday', 'Birthday')!!}
```

#### Time inputs

| Param     | Type   | Default | Description   |
| --------- | ------ | ------- | ------------- |
| \$name    | string | null    | Input name    |
| \$label   | string | null    | Input label   |
| \$default | string | null    | Default value |

```php
// Example
{!!Form::time('hour', 'Meeting hour')!!}
```

#### Range inputs

| Param     | Type   | Default | Description   |
| --------- | ------ | ------- | ------------- |
| \$name    | string | null    | Input name    |
| \$label   | string | null    | Input label   |
| \$default | string | null    | Default value |

```php
// Example
{!!Form::range('name', 'User name')!!}
```

##### Hidden

| Param     | Type    | Default | Description   |
| --------- | ------- | ------- | ------------- |
| \$name    | string  | null    | Input name    |
| \$default | boolean | null    | Default value |

```php
// Example
{!!Form::hidden('user_id')!!}
```

##### Anchor

| Param   | Type   | Default | Description |
| ------- | ------ | ------- | ----------- |
| \$value | string | null    | Anchor text |
| \$url   | string | null    | Anchor url  |

```php
// Example
{!!Form::anchor("Link via parameter", 'foo/bar')!!}
```

##### Buttons

| Param   | Type   | Default | Description  |
| ------- | ------ | ------- | ------------ |
| \$value | string | null    | Button value |
| \$color | string | null    | Button color |
| \$size  | string | null    | button size  |

###### Submit

```php
// Example
{!!Form::submit("Send form")!!}
```

###### Button

```php
// Example
{!!Form::button("Do something", "warning", "lg")!!}
```

###### Reset

```php
// Example
{!!Form::reset("Clear form")!!}
```

### Chainable methods

> This package uses [chaining](https://en.wikipedia.org/wiki/Method_chaining) feature, allowing easly pass more parameters.

### Filling a form

| Param  | Type   | Default | Description |
| ------ | ------ | ------- | ----------- |
| \$data | object | array   | null        | DAta fo fill form inputs |

```php
// Examples

// With initial data using a Model instance
$user = User::find(1);
{!!Form::open()->fill($user)!!}

// With initial array data
$user = ['name' => 'Jesus', 'age' => 33];
{!!Form::open()->fill($user)!!}
```

### Url

Use in anchors and forms openings

| Param | Type   | Default | Description |
| ----- | ------ | ------- | ----------- |
| \$url | string | null    | Url         |

```php
// Example
{!!Form::anchor("Link via url")->url('foo/bar')!!}
```

### Route

Use in anchors and forms openings

| Param   | Type   | Default | Description |
| ------- | ------ | ------- | ----------- |
| \$route | string | null    | Route name  |

```php
// Example
{!!Form::anchor("Link via route")->route('home')!!}
```

### Checked

Set the checkbox/radio checked status

| Param     | Type    | Default | Description    |
| --------- | ------- | ------- | -------------- |
| \$checked | boolean | true    | Checked status |

```php
// Examples

// Using readonly field
{!!Form::checkbox('agree', 'I agree')->checked()!!}

// You can use FALSE to turn off checked status
{!!Form::checkbox('agree', 'I agree')->checked(false)!!}
```

### Inline

Set the checkbox/radio checked status

```php
// Examples
{!!Form::radio('orange', 'Orange')->inline()!!}

{!!Form::checkbox('orange', 'Orange')->inline()!!}
```

### Placeholder

| Param         | Type   | Default | Description      |
| ------------- | ------ | ------- | ---------------- |
| \$placeholder | string | null    | Placeholder text |

```php
// Example
{!!Form::text('name', 'Name')->placeholder('Input placeholder')!!}
```

### Select Multiple

```php
// Example
{!!Form::select('city', 'Choose your city', [1 => 'Gotham City', 2 => 'Springfield'])->multiple()!!}
```

### Locale

Using locale, the package will look for a resources/lang/{CURRENT_LANG}/forms/user.php language file and uses labels and help texts as keys for replace texts

```php
// Example
{!!Form::open()->locale('forms.user')!!}
```

### Help Text

| Param  | Type   | Default | Description |
| ------ | ------ | ------- | ----------- |
| \$text | string | null    | Help text   |

```php
// Example
{!!Form::text('name', 'Name')->help('Help text here')!!}
```

### Custom attributes

| Param   | Type  | Default | Description             |
| ------- | ----- | ------- | ----------------------- |
| \$attrs | array | []      | Custom input attributes |

```php
// Example
{!!Form::text('name', 'Name')->attrs(['data-foo' => 'bar', 'rel'=> 'baz'])!!}
```

### Custom attributes in wrapper div (\<div class="form-group">...\</div>)

| Param   | Type  | Default | Description             |
| ------- | ----- | ------- | ----------------------- |
| \$attrs | array | []      | Custom input attributes |

```php
// Example
{!!Form::text('name', 'Name')->wrapperAttrs(['data-foo' => 'bar', 'id'=> 'name-wrapper'])!!}
```

### Readonly

| Param    | Type    | Default | Description      |
| -------- | ------- | ------- | ---------------- |
| \$status | boolean | true    | Read only status |

```php
// Examples

// Using readonly field
{!!Form::text('name', 'Name')->readonly()!!}

// You can use FALSE to turn off readonly status
{!!Form::text('name', 'Name')->readonly(false)!!}
```

### Disabled

| Param    | Type    | Default | Description     |
| -------- | ------- | ------- | --------------- |
| \$status | boolean | true    | Disabled status |

```php
// Examples

// Disabling a field
{!!Form::text('name', 'Name')->disabled()!!}

// Disabling a fieldset
{!!Form::fieldsetOpen('User data')->disabled()!!}

// You can use FALSE to turn off disabled status
{!!Form::text('name', 'Name')->disabled(false)!!}
```

### Block

| Param    | Type    | Default | Description     |
| -------- | ------- | ------- | --------------- |
| \$status | boolean | true    | Disabled status |

```php
// Examples

// Disabling a field
{!!Form::text('name', 'Name')->block()!!}

// You can use FALSE to turn off block status
{!!Form::text('name', 'Name')->block(false)!!}
```

### Required

| Param    | Type    | Default | Description     |
| -------- | ------- | ------- | --------------- |
| \$status | boolean | true    | Required status |

```php
// Examples

// Disabling a field
{!!Form::text('name', 'Name')->required()!!}

// Disabling a fieldset
{!!Form::fieldsetOpen('User data')->required()!!}

// You can use FALSE to turn off required status
{!!Form::text('name', 'Name')->required(false)!!}
```

### AutoFill

| Param   | Type   | Default | Description       |
| ------- | ------ | ------- | ----------------- |
| \$value | string | 'on'    | autocomplte value |

see: https://html.spec.whatwg.org/multipage/forms.html#autofill

If no autocomplete value is specified on the form, html spec requires
a default value of 'on'. So, you must explicitly turn it off.

Autocomplete values will be automatically generated for fields with
single word names matching valid values (e.g. name, email, tel, organization). The
complete list is in the spec mentioned above.

```php
// Examples

// Switch off autocomplete for the form
{!!Form::open()->autocomplete('off')!!}

// Explicitly set a autocomplete value
{!!Form::text('mobile', 'Mobile Number')->autocomplete('tel')!!}

// Disable autocomplete for fields with valid names
{!!Form::text('name', 'Name')->autocomplete('off')!!}
```

### Id

| Param | Type   | Default | Description |
| ----- | ------ | ------- | ----------- |
| \$id  | string | null    | Id field    |

```php
// Example
{!!Form::text('name', 'Name')->id('user-name')!!}
```

### Id prefix

| Param    | Type   | Default | Description |
| -------- | ------ | ------- | ----------- |
| \$prefix | string | null    | Id prefix   |

```php
// Example
{!!Form::open()->idPrefix('register')!!}
```

### Multipart

| Param       | Type    | Default | Description    |
| ----------- | ------- | ------- | -------------- |
| \$multipart | boolean | true    | Multipart flag |

```php
// Examples
{!!Form::open()->multipart()!!}

// You can use FALSE to turn off multipart
{!!Form::open()->multipart(false)!!}
```

### Method

| Param    | Type   | Default | Description |
| -------- | ------ | ------- | ----------- |
| \$method | string | null    | HTTP method |

```php
// Examples
{!!Form::open()->method('get')!!}
{!!Form::open()->method('post')!!}
{!!Form::open()->method('put')!!}
{!!Form::open()->method('patch')!!}
{!!Form::open()->method('delete')!!}
```

### explicit HTTP verbs

```php
// Examples
{!!Form::open()->get()!!}
{!!Form::open()->post()!!}
{!!Form::open()->put()!!}
{!!Form::open()->patch()!!}
{!!Form::open()->delete()!!}
```

### Color

| Param   | Type   | Default | Description |
| ------- | ------ | ------- | ----------- |
| \$color | string | null    | Color name  |

```php
// Examples
{!!Form::button("Do something")->color("warning")!!}

{!!Form::button("Do something")->color("primary")!!}
```

### explicit color

```php
// Examples
{!!Form::button("Button label")->warning()!!}
{!!Form::button("Button label")->outline()!!}
{!!Form::button("Button label")->success()!!
{!!Form::button("Button label")->danger()!!}
{!!Form::button("Button label")->secondary()!!}
{!!Form::button("Button label")->info()!!}
{!!Form::button("Button label")->light()!!}
{!!Form::button("Button label")->dark()!!}
{!!Form::button("Button label")->link()!!}
```

### Size

| Param  | Type   | Default | Description |
| ------ | ------ | ------- | ----------- |
| \$size | string | null    | Size name   |

```php
// Examples
{!!Form::button("Do something")->size("sm")!!}

{!!Form::button("Do something")->size("lg")!!}
```

### Explicit size

```php
// Examples
{!!Form::button("Button label")->sm()!!}
{!!Form::button("Button label")->lg()!!}
```

### Type

| Param  | Type   | Default | Description |
| ------ | ------ | ------- | ----------- |
| \$type | string | null    | Type field  |

```php
// Examples

// Password field
{!!Form::text('password', 'Your password')->type('password')!!}

// Number field
{!!Form::text('age', 'Your age')->type('number')!!}

// Email field
{!!Form::text('email', 'Your email')->type('email')!!}
```

### Name

| Param  | Type   | Default | Description |
| ------ | ------ | ------- | ----------- |
| \$name | string | null    | Input name  |

```php
// Examples
{!!Form::text('text')->name('name')!!}
```

### Label

| Param   | Type   | Default | Description |
| ------- | ------ | ------- | ----------- |
| \$label | string | null    | Input label |

```php
// Examples
{!!Form::text('age')->label('Your age')!!}
```

### Default Value

| Param   | Type  | Default | Description |
| ------- | ----- | ------- | ----------- |
| \$value | mixed | null    | Input value |

```php
// Example
{!!Form::text('name', 'Your name')->value('Maria')!!}
```

### Render

| Param    | Type   | Default | Description |
| -------- | ------ | ------- | ----------- |
| \$render | string | null    | Render name |

```php
// Examples

// Number field
{!!Form::render('text')->name('age')->label('Your age')!!}
```

### Chaining properties

You can use chaining feature to use a lot of settings for each component

```php
// Examples

{!!Form::open()->locale('forms.user')->put()->multipart()->route('user.add')->data($user)!!}

{!!Form::text('name', 'Name')->placeholder('Type your name')->lg()!!}

{!!Form::anchor("Link as a button")->sm()->info()->outline()!!}

{!!Form::submit('Awesome button')->id('my-btn')->disabled()->danger()->lg()!!}

{!!Form::close()!!}
```
