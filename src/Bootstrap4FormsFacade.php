<?php

namespace NetoJose\Bootstrap4Forms;

use \Illuminate\Support\Facades\Facade;

class Bootstrap4FormsFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'bootstrap4-form';
    }

}
