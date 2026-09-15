<?php

namespace TomatoPHP\FilamentLogger\Tests\Models;

use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifiableUser extends User implements MustVerifyEmail
{
    use MustVerifyEmailTrait;

    protected $table = 'users';
}
