<?php
namespace Controllers;


use Classes\Controller;
use Classes\Viewer;
use Models\ReportModel;

class ReportController extends GeneralController {
    public function Add(){
        $report_model = new ReportModel();

        $params = [
            "page_title" => "Report new scam to CryptoCheaters.com",
            "report_types" => $report_model->Types()
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("add", $params);
    }

    public function AddProcess(){
        global $POST, $config;

        $this->CheckSessionJSON();

        $report_model = new ReportModel();

        $name = $POST['name'];
        $type = $POST['type'];
        $desc = $POST['desc'];
        $link = $POST['link'];
        $tags = $POST['tags'];
        $evidences = $POST['evidence'];
        $evidences_desc = $POST['evidence_desc'];

        $report_id = $report_model->Create($name, $desc, $type, $tags, $link);

        if ($report_id === false) {
            $this->ReturnJSON(false, "Report not created!", []);
        }

        foreach ($evidences as $key => $evidence) {
            $report_model->AddEvidence($report_id, $_SESSION['current'], $evidence, $evidences_desc[$key]);
        }

        $this->ReturnJSON(true, "OK", ["report_id" => $report_id]);
    }
}