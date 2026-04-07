<?php

require_once('Report.php');

class ReportOrders extends Report{
	
	protected function getsql($prefix){
/*
		$sql = <<<sql
		select a.ID as "Order ID", a.Company as "Company Name", a.order_item_name as "Product", a.ItemQuantity as "Product Quantity",
		a.ItemTotal as "Product Value" , sr.name as "Sales Rep" from (
		select p.ID,
		MAX(CASE WHEN pm.meta_key = 'hdb_sales_rep' AND oi.order_id = pm.post_id  THEN pm.meta_value END) as SalesRep,
		MAX(CASE WHEN pm.meta_key = '_billing_company' AND p.ID = pm.post_id  THEN pm.meta_value ELSE "" END) as Company,
		oi.order_item_name,
		MAX(CASE WHEN im.meta_key = '_line_total' AND oi.order_id = pm.post_id  THEN im.meta_value END) as ItemTotal,
		MAX(CASE WHEN im.meta_key = '_qty'  AND p.ID = pm.post_id  THEN im.meta_value END) as ItemQuantity	
		from {$prefix}posts as p 
		inner join {$prefix}woocommerce_order_items as oi on oi.order_id = p.ID
		inner join {$prefix}woocommerce_order_itemmeta as im on im.order_item_id = oi.order_item_id
		inner join {$prefix}postmeta as pm on pm.post_id = p.ID
		WHERE p.post_type = "shop_order" AND p.post_status in ("wc-on-hold", "wc-processing", "wc-completed") AND 
		p.post_date >= %s AND im.meta_key in ("_line_total", "_qty") AND
		pm.meta_key in ("hdb_sales_rep", "_billing_company") AND 
		oi.order_item_type != 'tax'
		group by oi.order_item_name, p.ID order by p.ID DESC ) a 
		inner join {$prefix}hdb_sales_reps as sr on sr.repID = a.SalesRep
		WHERE sr.active=1 and sr.market = %s;	
		sql;
*/
		$sql = <<<sql
		SELECT ords.id as "Order ID",
		oa.company as "Company Name",						
		oi.order_item_name as "Product",            
		MAX(CASE WHEN im.meta_key = '_qty'  AND im.order_item_id = oi.order_item_id  THEN im.meta_value END) as "Product Quantity",
		MAX(CASE WHEN im.meta_key = '_line_total' AND oi.order_id = ords.id  THEN im.meta_value END) as "Product Value",
		sr.name as "Sales Rep"
		FROM {$prefix}wc_orders ords
		INNER JOIN {$prefix}wc_order_addresses as oa on oa.order_id = ords.id
		INNER JOIN {$prefix}wc_orders_meta as om on om.order_id = ords.id
		inner join {$prefix}woocommerce_order_items as oi on oi.order_id = ords.id
		inner join {$prefix}woocommerce_order_itemmeta as im on im.order_item_id = oi.order_item_id
		inner join {$prefix}hdb_sales_reps as sr on sr.repID = om.meta_value
		WHERE oa.address_type = "billing" 	AND
		om.meta_key = "hdb_sales_rep" 		AND
		ords.status in ("wc-on-hold", "wc-processing", "wc-completed") AND
		ords.date_created_gmt > %s AND 
		oi.order_item_type != 'tax' AND 
		sr.market = %s
		group by  oi.order_item_name, ords.id
		order by ords.id DESC
		sql;

		return $sql;
		
	}
	
	
	
	public function getData($market){
		
		$sql = $this->getsql($this->config["blogPrefix"]);
		
		
		$psql = $this->db->prepare($sql, $this->config["order_date"], $market );
		
		//write_log($psql);		

		$results = $this->db->get_results($psql);

		$header = array('Order ID', 'Company Name', 'Product', 'Product Quantity', 'Product Value', 'Sales Rep');
		
			
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);
		
		echo json_encode($out);
		
	}



	protected function getCols($header){		
		$numbers = array('Order ID', 'Order Total', 'Product Quantity', 'Product Value');
		$cols = array();
		foreach($header as $c){
			$t = array("id"=>"", "label"=>$c, "pattern"=>"", "type"=> in_array($c, $numbers) ? "number" : "string" );
			array_push($cols, $t);
		}
				
		return $cols;
	}
	
	
	
	protected function getRows($data){		
		$rows = array();
		foreach($data as $r){
			$a = array();
			foreach($r as $c){
				//array_push($a, array("v"=> is_numeric($c)? intval($c) : $c));
				array_push($a, array("v"=> $c));
			}
			array_push($rows, array("c"=>$a));
		}				
		return $rows;
	}
	
	
}



	

