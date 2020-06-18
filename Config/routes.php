<?php

// В роутах первым параметром должен идти метод запроса
// Вохможные варианты: GET, POST, ANY
// ANY используется для страниц на которые можно зайти обоими методами, например поиск

return array(
    array('GET', "/", array("controller" => "DefaultController", "action" => "Index")),
    array('GET', "/terms", array("controller" => "DefaultController", "action" => "Terms")),

    // Requests
    array('GET', "/verification", array("controller" => "RequestController", "action" => "RequestVerification")),
    array('POST', "/verification/process", array("controller" => "RequestController", "action" => "RequestVerificationProcess")),

    // Report ---------------------------------------------------------------------------------------------------------
    array('GET', "/scams", array("controller" => "ReportController", "action" => "Index")),
    array('GET', "/newest", array("controller" => "ReportController", "action" => "Newest")),
    array('GET', "/top", array("controller" => "ReportController", "action" => "Top")),
    array('GET', "/report/:id", array("controller" => "ReportController", "action" => "Report"), ["id"=>'[\d]+']),
    array('GET', "/add", array("controller" => "ReportController", "action" => "Add")),
    array('GET', "/update/:id", array("controller" => "ReportController", "action" => "Update"), ["id"=>'[\d]+']),
    array('POST', "/add/process", array("controller" => "ReportController", "action" => "AddProcess")),
    array('POST', "/update/process", array("controller" => "ReportController", "action" => "UpdateProcess")),
    array('POST', "/delete/process", array("controller" => "ReportController", "action" => "DeleteProcess")),
    array('POST', "/vote", array("controller" => "ReportController", "action" => "VoteProcess")),

    // Auth -----------------------------------------------------------------------------------------------------------
    array('GET', "/login", array("controller" => "AuthController", "action" => "Login")),
    array('GET', "/logout", array("controller" => "AuthController", "action" => "Logout")),
    array('GET', "/signup", array("controller" => "AuthController", "action" => "Signup")),
    array('POST', "/login/process", array("controller" => "AuthController", "action" => "LoginProcess")),
    array('POST', "/signup/process", array("controller" => "AuthController", "action" => "SignupProcess")),
);
