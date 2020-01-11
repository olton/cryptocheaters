<?php

// В роутах первым параметром должен идти метод запроса
// Вохможные варианты: GET, POST, ANY
// ANY используется для страниц на которые можно зайти обоими методами, например поиск

return array(
    array('GET', "/", array("controller" => "DefaultController", "action" => "Index")),
    array('GET', "/terms", array("controller" => "DefaultController", "action" => "Terms")),

    // Report ---------------------------------------------------------------------------------------------------------
    array('GET', "/scams", array("controller" => "ReportController", "action" => "Index")),
    array('GET', "/report/:id", array("controller" => "ReportController", "action" => "Report"), ["id"=>'[\d]+']),
    array('GET', "/add", array("controller" => "ReportController", "action" => "Add")),
    array('POST', "/add/process", array("controller" => "ReportController", "action" => "AddProcess")),
    array('POST', "/delete/process", array("controller" => "ReportController", "action" => "DeleteProcess")),

    // Auth -----------------------------------------------------------------------------------------------------------
    array('GET', "/login", array("controller" => "AuthController", "action" => "Login")),
    array('GET', "/logout", array("controller" => "AuthController", "action" => "Logout")),
    array('GET', "/signup", array("controller" => "AuthController", "action" => "Signup")),
    array('POST', "/login/process", array("controller" => "AuthController", "action" => "LoginProcess")),
    array('POST', "/signup/process", array("controller" => "AuthController", "action" => "SignupProcess")),
);
