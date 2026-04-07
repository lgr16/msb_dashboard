<?php
require_once('Report.php');

class ReportAbandonedCarts extends Report {
	
	protected function getsql($prefix){

		$mc = $this->sqlhelper_campaigns();
		
		$sql = <<<sql
			select d1.rep_name, d1.Business from (SELECT
			Rep_name,
			Business,
			COUNT( case when LOCATE("order-received", Page) > 0 then Page else NULL end ) as OrderID, 
			COUNT( case when LOCATE("checkout", Page) > 0 then Page else NULL end ) as Checkout,
			COUNT( case when LOCATE("cart", Page) > 0 then Page else NULL end ) as Cart
			FROM  {$prefix}hdb_analytics_v WHERE  market 
		sql;					
			
		$sql2 = <<<sql2
			group by repID, bizID) as d1
			where d1.OrderID = 0 AND (d1.Checkout > 0 or d1.Cart > 0)
			ORDER BY Rep_name, Business;
		sql2;
		
		$sql = $sql . $mc["market"]  . $sql2;
		
		return $this->db->prepare($sql, $mc["campaigns"] );
	
	}
	
	public function getData($market){
		
		$psql = $this->getsql($this->config['blogPrefix']);
		
		//write_log($psql);
		
		$results = $this->db->get_results($psql);
		
		$header = array("Rep Name", "Business");
		
				
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);
			
		return json_encode($out);

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
			foreach($r as $c){
				array_push($a, array("v"=> is_numeric($c)? intval($c) : $c));
			}
			array_push($rows, array("c"=>$a));			
		}				
		return $rows;			
	}
	
}
	


































