<?php

namespace App\Custom;

use Illuminate\Foundation\Application;

class EnvironmentPathCustomization extends Application
{
    /**
     * The custom environment path defined by the developer.
     *
     * @var string
     */
    // protected $environmentPath = '/var/www/backend/..';  NOT WORKS!
    // set $app->useEnvironmentPath(base_path('..')); in /bootstrap/app.php Application instance

}
