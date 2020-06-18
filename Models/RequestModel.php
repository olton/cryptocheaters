<?php

namespace Models;

use Classes\Model;

class RequestModel extends Model {
    public function CreateVerification($type, $name, $email, $tr_type, $tr_detail, $tr_date, $desc){
        $uid = $type[0] . "-" . date("Ymd") . "-" . date("z") . "-" . str_replace(" ", "-", microtime(false));
        $data = [
            "request_uid" => $this->_e($uid),
            "request_name" => $this->_e($name),
            "request_email" => $this->_e($email),
            "request_type" => $this->_e($type),
            "request_tr_type" => $this->_e($tr_type),
            "request_tr_detail" => $this->_e($tr_detail),
            "request_tr_date" => $this->_e($tr_date),
            "request_desc" => $this->_e($desc)
        ];
        $h = $this->Insert("requests", $data, false, true);
        return $this->Rows($h) ? $this->ID() : false;
    }

    public function UID($id){
        $h = $this->Select("select request_uid from requests where request_id = " . $this->_e($id) );
        return $this->Rows($h) > 0 ? $this->FetchArray($h)["request_uid"] : false;
    }

    public function CloseVerification($id){
        $data = [
            "request_done" => 1,
            "request_complete" => date("Y-m-d")
        ];
        $h = $this->Update("requests", $data, "request_id = " . $this->_e($id), true);
        return $this->Rows($h);
    }
}