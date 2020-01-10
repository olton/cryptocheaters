<?php

namespace Models;

use Classes\Model;

class ReportModel extends Model {
    public function Create($name, $desc, $type = -1, $tags = "", $link = ""){
        $data = [
            "report_name" => $this->_e($name),
            "report_desc" => $this->_e($desc),
            "report_type" => $this->_e($type),
            "report_tags" => $this->_e($tags),
            "report_link" => $this->_e($link)
        ];

        $h = $this->Insert("reports", $data, false, true);

        return $this->Rows($h) > 0 ? $this->ID() : false;
    }

    public function AddEvidence($report_id, $user_id, $photo, $desc){
        $data = [
            "evidence_report" => $report_id,
            "evidence_user" => $user_id,
            "evidence_image" => $this->_e($photo),
            "evidence_desc" => $this->_e($desc),
        ];

        $h = $this->Insert("evidences", $data, false, true);

        return $this->Rows($h) > 0 ? $this->ID() : false;
    }

    public function Types(){
        $h = $this->Select("select * from reports_types order by report_type_id");
        $result = [];

        while ($r = $this->FetchArray($h)) {
            $result[$r['report_type_id']] = $r['report_type_name'];
        }

        return $result;
    }
}