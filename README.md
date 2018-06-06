# Bootstrap 4 forms for Laravel 5

This is a package for creating Bootstrap 4 styled form elements in Laravel 5.

## Features

*   Labels
*   Error messages
*   Bootstrap 4 markup and classes (including state, colors, and sizes)
*   Error validation messages
*   Form fill (using Model instance, array or after form submission when a validation error occurs)
*   Internationalization
*   Add parameters using php chaining approach
*   Zero dependences (without Laravel Collective)

## Introduction

### Before

```html
<div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control @if($errors->has('username')) is-invalid @endif " id="username" value="{{old('username', $username)}}">
    @if($errors->has('username'))
    <div class="invalid-feedback">
        {{$errors->first('username')}}
    </div>
    @endif
</div>
```

### After

```php
Form::text('username', 'Username', $username)
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

### Opening a form

```php
// Opening a form using POST method
{!!Form::open()!!}

// Using a different method (get, put, patch, delete)
{!!Form::open()->get()!!}

// With multipart
{!!Form::open()->multipart()!!}

// With custom route
{!!Form::open()->route('route.name')!!}

// With url
{!!Form::open()->url('user/add')!!}

// With initial data using a Model instance
$user = User::find(1);
{!!Form::open()->fill($user)!!}

// With initial array data
$user = ['name' => 'Jesus', 'age' => 33];
{!!Form::open()->fill($user)!!}

// With locale (look for a resources/lang/{CURRENT_LANG}/forms/user.php language file and uses labels and help texts as keys for replace texts)
{!!Form::open()->locale('forms.user')!!}
```

### Closing a form

```php
{!!Form::close()!!}
```

### Fieldset

```php
{!!Form::fieldsetOpen('Legend title')!!}
// ... fieldset content
{!!Form::fieldsetClose()!!}
```

### Text inputs

```php
{!!Form::text('name', 'User name')!!}
```

### Textarea inputs

```php
{!!Form::textarea('description', 'Description')!!}
```

### Select inputs

```php
{!!Form::select('city', 'Choose your city', [1 => 'Gotham City', 2 => 'Springfield'])!!}

// Using a select multiple
{!!Form::select('city', 'Choose your city', [1 => 'Gotham City', 2 => 'Springfield'])->multiple()!!}
```

### Checkbox inputs

```php
{!!Form::checkbox('orange', 'Orange')!!}

// With custom value (default is on)
{!!Form::checkbox('orange', 'Orange', 'yes')->inline()!!}

// Inline
{!!Form::checkbox('orange', 'Orange')->inline()!!}
```

### Radio inputs

```php
{!!Form::radio('orange', 'Orange')!!}

// With custom value (default is on)
{!!Form::radio('orange', 'Orange', 'yes')->inline()!!}

// Inline
{!!Form::radio('orange', 'Orange')->inline()!!}
```

### Hidden inputs

```php
{!!Form::hidden('user_id')!!}
```

### Placeholder

```php
{!!Form::text('name', 'Name')->placeholder('Input placeholder')!!}
```

### Help Text

```php
{!!Form::text('name', 'Name')->help('Help text here')!!}
```

### Button

```php
// Submit button
{!!Form::submit("Send form")!!}

// Reset button
{!!Form::reset("Reset form button")!!}

// Warning button
{!!Form::button("Button label")->warning()!!}

// Outline button
{!!Form::button("Button label")->outline()!!}

// Success button
{!!Form::button("Button label")->success()!!}

// Danger button
{!!Form::button("Button label")->danger()!!}

// Secondary button
{!!Form::button("Button label")->secondary()!!}

// Info button
{!!Form::button("Button label")->info()!!}

// Light button
{!!Form::button("Button label")->light()!!}

// Dark button
{!!Form::button("Button label")->dark()!!}

// Link button
{!!Form::button("Button label")->link()!!}

// Small button
{!!Form::button("Button label")->sm()!!}

// Large button
{!!Form::button("Button label")->lg()!!}
```

### Custom parameters

```php
{!!Form::text('name', 'Name')->params(['data-foo' => 'bar', 'rel'=> 'baz'])!!}
```

### Anchor

```php
{!!Form::anchor("Link via parameter", 'foo/bar')!!}
{!!Form::anchor("Link via url")->url('foo/bar')!!}
{!!Form::anchor("Link via route")->route('home')!!}
```

### Readonly

```php
// Using readonly field
{!!Form::text('name', 'Name')->readonly()!!}

// You can use FALSE to turn off readonly status
{!!Form::text('name', 'Name')->readonly(false)!!}
```

### Disabled

```php
// Disabling a field
{!!Form::text('name', 'Name')->disabled()!!}

// Disabling a fieldset
{!!Form::fieldsetOpen('User data')->disabled()!!}

// You can use FALSE to turn off disabled status
{!!Form::text('name', 'Name')-> disabled(false)!!}
```

### Id

```php
{!!Form::text('name', 'Name')->id('user-name')!!}
```

### Type

```php
{!!Form::text('age', 'Age')->type('number')!!}
{!!Form::text('email', 'Email')->type('email')!!}
```

### Chaining properties

```php
// You can use chaining feature to use a lot of settings for each component

{!!Form::open()->locale('forms.user')->put()->multipart()->route('user.add')->data($user)!!}

{!!Form::text('name', 'Name')->placeholder('Type your name')->lg()!!}

{!!Form::anchor("Link as a button")->sm()->info()->outline()!!}

{!!Form::submit('Awesome button')->id('my-btn')->disabled()->danger()->lg()!!}

{!!Form::close()!!}
```
