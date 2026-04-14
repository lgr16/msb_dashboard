<?php
include_once("Report.php");
class ReportTotalSales extends Report {

	
	public function getData($market){
		
		$config = $this->config;
		
		$sql = $this->getsql($config["blogPrefix"]);
		
		
		$psql = $this->db->prepare($sql, $config["order_date"], $market );
		
		//write_log($psql);		

		$results = $this->db->get_results($psql);
		
		$f = "Count";
		$f2 ="Total Sales";
		
		$header = array("Sale", "Total Orders","Total Sales");
		
			
		$arr = $results[0];
		if ( empty($arr) ){
			$values = array(array("Sale", 0 , 0));	
		}
		else{
			$values = array(array("Sale", $arr->$f , $arr->$f2));
		}
		
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($values);
		
		//write_log($out);
		
		return json_encode($out);

	}

	//tweaked for new orders table
	protected function getsql($prefix)	{
		$sql = <<<sql
		select count(DISTINCT a.ID) as Count, sum(a.ItemTotal) as "Total Sales" from (
		select p.ID,
		MAX(CASE WHEN pm.meta_key = 'hdb_sales_rep' AND oi.order_id = pm.order_id  THEN pm.meta_value COLLATE utf8mb4_unicode_520_ci END) as SalesRep,
		MAX(CASE WHEN im.meta_key = '_line_total' AND oi.order_id = pm.order_id  THEN im.meta_value COLLATE utf8mb4_unicode_520_ci END) as ItemTotal
		from {$prefix}wc_orders as p 
		inner join  {$prefix}woocommerce_order_items as oi on oi.order_id = p.id
		inner join  {$prefix}woocommerce_order_itemmeta as im on im.order_item_id = oi.order_item_id
		inner join {$prefix}wc_orders_meta as pm on pm.order_id = p.id
		WHERE p.type = "shop_order" AND p.status in ("wc-on-hold", "wc-processing", "wc-completed") AND
		p.date_created_gmt >= %s AND
		im.meta_key = "_line_total" AND
		pm.meta_key = "hdb_sales_rep" AND 
		oi.order_item_type != 'tax'
		group by oi.order_item_name, p.ID ) a 
		inner join {$prefix}hdb_sales_reps as sr on sr.repID = a.SalesRep
		WHERE sr.active=1 and sr.market = %s;
		sql;
		
		return $sql;
	}
	
	protected function getsql_old($prefix)	{
		$sql = <<<sql
		select count(DISTINCT a.ID) as Count, sum(a.ItemTotal) as "Total Sales" from (
		select ID,
		MAX(CASE WHEN pm.meta_key = 'hdb_sales_rep' AND oi.order_id = pm.post_id  THEN pm.meta_value END) as SalesRep,
		MAX(CASE WHEN im.meta_key = '_line_total' AND oi.order_id = pm.post_id  THEN im.meta_value END) as ItemTotal
		from {$prefix}posts as p 
		inner join  {$prefix}woocommerce_order_items as oi on oi.order_id = p.ID
		inner join  {$prefix}woocommerce_order_itemmeta as im on im.order_item_id = oi.order_item_id
		inner join {$prefix}postmeta as pm on pm.post_id = p.ID
		WHERE p.post_type = "shop_order" AND p.post_status in ("wc-on-hold", "wc-processing", "wc-completed") AND
		p.post_date >= %s AND
		im.meta_key = "_line_total" AND
		pm.meta_key = "hdb_sales_rep" AND 
		oi.order_item_type != 'tax'
		group by oi.order_item_name, p.ID ) a 
		inner join {$prefix}hdb_sales_reps as sr on sr.repID = a.SalesRep
		WHERE sr.active=1 and sr.market = %s;
		sql;
		
		return $sql;
	}
	
	
	protected function getCols($header){		
		$cols = array();
		foreach($header as $c){
			$t = array("id"=>"", "label"=>$c, "pattern"=>"", "type"=> $c == "Sale" ? "string" : "number" );
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