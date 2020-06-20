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
        $order = 'votes desc';
        $this->report_model->page_size = 6;
        $params = [
            "page_title" => "CryptoScamAlert.com - Report Crypto Scam",
            "reports" => $this->report_model->Index("1=1", $order, 1),
            "newest" => $this->report_model->Newest(),
            "evidences" => $this->report_model->RandEvidences(10),
            "scripts" => ["metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "css/site.css"]

        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("index", $params);
    }

    public function Terms(){
        $params = [
            "page_title" => "CryptoScamAlert.com - Terms and conditions",
            "scripts" => ["metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "css/site.css"]

        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("terms", $params);
    }

    public function PageNotFound(){
        $params = array(
            "page_title" => "CryptoScamAlert.com - Page not found",
            "scripts" => ["metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "css/site.css"]

        );
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("404", $params);
    }

    public function Page500(){
        $params = array(
            "page_title" => "CryptoScamAlert.com - System error!",
            "scripts" => ["metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "css/site.css"]

        );
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("500", $params);
    }
}