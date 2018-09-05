<?php
//require __DIR__ . '/vendor/autoload.php';
//include_once "method.php";

class facade {
    function minwidth($d=null) {
        echo 'min-width:800px;';
    }
    function method($rq) {
        //echo $rq;
        switch ($rq) {
        case 'style_min_width':
            $r = array ( "method" => "style_min_width", "return" => "echo/return/json_encode/xml");
            $m = new Method($r);
            $d = $m->getData();
            if ( $d["method"] == $r["method"]) {
                $res = $this->$r["method"]($d);
                echo $res;
            }
            break;
        default:
            
        }
    }

}
?>