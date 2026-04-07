<?php

include_once("Report.php");

class ReportSalesByProductTaxonomy extends Report{
	
	protected function getsql($prefix){

		//TODO: investigate if we need to filter by salesRep market, not doing it now
				
		#based on single tag or cat, not hierarchical
		$sql_single_cat_or_tag = <<<sql_single_cat_or_tag
		SELECT t.name as Grouping, SUM(oim_price.meta_value) as Sales
		FROM {$prefix}woocommerce_order_items AS oi
		INNER JOIN {$prefix}woocommerce_order_itemmeta AS oim
			ON oi.order_item_id = oim.order_item_id
		INNER JOIN {$prefix}woocommerce_order_itemmeta AS oim_price
			ON oi.order_item_id = oim_price.order_item_id
		INNER JOIN {$prefix}wc_orders AS o
			ON o.id = oi.order_id
		INNER JOIN {$prefix}term_relationships AS tr
			ON tr.object_id = oim.meta_value
		INNER JOIN {$prefix}term_taxonomy AS tt
			ON tt.term_taxonomy_id = tr.term_taxonomy_id
		INNER JOIN {$prefix}terms as t
			ON t.term_id = tt.term_taxonomy_id
		WHERE oi.order_item_type = 'line_item'
		AND oim.meta_key = '_product_id'
		AND (tt.taxonomy = 'product_cat' or tt.taxonomy = 'product_tag')
		AND oim_price.meta_key = '_line_subtotal'
		AND o.date_created_gmt  > %s 
		AND tt.term_id 
		sql_single_cat_or_tag;	
		
		$sql_single_cat_or_tag = $sql_single_cat_or_tag . $this->sqlhelper_in($this->config["SalesByTaxIDs"]).' GROUP BY t.name ORDER BY Sales DESC;';
		
		return $sql_single_cat_or_tag;		
	}


	public function getsql_parentcat($prefix){
		#by Parent Category
		$sql_parent_cat = <<<sql_parent_cat
		SELECT t.name as Grouping, SUM(oim_price.meta_value) as Sales
		FROM {$prefix}woocommerce_order_items AS oi
		INNER JOIN {$prefix}woocommerce_order_itemmeta AS oim
			ON oi.order_item_id = oim.order_item_id
		INNER JOIN {$prefix}woocommerce_order_itemmeta AS oim_price
			ON oi.order_item_id = oim_price.order_item_id
		INNER JOIN {$prefix}wc_orders AS o
			ON o.id = oi.order_id
		INNER JOIN {$prefix}term_relationships AS tr
			ON tr.object_id = oim.meta_value
		INNER JOIN {$prefix}term_taxonomy AS tt
			ON tt.term_taxonomy_id = tr.term_taxonomy_id
		INNER JOIN {$prefix}terms as t
			ON t.term_id = tt.parent
		WHERE oi.order_item_type = 'line_item'
		AND oim.meta_key = '_product_id'
		AND tt.taxonomy = 'product_cat'
		AND oim_price.meta_key = '_line_subtotal'
		AND o.date_created_gmt  > %s
		AND tt.parent  		
		sql_parent_cat;
		

		$sql_parent_cat = $sql_parent_cat . $this->sqlhelper_in($this->config["SalesByTaxIDs"]).' GROUP BY t.name ORDER BY Sales DESC;';

		return $sql_parent_cat;
	}

	
	public function getData($market){

		if( ! isset($this->config["SalesByTaxType"]) ){
			return json_encode(array("error"=>"SalesByTaxType not set"));		
		}

		$taxType = $this->config["SalesByTaxType"];

		if ($taxType == "parent_category"){
			$sql = $this->getsql_parentcat($this->config["blogPrefix"]);
		}
		else {
			$sql = $this->getsql($this->config["blogPrefix"]);				
		}

		$this->add_arg($this->config["order_date"]);
		$this->add_arg($this->config["SalesByTaxIDs"]);
		
		$psql = $this->db->prepare($sql, $this->args); 		

		write_log($psql);
		
		$results = $this->db->get_results($psql);
		
		$header = array("Sales Group", "Total Sales");
		
		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);
	
		return json_encode($out);		
	}
	
	
	protected function getCols($header){		
		$cols = array();
		foreach($header as $c){
			$t = array("id"=>"", "label"=>$c, "pattern"=>"", "type"=> $c == "Sales Group" ? "string" : "number" );
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

	
	
	
	
	































