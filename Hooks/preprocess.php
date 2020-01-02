<?php

use Models\UserModel;

if (isset($_SESSION['current']) && $_SESSION['current'] > 0) {
    $user_model = new UserModel();
    $_SESSION['user'] = $user_model->User($_SESSION['current']);
}
