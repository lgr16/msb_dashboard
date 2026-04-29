<?php
include_once("Report.php");
class ReportSalesByProducts extends Report{
	
	protected $lookUp;
	
	public function getData($market){
		
		$config = $this->config;
		
		$this->lookUp  = $config["market_products_lookup"];
		
		$sql = $this->getsql($config["blogPrefix"]);
		
		$header = array("Station", "Sales");		
		
		$psql = $this->db->prepare($sql, $config["order_date"], $market );
		
		//write_log($psql);		

		$results = $this->db->get_results($psql);
		
		if (count($results) < 1){
			$temp = array();
			asort($this->lookUp);
			foreach(array_values($this->lookUp) as $U){
				$obj = new stdClass();
				$obj->station = $U;
				$obj->sales = 0;
				$temp[] = $obj;
			}
			$results = $temp;
		}
		
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);
						
		return json_encode($out);
		
	}//getData
	
	protected function getsql($prefix){
		$sql = <<<sql
		SELECT 			
			COALESCE(prm.meta_value, b.Station) AS Station,
			b.Sales	as Sales	
		FROM (		
		select a.order_item_name as Station, a.product_id ,  sum(a.ItemTotal) as Sales from (
		select p.ID,
		MAX(CASE WHEN pm.meta_key = 'hdb_sales_rep' AND oi.order_id = pm.order_id  THEN pm.meta_value END) as SalesRep,
		MAX(CASE WHEN im.meta_key = '_line_total' AND oi.order_id = pm.order_id  THEN im.meta_value END) as ItemTotal,
		MAX(CASE WHEN im.meta_key = '_product_id' AND im.order_item_id = oi.order_item_id  THEN im.meta_value END) as product_id,
		oi.order_item_name,
		p.date_created_gmt
		from  {$prefix}wc_orders as p 
		inner join   {$prefix}woocommerce_order_items as oi on oi.order_id = p.ID
		inner join   {$prefix}woocommerce_order_itemmeta as im on im.order_item_id = oi.order_item_id
		inner join  {$prefix}wc_orders_meta as pm on pm.order_id = p.ID
		WHERE p.type = "shop_order" AND p.status in ("wc-on-hold", "wc-processing", "wc-completed") AND p.date_created_gmt >= %s AND
		pm.meta_key = "hdb_sales_rep" AND 
		oi.order_item_type != 'tax'
		group by oi.order_item_name, p.ID ) a 
		inner join  {$prefix}hdb_sales_reps as sr on sr.repID = a.SalesRep
		WHERE sr.active=1 and sr.market = %s
		group by a.order_item_name, a.product_id
		order by Sales DESC		
		) b
		LEFT JOIN {$prefix}postmeta as prm on prm.post_id = b.product_id AND prm.meta_key = '_sku'
		sql;
		
		return $sql;
	}
	
		
	protected function trimProductName($raw, $lookUp){
		
		$rval = $raw;
		
		if ( array_key_exists($raw, $lookUp) ){
			$rval = $lookUp[$raw];
		}

		return $rval;
	}


	
	protected function getCols($header){		
	
		$cols = array();
		foreach($header as $c){
			$t = array("id"=>"", "label"=>$c, "pattern"=>"", "type"=> $c == "Station" ? "string" : "number" );
			array_push($cols, $t);
		}
				
		return $cols;
	}


	protected function getRows($data){	
		
		$rows = array();
		foreach($data as $r){
			$a = array();
			foreach($r as $c){
				array_push($a, array("v"=> is_numeric($c)? intval($c) : $c, "f"=> is_numeric($c)? '$'.number_format( intval($c),0 ) : $this->trimProductName($c, $this->lookUp) ));
			}
			array_push($rows, array("c"=>$a));
		}				
		return $rows;
	}
	
}//class






























