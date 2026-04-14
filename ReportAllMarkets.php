<?php
include_once("Report.php");
class ReportAllMarkets extends Report {

	
	public function getData($market){
		
		$config = $this->config;
		
		$allMarkets = isset($config["all_markets_report"]) ? $config["all_markets_report"] : [];
		
		$sql = $this->getBigQuery($allMarkets, $config["order_date"]);
		
		
		//$psql = $this->db->prepare($sql, $config["order_date"], $market );
		
		//write_log($psql);		

		$results = $this->db->get_results($sql);
		
		$f = "Count";
		$f2 ="Total Sales";
		
		$header = array("Market", "Total Orders","Total Sales");

		
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);
		
		//write_log($out);
		
		return json_encode($out);

	}
	
	protected function getsql($prefix){
		return true;
	}


	protected function getBigQuery($all_markets, $date){
		
		$bigQuery = "";

		foreach($all_markets as $marketName => $blogID){
			
			$bigQuery .= $this->gsql($blogID, $marketName, $date);
			$bigQuery .= "\n";
			$bigQuery .= "UNION\n";
			
		}
		
		$bigQuery =  preg_replace('/UNION$/', '', trim($bigQuery) );

		return $bigQuery;
	}

	
	protected function gsql($blogID, $market, $date) {
	$sql = <<<sql
	select "{$market}" as Market, count(DISTINCT a.id) as Count, sum(a.ItemTotal) as "Total Sales" from (
	select p.id,    
	MAX(CASE WHEN im.meta_key = '_line_total' AND oi.order_id = pm.order_id  THEN im.meta_value END) as ItemTotal
	from wpmsb_{$blogID}_wc_orders as p 
	inner join  wpmsb_{$blogID}_woocommerce_order_items as oi on oi.order_id = p.id
	inner join  wpmsb_{$blogID}_woocommerce_order_itemmeta as im on im.order_item_id = oi.order_item_id
	inner join wpmsb_{$blogID}_wc_orders_meta as pm on pm.order_id = p.id
	WHERE p.type = "shop_order" AND p.status in ("wc-on-hold", "wc-processing", "wc-completed") AND
	p.date_created_gmt >= "{$date}" AND
	im.meta_key = "_line_total" AND    
	oi.order_item_type != 'tax'
	group by oi.order_item_name, p.ID 
	) a 		
	sql;
	
	return $sql;
}

	
	protected function getCols($header){		
		$cols = array();
		foreach($header as $c){
			$t = array("id"=>"", "label"=>$c, "pattern"=>"", "type"=> $c == "Market" ? "string" : "number" );
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

}  //class