<?php

namespace KanxPHP\Controllers;

use KanxPHP\Core\SafeJSON;

class WebpConverterController {

    public function handle($input) {
        // Your RAD logic here
        return SafeJSON::success(['tool' => 'WebpConverter', 'status' => 'ready']);
    }
}
