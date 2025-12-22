<?php
namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    // puedes añadir cookies a $except si es necesario
    protected $except = [
        //
    ];
}