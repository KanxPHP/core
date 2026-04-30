<?php

namespace KanxPHP\Controllers;

use KanxPHP\Core\SafeJSON;

class UserController {

    public function handle($input) {
        // Your RAD logic here
        return SafeJSON::success(['tool' => 'User', 'status' => 'ready']);
    }
}
