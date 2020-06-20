<?php
namespace Controllers;


use Classes\Controller;
use Classes\Viewer;
use Models\ReportModel;

class ReportController extends GeneralController {
    private $report_model;
    private $report_order;

    public function __construct() {
        $this->report_model = new ReportModel();
        $this->report_order = ['report_name', 'report_type', 'votes', 'report_created', 'nickname'];
    }

    public function Index(){
        global $GET;

        $page = isset($GET['page']) ? intval($GET['page']) : 1;
        $order = isset($GET['order']) && in_array($GET['order'], $this->report_order) ? $GET['order'] : 'report_name';

        $this->report_model->page_size = 24;

        $filter = "1=1";
        if (isset($GET['q'])) {
            $q = $this->report_model->_e('%'.$GET['q'].'%');
            $filter = "report_name like $q or report_tags like $q";
        }

        $params = [
            "page_title" => "All Crypto Scam reports on CryptoScamAlert.com",
            "reports" => $this->report_model->Index($filter, (in_array($order, ['votes', 'report_created']) ? $order . " desc" : $order), $page),
            "newest" => $this->report_model->Newest(),
            "rows" => $this->report_model->page_size,
            "page" => $page,
            "query" => isset($GET['q']) ? $GET['q'] : "",
            "length" => $this->report_model->Total($filter),
            "order" => $order,
            "scripts" => ["metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "css/site.css"]
        ];

        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("scams", $params);
    }

    public function Newest(){
        $this->report_model->page_size = 20;

        $params = [
            "page_title" => "Newest Crypto Scam reports on CryptoScamAlert.com",
            "reports" => $this->report_model->Newest("1=1", 20),
            "scripts" => ["metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "css/site.css"]
        ];

        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("newest", $params);
    }

    public function Top(){
        global $GET;

        $page = isset($GET['page']) ? intval($GET['page']) : 1;
        $order = 'votes desc';

        $this->report_model->page_size = 24;

        $filter = "votes > 0";

        $params = [
            "page_title" => "Top Crypto scammers reports on CryptoScamAlert.com",
            "reports" => $this->report_model->Index($filter, $order, $page),
            "rows" => $this->report_model->page_size,
            "page" => $page,
            "length" => $this->report_model->Total($filter),
            "scripts" => ["metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "css/site.css"]
        ];

        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("top", $params);
    }

    public function Report($id){
        $report = $this->report_model->Report($id);
        $keywords = $report['report_type_name'] . " " . str_replace(",", " ", $report["report_tags"]);
        $description = substr($report["report_desc"], 0, 500);
        $description = substr($description, 0, strrpos($description, ".")+1);

        $params = [
            "page_title" => "Report of scam on CryptoScamAlert.com",
            "report" => $report,
            "evidences" => $this->report_model->Evidences($id),
            "docs" => $this->report_model->Docs($id),
            "keywords" => $keywords,
            "description" => $description,
            "scripts" => ["markdown-it/markdown-it.js", "metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "css/site.css"]
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("report", $params);
    }

    public function Add(){
        $params = [
            "page_title" => "Report new Crypto scam to CryptoScamAlert.com",
            "report_types" => $this->report_model->Types(),
            "scripts" => ["js/jquery-3.5.1.min.js", "editor.md/editormd.min.js", "editor.md/languages/en.js", "metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "editor.md/css/editormd.min.css", "css/site.css"]
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("add", $params);
    }

    public function Update($id){
        $params = [
            "page_title" => "Report Crypto scam to CryptoScamAlert.com",
            "report_types" => $this->report_model->Types(),
            "report" => $this->report_model->Report($id),
            "evidences" => $this->report_model->Evidences($id),
            "docs" => $this->report_model->Docs($id),
            "scripts" => ["js/jquery-3.5.1.min.js", "editor.md/editormd.min.js", "editor.md/languages/en.js", "metro/js/metro.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "editor.md/css/editormd.min.css", "css/site.css"]
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("update", $params);
    }

    public function AddProcess(){
        global $POST, $config;

        $this->CheckSessionJSON();

        $name = $POST['name'];
        $type = $POST['type'];
        $desc = $POST['desc'];
        $link = $POST['link'];
        $tags = $POST['tags'];
        $user = $_SESSION['current'];
        $evidences = $POST['evidence'];
        $evidences_desc = $POST['evidence_desc'];
        $docs = $POST['doc'];
        $docs_desc = $POST['doc_desc'];

        $report_id = $this->report_model->Create($user, $name, $desc, $type, $tags, $link);

        if ($report_id === false) {
            $this->ReturnJSON(false, "Report not created!", []);
        }

        foreach ($evidences as $key => $evidence) {
            $this->report_model->AddEvidence($report_id, $_SESSION['current'], $evidence, $evidences_desc[$key]);
        }

        foreach ($docs as $key => $doc) {
            $this->report_model->AddDoc($report_id, $_SESSION['current'], $doc, $docs_desc[$key]);
        }

        $this->ReturnJSON(true, "OK", ["report_id" => $report_id]);
    }

    public function UpdateProcess(){
        global $POST, $config;

        $this->CheckSessionJSON();

        $report_owner = intval($_SESSION['current']) === intval($POST['report_owner']) || intval($_SESSION['user']['admin']) === 1;

        if (!$report_owner) {
            $this->ReturnJSON(false, "You can't modify this report", []);
        }

        $report_id = $POST['report_id'];
        $name = $POST['name'];
        $type = $POST['type'];
        $desc = $POST['desc'];
        $link = $POST['link'];
        $tags = $POST['tags'];
        $evidences = $POST['evidence'];
        $evidences_desc = $POST['evidence_desc'];
        $docs = $POST['doc'];
        $docs_desc = $POST['doc_desc'];

        $this->report_model->Edit($report_id, $name, $desc, $type, $tags, $link);

        $this->report_model->DeleteEvidences($report_id);
        $this->report_model->DeleteDocs($report_id);

        foreach ($evidences as $key => $evidence) {
            $this->report_model->AddEvidence($report_id, $_SESSION['current'], $evidence, $evidences_desc[$key]);
        }

        foreach ($docs as $key => $doc) {
            $this->report_model->AddDoc($report_id, $_SESSION['current'], $doc, $docs_desc[$key]);
        }

        $this->ReturnJSON(true, "OK", ["report_id" => $report_id]);
    }

    public function DeleteProcess(){
        global $POST;

        $this->CheckSessionJSON();
        $this->CheckSessionAdmin();

        $id = $POST['id'];

        $result = $this->report_model->DeleteReport($id);
        if ($result === false) {
            $this->ReturnJSON(false, "Report can't be deleted!", []);
        }

        $this->ReturnJSON(true, "OK", []);
    }

    public function VoteProcess(){
        global $POST;

        $this->CheckSessionJSON();

        $id = $POST['id'];

        $result = $this->report_model->VoteReport($id, $_SESSION['current']);

        $this->ReturnJSON(true, "OK", ['votes'=>$result]);
    }
}