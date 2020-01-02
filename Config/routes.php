<?php

// В роутах первым параметром должен идти метод запроса
// Вохможные варианты: GET, POST, ANY
// ANY используется для страниц на которые можно зайти обоими методами, например поиск

return array(
    array('GET', "/", array("controller" => "DefaultController", "action" => "Index")),
    array('GET', "/login", array("controller" => "AuthController", "action" => "Login")),
    array('GET', "/logout", array("controller" => "AuthController", "action" => "Logout")),
    array('GET', "/signup", array("controller" => "AuthController", "action" => "Signup")),
);
