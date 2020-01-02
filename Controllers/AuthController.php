<?php
namespace Controllers;


use Classes\Controller;
use Classes\Viewer;

class AuthController extends GeneralController {
    public function Login(){
        $params = [
            "page_title" => ""
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("login", $params);
    }

    public function Signup(){
        $params = [
            "page_title" => ""
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("signup", $params);
    }
}