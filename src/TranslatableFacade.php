<?php
namespace DigitalUnityCa\Translatable;

use Illuminate\Support\Facades\Facade;

class TranslatableFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'translatable';
    }
}