<?php

namespace Models;

use Classes\Model;
use Classes\Security;

class UserModel extends Model {
    public function Create($n, $e, $p){
        $data = [
            "nickname" => $this->_e($n),
            "email" => $this->_e($e),
            "password" => $this->_e(Security::EncodePassword($p))
        ];
        $h = $this->Insert("users", $data, false, true);
        return $this->Rows($h) ? $this->ID() : false;
    }

    public function User($id){
        $id = $this->_e($id);
        $sql = "select id, nickname, email, created, updated, admin from users where id = $id";
        $h = $this->Select($sql);
        if ($this->Rows($h) === 0) {
            return false;
        }
        return $this->FetchArray($h);
    }

    public function Check($v, $t){
        $v = $this->_e($v);
        $h = $this->Select("select id from users where $t = $v");
        return $this->Rows($h) > 0;
    }

    public function Login($u, $p){
        $p = $this->_e(Security::EncodePassword($p));
        $u = $this->_e($u);
        $sql = "select id from users where (nickname = $u or email = $u) and password = $p";
        $h = $this->Select($sql);
        if ($this->Rows($h) === 0) {
            return false;
        }
        $result = $this->FetchArray($h);
        return $result['id'];
    }
}