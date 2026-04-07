<?php

require_once('Report.php');

class ReportAllVisitDetails extends Report {
	
	protected function getsql($prefix){

		$mc = $this->sqlhelper_campaigns();
		
		$sql = <<<sql
			SELECT DISTINCT Rep_Name, Business, Page, Date_Format(convert_tz(dateHourMinute, %s, %s), "%%Y-%%m-%%d %%H:%%i:%%s" ) as "Date"  
			FROM {$prefix}hdb_analytics_v
			WHERE LOCATE("/newsletter/", Page) = 0 AND LOCATE("elementor", Page) = 0 AND LOCATE("client-capture", Page) = 0 AND Rep_Name <> "MSB" 
			AND Business <> "MSB" AND dateHourMinute > %s AND market
		sql;	
		
		$sql = $sql . $mc["market"] . " Order By Date DESC";
		
		$from =  strval($this->config["fromTZ"]);
		$to = strval($this->config["toTZ"]);
		
		$this->add_arg($from);
		$this->add_arg($to);
		$this->add_arg($this->config["order_date"]);
		$this->add_arg( $mc["campaigns"] ) ;	

		return $this->db->prepare($sql, $this->args);
	}
	
	
	public function getData($market){
		
		$psql = $this->getsql($this->config["blogPrefix"]);
		
		//write_log($psql);
		
		$results = $this->db->get_results($psql);
		
		
		$header = array("Rep Name", "Business","Page", "Date");
		
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);
			
		return json_encode($out);
	
	}
	
	private function endsWith($haystack, $needle) {
		return substr_compare($haystack, $needle, -strlen($needle)) === 0;
	}
	

	private function filterPage($page){
		$rval = $page;
		
		if (! empty($page) ) {
			//stripos($page, "/checkout/order-received/") !== False 
			if ( preg_match("/\/order-received\//i", $page) ) {
				//  preg_match("/\/thank-you-page(.*)\/order-received\//i", $page)
				///checkout/order-received/4977/?key=wc_order_EjXxzZEQM2ZUz
				///thank-you-page/order-received/573/?key=wc_order_9LBaEnL15UIfz	
				$si = stripos($page,"/order-received/" )+16;
				$ei = stripos($page,"/?" );
				$rval = "<div style='text-align:center; background-color:#98FB98; padding:10px;'><b> Order ID <br>". substr($page, $si, $ei-$si) ."</b><br></div>";
			}
			// $this->endsWith(strtolower($page), "checkout/" 
			elseif ( preg_match("/\/checkout(.*)\/$/i", $page)){
				$rval = "<div style='text-align:center; background-color:#FFFD37; padding:5px;'><b>Checkout</b><br/></div>" ;
			}
			// $this->endsWith(strtolower($page), "cart/")
			elseif ( preg_match("/\/cart(.*)\/$/i", $page)){  
				$rval = "<div style='text-align:center; background-color:#FFFD37; padding:5px;'><b>Cart</b><br/></div>" ;
			}
		}
		
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

		foreach($data as $r){
			
			$a = array();
			
			$r->page = $this->filterPage($r->page);
			
			foreach($r as $c){
				array_push($a, array("v"=> is_numeric($c)? intval($c) : $c));
			}
			
			array_push($rows, array("c"=>$a));			
		}	
		return $rows;
	}
	
}//class
