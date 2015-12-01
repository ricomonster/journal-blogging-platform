<?php

namespace Journal\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Artisan;
use Schema;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;
}
