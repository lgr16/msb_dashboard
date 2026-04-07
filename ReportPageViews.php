<?php

require_once('Report.php');

class ReportPageViews extends Report{
	
	protected function getsql($prefix){
		
		$mc = $this->sqlhelper_campaigns();		
				
		$sql = <<<sql
		SELECT count(*) as count
		FROM {$prefix}hdb_std_report
		WHERE dateHourMinute > %s AND market
		sql;
		
		$sql  = $sql . $mc["market"];
		
		$this->add_arg($this->config['order_date']);
		$this->add_arg($mc["campaigns"] );
		
		return $psql = $this->db->prepare($sql, $this->args);
	}
	
	public function getData($market){
		
		$psql = $this->getsql($this->config["blogPrefix"]);
		
		//write_log($psql);
		
		$results = $this->db->get_var($psql);
		
		echo json_encode($results);
		
	}
	
	protected function getCols($header){
		return true;
	}
	
	protected function getRows($data){
		return true;
	}
}






























