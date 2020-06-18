<?php
namespace Controllers;


use Classes\Controller;
use Classes\SimpleEmail;
use Classes\Viewer;
use Models\ReportModel;
use Models\RequestModel;

class DefaultController extends GeneralController {
    private $report_model;

    public function __construct(){
        $this->report_model = new ReportModel();
    }

    public function Index(){
        $this->report_model->page_size = 6;
        $params = [
            "page_title" => "Report Crypto Scam",
            "reports" => $this->report_model->Index("1=1", 1),
            "newest" => $this->report_model->Newest(),
            "evidences" => $this->report_model->RandEvidences(10)
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("index", $params);
    }

    public function Terms(){
        $params = [
            "page_title" => "CryptoCheaters.com - Terms and conditions"
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("terms", $params);
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