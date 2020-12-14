<?php
namespace Controllers;


use Classes\Controller;
use Classes\SimpleEmail;
use Classes\Viewer;
use Models\ReportModel;
use Models\RequestModel;

class RequestController extends GeneralController {
    private $request_model;
    private $mailer;

    public function __construct(){
        $this->request_model = new RequestModel();
        $this->mailer = new SimpleEmail();
    }

    public function RequestVerification(){
        $params = [
            "page_title" => "Verify if your potential partner is legit or scam! Check any company or individual before investing in crypto!",
            "description" => "Verify if your partner is legit or scam!Check company or individual before investing in crypto!",
            "keywords" => "report crypto scam, crypto scam, crypto cheaters, crypto scammers, ico scam, blogger scam",
            "scripts" => ["metro/js/metro.min.js", "js/site.js"],
            "styles" => ["metro/css/metro-all.min.css", "css/site.css"],
            "meta" => [
                "description" => "Verify if your partner is legit or scam! Do not become a victim of crypto fraud! Check any company or individual before investing in crypto!",
                "keywords" => "check crypto fraud, check partner before invest, check crypto scammer,report crypto scam, report crypto fraud,crypto scam, crypto scammers, ico scam, crypto blogger scam, telegram crypto scam, crypto cheaters, crypto ico scam,"
            ]
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("request-verification", $params);
    }

    public function RequestVerificationProcess(){
        global $POST;

        $name = $POST['name'];
        $email = $POST['email'];
        $tr_type = $POST['tr_type'];
        $tr_detail = $POST['tr_detail'];
        $tr_date = $POST['tr_date'];
        $desc = $POST['desc'];

        $id = $this->request_model->CreateVerification("VERIFICATION", $name, $email, $tr_type, $tr_detail, $tr_date, $desc);

        if ($id === false) {
            $this->ReturnJSON(false, "Request not created! Please contact to administrator!", []);
        }

        $uid = $this->request_model->UID($id);

        // send email to cryptocheaters@gmail.com
        $message  = "<h4>Verification Request</h4>";
        $message .= "Hi! You can receive new verification request!<br>";
        $message .= "<br>Name: $name";
        $message .= "<br>Email: $email";
        $message .= "<br>";
        $message .= "<br>Trans type: $tr_type";
        $message .= "<br>Trans address from: $tr_detail";
        $message .= "<br>Trans date: $tr_date";
        $message .= "<br>";
        $message .= "<br>Request details:";
        $message .= "<br>$desc";

        try {
            $this->mailer
                ->SetCharset()
                ->From( $email )
                ->To(array("cryptocheaters@gmail.com", "sergey@pimenov.com.ua"))
                ->Subject("Verification Request")
                ->Message($message)
                ->Send();
        } catch (\Exception $e) {

        }

        $this->ReturnJSON(true, "OK", ["request_uid" => $uid]);
    }
}