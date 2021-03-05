<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class RefreshTestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;
    // https://laravel.com/docs/8.x/database-testing#running-seeders
    protected $seed = true;
}
