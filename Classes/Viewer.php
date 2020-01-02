<?php

namespace Classes;

class Viewer extends Super {
    protected $path;

    public function __construct($path = ''){
        $this->path = $path;
    }

    private function _render($_t, $_p = array()){
        extract($_p);
        ob_start();
        include($_t);
        return ob_get_clean();
    }

    public function Render($_t, $_p = array(), $ext = "phtml"){
        $tpl = $this->path . $_t . '.'.$ext;
        if (!file_exists($tpl)) throw new \Exception("Template not found" . ". File: ".$_t.".".$ext, E_USER_ERROR);
        return $this->_render($tpl, $_p);
    }

    public function SetPath($path = "") {
        $this->path = $path;
        return $this;
    }
}
