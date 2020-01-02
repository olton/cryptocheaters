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
}