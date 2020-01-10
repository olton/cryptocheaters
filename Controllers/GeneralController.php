<?php

namespace Controllers;

use Classes\Controller;
use Classes\Url;

class GeneralController extends Controller {
    public function CheckSession(){
        if (!$_SESSION['current']) {
            Url::Redirect("/");
            exit(0);
        }
    }

    public function CheckSessionJSON(){
        if (!$_SESSION['current']) {
            $this->ReturnJSON(false, "Session expired", []);
            exit(0);
        }
    }

    public function CheckSessionAdmin(){
        if (!$_SESSION['current'] || intval($_SESSION['user']['admin']) !== 1) {
            $this->ReturnJSON(false, "Admin role required!", []);
            exit(0);
        }
    }
}