<?php

include_once("Report.php");

class ReportBusinessProgress extends Report{
	
	protected $progressDescriptor = [1=>"V", 2=>"I", 3=>"P", 4=>"S", 5=>"C", 6=>"O"];

	protected function getsql($prefix){
		
		$mc = $this->sqlhelper_campaigns();
		
		//write_log($mc);
		//locate("/checkout%/order-received/", page) > 0  THEN 5
		$sql = <<<sql
		SELECT rep_name, business, Max(
			CASE
				WHEN page like "%/order-received/%"   THEN 6
				WHEN page like "%checkout%/"          THEN 5
				WHEN page like "%cart%/"              THEN 4
				WHEN locate("/product/", page) > 0    THEN 3
				WHEN page like "/sale/_%"             THEN 2
				ELSE 1
			END) as progress,
			MAX(Date_Format(convert_tz(dateHourMinute, %s, %s), "%%Y-%%m-%%d %%H:%%i:%%s" ) ) as "Date"
		FROM {$prefix}hdb_analytics_v 
		WHERE LOCATE("/newsletter/", Page) = 0 AND LOCATE("elementor", Page) = 0 AND LOCATE("client-capture", Page) = 0 AND Rep_Name <> "MSB" 
		AND Business <> "MSB" AND dateHourMinute > %s AND market
		sql;	
		
		$sql = $sql .  $mc["market"] . " GROUP BY repID, bizID Order By Date DESC";

		$from =  strval($this->config["fromTZ"]);
		$to = strval($this->config["toTZ"]);
		
		$this->add_arg($from);
		$this->add_arg($to);
		$this->add_arg($this->config["order_date"]);
		$this->add_arg( $mc["campaigns"] ) ;		
		
		//write_log($sql);
		
		// from, to, orderDate, campaigns
		return $this->db->prepare($sql, $this->args );
	}
	
	
	
	public function getData($market){
		
		$psql = $this->getsql($this->config["blogPrefix"]);
		
		//write_log($psql);
		
		$results = $this->db->get_results($psql);
		
		$header = array("Rep Name", "Business","Progress", "Date", "ProgressBar");
		
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);
	
		return json_encode($out);		
	}
	

	protected function getProgressBar2($progressNumber){

		$rval = "<div class='hdb_circles'>";
		
		for($i=1;$i<=6; $i++ ){
			$active = ($i <= $progressNumber) ? "hdb_active" : "";
			$rval .= "<div class='hdb_circle-with-text ". $active ."'>". $this->progressDescriptor[$i] ."</div>";
		}
		$rval .= "</div>";

		return $rval;
	}



	protected function getCols($header){		
		$cols = array();
		foreach($header as $c){
			$t = array("id"=>"", "label"=>$c, "pattern"=>"", "type"=> $c != "x" ? "string" : "date" );
			array_push($cols, $t);
		}
				
		return $cols;
	}
	
	protected function getRows($data){

		$rows = array();

		foreach($data as $r ){
			$a = array();
			$r->progressBar = $this->getProgressBar2($r->progress);
			
			foreach($r as $c){
				array_push($a, array("v"=> is_numeric($c)? intval($c) : $c ));
			}
			
			array_push($rows, array("c"=>$a));					
		}				
		
		return $rows;
	}

	
} //class