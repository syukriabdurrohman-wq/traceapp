<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function authService(bool $getShared = true): \App\Services\AuthService
    {
        if ($getShared) {
            /** @var \App\Services\AuthService */
            return static::getSharedInstance('authService');
        }

        return new \App\Services\AuthService();
    }

    public static function tokenService(bool $getShared = true): \App\Services\TokenService
    {
        if ($getShared) {
            /** @var \App\Services\TokenService */
            return static::getSharedInstance('tokenService');
        }

        return new \App\Services\TokenService();
    }
}
