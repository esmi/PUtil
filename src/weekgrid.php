<?php
//include_once "db_connection.php";

class weekgrid {
    protected $weekstmt;
    protected $db;

    function __construct( $db ) {
        $this->db = $db;

        $this->weekstmt =<<<STMT
    ;WITH nums AS
       (SELECT 1 AS value
        UNION ALL
        SELECT value + 1 AS value
        FROM nums
        WHERE nums.value <= 6)

    SELECT 
        case
            when a.value= 1 then '周一'
            when a.value= 2 then '周二'
            when a.value= 3 then '周三'
            when a.value= 4 then '周四'
            when a.value= 5 then '周五'
            when a.value= 6 then '周六'
            when a.value= 7 then '周日'
            else 'err'  
        end week_name,
        0 ck,
        ##
        *
    FROM 
        (
            select CONVERT(varchar(10), value) vstr,*
            from
            nums
        ) as a  
        
STMT;
    }
    function getweekgrid($join, $fds ) {
        $temp = $this->weekstmt . $join;
        $stmt = str_replace( '##', $fds, $temp);
        //echo $stmt;
        $rs = $this->db->query($stmt);
        $res = $this->db->getRows($rs);
        return $res;
    }
    function nullweek() {
        $stmt = str_replace('##', '', $this->weekstmt);
        $rs = $this->db->query($stmt);
        $res = $this->db->getRows($rs);
        return $res;
    }
}
?>