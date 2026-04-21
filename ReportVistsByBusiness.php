<?php
include_once("Report.php");

class ReportVisitsByBusiness extends Report {
	
	protected function getsql($prefix){

		$mc = $this->sqlhelper_campaigns();
		
		$sql = <<<sql
		SELECT Rep_Name, Count(DISTINCT Business) as No_Uniq_Visits FROM {$prefix}hdb_analytics_v
		WHERE dateHourMinute > %s AND market 
		sql;

		$sql = $sql .  $mc["market"] . " AND Rep_Name is not null" . " GROUP BY Rep_Name";	
		
		$this->add_arg($this->config["order_date"] );
		$this->add_arg($mc["campaigns"]);

		return $this->db->prepare($sql, $this->args);
		
	}
	
	public function getData($market){
		
		$psql = $this->getsql($this->config["blogPrefix"]);
		
		//write_log($psql);
		
		$results = $this->db->get_results($psql);
		
		$header = array("Rep Name", "Client Visits");
		
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);
	
		return json_encode($out);		
	}
    
	protected function getCols($header){		
		$cols = array();
		foreach($header as $c){
			$t = array("id"=>"", "label"=>$c, "pattern"=>"", "type"=> $c == "Rep Name" ? "string" : "number" );
			array_push($cols, $t);
		}
				
		return $cols;
	}
	
	protected function getRows($data){		
		$rows = array();
		foreach($data as $r){
			$a = array();
			foreach($r as $c){
				array_push($a, array("v"=> is_numeric($c)? intval($c) : $c));
			}
			array_push($rows, array("c"=>$a));
		}				
		return $rows;
	}
	
}	//class