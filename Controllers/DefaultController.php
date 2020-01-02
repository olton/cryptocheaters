<?php
namespace Controllers;


use Classes\Controller;
use Classes\Viewer;

class DefaultController extends GeneralController {
    public function Index(){
        $params = [
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("index", $params);
    }

    public function PageNotFound(){
        $params = array(
            "page_title" => "CryptoCheaters.com - Page not found"
        );
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("404", $params);
    }

    public function Page500(){
        $params = array(
            "page_title" => "CryptoCheaters.com - System error!"
        );
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("500", $params);
    }
}