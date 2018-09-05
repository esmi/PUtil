<?php
namespace Esmi\utils;

trait reportUtils
{
   	/**
	* Generating CSV formatted string from an array.
	*/
	function array_to_csv($array, $header_map = [], $excl_header = [], $eqformat=[], $header_row = true, $excl_integer_key = true, $col_sep = ",", $row_sep = "\n", $qut = '"')
	{
		if (!is_array($array) or !is_array($array[0])) return false;

		$map_header = sizeof($header_map) > 0;
		//var_dump($header_map);
		//echo "\r\n" . $map_header . gettype($map_header) ."----\r\n";
		//var_dump($map_header);

		$output = "";
		if ($header_row)
		{
			foreach ($array[0] as $key => $val)
			{
				if ( !in_array($key, $excl_header )) {
					if (!( gettype($key) == "integer" && $excl_integer_key ) ) {
						if ( $map_header) {
							if ( isset($header_map[$key]))  {

								$key = $header_map["$key"];
							}
						}
						$key = str_replace($qut, "$qut$qut", $key);
						$output .= "$col_sep$qut$key$qut";
					}
				}

				//Escaping quotes.
			}
			$output = substr($output, 1)."\n";
		}
		//Data rows.
		foreach ($array as $key => $val)
		{

			$tmp = '';
			foreach ($val as $cell_key => $cell_val)
			{
				if ( !in_array($cell_key, $excl_header )) {
				//Escaping quotes.
					if (!( gettype($cell_key) == "integer" && $excl_integer_key ) ) {
						$cell_val = str_replace($qut, "$qut$qut", $cell_val);
						$eq = (in_array($cell_key, $eqformat)) ? "=" : "";
						//var_dump($reformat);
						//var_dump($cell_key);
						$tmp .= "$col_sep$eq$qut$cell_val$qut";
					}
				}
			}
			$output .= substr($tmp, 1).$row_sep;
		}

		return $output;
	}	
 	function show($stmt, $flag=false) {
        $this->g->show($stmt);
	}
	/*
	function getRows($stmt, $src=[], 
		$er1="此查詢條件,查無查詢資料!", 
		$er2='無法取得查詢資料(SQL語法有問題)!',
		$ok="OK") {

        $rs = $this->db->query($stmt);

        if ($rs) {
            $rows = $this->db->getRows($rs);
            //var_dump($rows);
            if ( $rows ) {
                $ret =
                    [
                        'status' => $ok, 
                        'rows' => $rows
                    ];
            }
            else {
                $ret = ['status' => 'error', 'message' => $er1, 'stmt' => $stmt];
            }
        }
        else {
            $ret = ['status' => 'error', 'message' => $er2, 'stmt' => $stmt];
        }

        return array_merge($src, $ret);
	}
	*/
    function getcsv($d, $header_map=[], $excl_header=[] , $reformat=[]) {
        $csv = "";
		if ( isset($d['status']) && $d['status'] == "OK" ) {

			$rows = $d['rows'];
			
			$csv = $this->array_to_csv($rows, $header_map, $excl_header, $reformat);
			
			//$this->show($csv);

			if ($csv) {
				$d['csv'] = $csv;
			}
			else {
				$d['status'] = 'error';
				$d['message'] = '查詢條件無法取得CSV資料';
			}
		}
		else {
		    $d['status'] = 'error';
		    $d['message'] = isset($d['status']) ? "來源資料'status'屬性不為'OK'!": "來源資料並未設定'status'屬性!";
		}
		  
		return $d;
    }
    function data_map($a) {
    	//$r=[];
		foreach ($a as $key => $val)
		{
			$r[$key] = $val['title'];
		}
		return $r;

    }
    function map2grid($header_map) {
    	// $header_map example:
    	// [
		//	'fdCode1'	=> 	[ 'title' => "fd Name 1", 	'options' => ""],
		//	'fdCode2'	=>  [ 'title' => "fd Name 2",'options' => ",align:'right'"],
		//	'fdCode3'	=>	[ 'title' => "fd Name 3, 	'options' => ",align:'right'"]
		//	];
    	

		$output = "";
		foreach ($header_map as $key => $val)
		{
			$o = <<<LINE
			<th data-options="field:'{$key}'{$val['options']}">{$val['title']}</th>
LINE;
			$output .= ($o . "\r\n");
		}
		return  $output;
    }
 
}