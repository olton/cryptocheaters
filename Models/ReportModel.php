<?php

namespace Models;

use Classes\Model;

class ReportModel extends Model {
    public function Create($name, $short, $desc, $type = -1, $tags = "", $link = ""){
        $data = [
            "report_name" => $this->_e($name),
            "report_short" => $this->_e($short),
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

    public function Report($id){
        $id = $this->_e($id);
        $sql = "select * from reports t1 left join reports_types t2 on (t1.report_type = t2.report_type_id) where report_id = $id";
        $h = $this->Select($sql);
        if ($this->Rows($h) === 0) {
            return false;
        }
        return $this->FetchArray($h);
    }

    public function Index($filter = "1=1", $page = false){
        $limit = $page === false ? "" : " ".$this->Limit($page);
        $sql = "select * from reports t1 left join reports_types t2 on (t1.report_type = t2.report_type_id) where $filter order by report_likes desc $limit ";
        $h = $this->Select($sql);
        if ($this->Rows($h) === 0) {
            return false;
        }
        $result = [];
        while($r = $this->FetchArray($h)) {
            $result[$r['report_id']] = $r;
        }
        return $result;
    }

    public function Evidences($id){
        $id = $this->_e($id);
        $sql = "select * from evidences where evidence_report = $id";
        $h = $this->Select($sql);
        if ($this->Rows($h) === 0) {
            return false;
        }
        $result = [];
        while($r = $this->FetchArray($h)) {
            $result[$r['evidence_id']] = $r;
        }
        return $result;
    }

}