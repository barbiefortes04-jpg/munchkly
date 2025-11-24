<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\RequirePassword as Middleware;

class ValidateSignature extends Middleware
{
    /**
     * The names of the query string parameters that should be ignored.
     *
     * @var array<int, string>
     */
    protected $ignore = [
        //
    ];
}