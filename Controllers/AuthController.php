<?php
namespace Controllers;


use Classes\Controller;
use Classes\Url;
use Classes\Viewer;
use Models\UserModel;

class AuthController extends GeneralController {
    public function Login(){
        $params = [
            "page_title" => "Login to CryptoCheaters.com",
            "body_class" => "pt-0 h-vh-100"
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("login", $params);
    }

    public function Signup(){
        $params = [
            "page_title" => "Sign Up to CryptoCheaters.com",
            "body_class" => "pt-0 h-vh-100"
        ];
        $view = new Viewer(TEMPLATE_PATH);
        echo $view->Render("signup", $params);
    }

    public function Logout(){
        unset($_SESSION['current']);
        unset($_SESSION['user']);
        Url::Redirect("/");
    }

    public function SignupProcess(){
        global $POST, $config;

        $user_model = new UserModel();

        $n = $POST["nickname"];
        $u = $POST["email"];
        $p = $POST["password"] ."===". $config['salt'];

        if ($user_model->Check($n, "nickname")) {
            $this->ReturnJSON(false, "Nickname already registered!", []);
        }

        if ($user_model->Check($u, "email")) {
            $this->ReturnJSON(false, "Email already registered!", []);
        }

        $user_id = $user_model->Create($n, $u, $p);
        if ($user_id === false) {
            $this->ReturnJSON(false, "User can't be registered with this credentials!", []);
        }

        $user = $user_model->User($user_id);
        $_SESSION['current'] = $user_id;
        $_SESSION['user'] = $user;
        $this->ReturnJSON(true, "OK", $user);
    }

    public function LoginProcess(){
        global $POST, $config;

        $user_model = new UserModel();

        $u = $POST["email"];
        $p = $POST["password"] ."===". $config['salt'];

        $user_id = $user_model->Login($u, $p);
        if ($user_id === false) {
            $this->ReturnJSON(false, "User not registered with this credentials!", []);
        }

        $user = $user_model->User($user_id);
        $_SESSION['current'] = $user_id;
        $_SESSION['user'] = $user;
        $this->ReturnJSON(true, "OK", $user);
    }
}