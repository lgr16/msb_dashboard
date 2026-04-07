<?php

require_once('Report.php');

//this is for an ag-grid not a google chart so the requried data structure output is different

class ReportOrdersEdit extends Report{
	
	protected function getsql($prefix){
//TODO: decide to join on sale rep table and filter by market
		$sql = <<<sql
		SELECT ords.id as "Order ID", CONCAT(oa.first_name, " ", oa.last_name) as "Contact Name",
			oa.company as "Company Name",
			ords.total_amount - ords.tax_amount as "Order Value", 
			om.meta_value as "Sales Rep"
		FROM {$prefix}wc_orders ords
		INNER JOIN {$prefix}wc_order_addresses as oa on oa.order_id = ords.id
		INNER JOIN {$prefix}wc_orders_meta as om on om.order_id = ords.id
		WHERE oa.address_type = "billing" 	AND
		om.meta_key = "hdb_sales_rep" 		AND
		ords.status in ("wc-on-hold", "wc-processing", "wc-completed") AND
		ords.date_created_gmt > %s order by ords.id DESC
		sql;
		

		return $sql;
		
	}
	
	protected function getSalesRepsSql($prefix){
		$sql = <<<sql
		select repID, name from {$prefix}hdb_sales_reps where market = %s and active = 1 and edit_orders = 1 order by sort_order;
		sql;
		
		return $sql;
	}
	
	public function getData($market){
		
		$sql = $this->getsql($this->config["blogPrefix"]);
		
		
		$psql = $this->db->prepare($sql, $this->config["order_date"]); //, $market );
		
		//write_log($psql);		

		$results = $this->db->get_results($psql);

		//$header = array('Order ID', 'Company Name', 'Product', 'Product Quantity', 'Product Value', 'Sales Rep');
		
			
		$out["rowData"] = $results;
		$out["salesReps"] = $this->getReps($market);
		//$out["rows"] = $this->getRows($results);
		
		echo json_encode($out);
		
	}


	protected function getReps($market){
		
		$sql = $this->getSalesRepsSql($this->config["blogPrefix"]);
				
		$psql = $this->db->prepare($sql, $market);
		
		//write_log($psql);		

		$results = $this->db->get_results($psql);
		
		$rval = [];
		foreach($results as $rep){
			$rval[$rep->repID] = $rep->name;
		}
		
		return $rval;
		
	}


	protected function getCols($header){		
		
		return true;
	}
	
	
	
	protected function getRows($data){		
			
		return true;
	}
	
	
}



	

