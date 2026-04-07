<?php

include_once("Report.php");

class ReportSalesByPerson extends Report{
	
	protected function getsql($prefix){
		/* old post / post meta based query
		$sql = <<<sql
		select sr.name as "Sales Rep", sum(a.ItemTotal) as "Total Sales" from (
		SELECT p.ID, 
		MAX(CASE WHEN pm.meta_key = 'hdb_sales_rep' AND p.ID = pm.post_id  THEN pm.meta_value END) as SalesRep,
		MAX(CASE WHEN im.meta_key = '_line_total' AND p.ID = pm.post_id  THEN im.meta_value END) as ItemTotal
		FROM {$prefix}woocommerce_order_items as oi
		inner join {$prefix}posts as p on p.ID = oi.order_id
		inner join {$prefix}woocommerce_order_itemmeta as im on im.order_item_id = oi.order_item_id
		inner join {$prefix}postmeta as pm on pm.post_id = p.ID
		WHERE p.post_type = "shop_order" AND p.post_status in ("wc-on-hold", "wc-processing", "wc-completed") AND
			oi.order_item_type != 'tax' AND p.post_date >= %s
		group by oi.order_item_name, p.id
		order by p.ID) as a
		inner join {$prefix}hdb_sales_reps as sr on sr.repID = a.SalesRep
		WHERE sr.active=1 and sr.market = %s
		group by sr.name order by "Total Sales";
		sql;	
		*/
		
		// based on new high performance orders tables
		$sql = <<<sql
		select sr.name, round(sum(inq.totSales),0) as "Total Sales" from (
		SELECT om.meta_value as "repID", ords.total_amount - ords.tax_amount as "totSales"
		FROM {$prefix}wc_orders ords
		INNER JOIN {$prefix}wc_orders_meta as om on om.order_id = ords.id
		WHERE om.meta_key = "hdb_sales_rep" 		AND
		ords.status in ("wc-on-hold", "wc-processing", "wc-completed") AND
		ords.date_created_gmt > %s ) as inq
		inner join {$prefix}hdb_sales_reps as sr on sr.repID = inq.repID
		WHERE sr.active=1 and sr.market = %s
		group by sr.name order by "Total Sales";
		sql;
		
		return $sql;
		
	}
	
	
	public function getData($market){
		$sql = $this->getsql($this->config["blogPrefix"]);
		
		$psql = $this->db->prepare($sql, $this->config["order_date"], $market );
		
		//write_log($psql);
		
		$results = $this->db->get_results($psql);
		
		$header = array("Sales Rep", "Total Sales");
		
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);
	
		return json_encode($out);		
	}
	
	
	protected function getCols($header){		
		$cols = array();
		foreach($header as $c){
			$t = array("id"=>"", "label"=>$c, "pattern"=>"", "type"=> $c == "Sales Rep" ? "string" : "number" );
			array_push($cols, $t);
		}
				
		return $cols;
	}
	
	
	
	protected function getRows($data){		
		$rows = array();
		foreach($data as $r){
			$a = array();
			foreach($r as $c){
				array_push($a, array("v"=> is_numeric($c)? intval($c) : $c, "f"=> is_numeric($c)? '$'.number_format( intval($c),0 ) : $c ));				
			}
			array_push($rows, array("c"=>$a));
		}				
		return $rows;
	}
}

	
	
	
	
	































