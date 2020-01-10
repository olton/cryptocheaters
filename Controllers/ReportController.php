<?php
namespace Controllers;


use Classes\Controller;
use Classes\Viewer;
use Models\ReportModel;

class ReportController extends GeneralController {
    private $report_model;

    public function __construct() {
        $this->report_model = new ReportModel();
    }

    public function Index(){
        $params = [
            "page_title" => "Scam reports on CryptoCheaters.com",
            "reports" => $this->report_model->Index()
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("scams", $params);
    }

    public function Report($id){
        $params = [
            "page_title" => "Report new scam to CryptoCheaters.com",
            "report" => $this->report_model->Report($id),
            "evidences" => $this->report_model->Evidences($id),
            "scripts" => ["markdown-it/markdown-it.js"]
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("report", $params);
    }

    public function Add(){
        $params = [
            "page_title" => "Report new scam to CryptoCheaters.com",
            "report_types" => $this->report_model->Types()
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("add", $params);
    }

    public function AddProcess(){
        global $POST, $config;

        $this->CheckSessionJSON();

        $name = $POST['name'];
        $type = $POST['type'];
        $desc = $POST['desc'];
        $short = $POST['short'];
        $link = $POST['link'];
        $tags = $POST['tags'];
        $evidences = $POST['evidence'];
        $evidences_desc = $POST['evidence_desc'];

        $report_id = $this->report_model->Create($name, $short, $desc, $type, $tags, $link);

        if ($report_id === false) {
            $this->ReturnJSON(false, "Report not created!", []);
        }

        foreach ($evidences as $key => $evidence) {
            $this->report_model->AddEvidence($report_id, $_SESSION['current'], $evidence, $evidences_desc[$key]);
        }

        $this->ReturnJSON(true, "OK", ["report_id" => $report_id]);
    }
}