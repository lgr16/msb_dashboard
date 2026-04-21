<?php
include_once("Report.php");
class ReportAllMarkets extends Report {

	
	public function getData($market){
		
		$config = $this->config;
		
		$allMarkets = isset($config["all_markets_report"]) ? $config["all_markets_report"] : [];
        $showDigitalSplit = isset($config["showDigitalSplit"]) ? true : false;
        $header = array("Market", "Total Orders","Total Sales");

        if($showDigitalSplit) {
            $header = array("Market", "Digital Count","Radio Count", "Digital Sales", "Radio Sales");
        }

		$sql = $this->getBigQuery($allMarkets, $config["order_date"], $showDigitalSplit);

		$results = $this->db->get_results($sql);

		$out["cols"] = $this->getCols($header);
		$out["rows"] = $this->getRows($results);

		return json_encode($out);
	}


	protected function getsql($prefix){
		return true;
	}


    protected function get_tag_id($tag_slug, $blog_id) {
        switch_to_blog($blog_id);
        $tag = get_term_by('slug', $tag_slug, 'product_tag');
        restore_current_blog();
        return $tag ? $tag->term_id : 0;
    }


	protected function getBigQuery($all_markets, $date, $showDigital=false){
		
		$bigQuery = "";

		foreach($all_markets as $marketName => $blogID){
			
			$bigQuery .= $this->gsql($blogID, $marketName, $date, $showDigital);
			$bigQuery .= "\n";
			$bigQuery .= "UNION\n";
			
		}
		
		$bigQuery =  preg_replace('/UNION$/', '', trim($bigQuery) );

		return $bigQuery;
	}

	
	protected function gsql($blogID, $market, $date, $showDigital=false) {

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

	        if($showDigital) {
                $digitalTagID = $this->get_tag_id('digital', $blogID);

                $sqlDigital = <<<sqlDigital
                select
                    "{$market}" as Market,
                    SUM( CASE WHEN a.isDigital > 0 THEN 1 ELSE 0 END) as DigitalCount,
                    SUM( CASE WHEN a.isDigital = 0 THEN 1 ELSE 0 END) as Not_Digital_Count,
                    sum( CASE WHEN a.isDigital > 0 THEN	a.ItemTotal ELSE 0 END) as "Digital Sales",
                    SUM( CASE WHEN a.isDigital = 0 THEN	a.ItemTotal ELSE 0 END) as "Not Digital Sales"
                from (
                select p.id,
                    MAX(CASE WHEN im_tot.meta_key = '_line_total' AND oi.order_id = pm.order_id  THEN im_tot.meta_value END) as ItemTotal,
                    MAX(CASE WHEN tt.term_id = "{$digitalTagID}" THEN 1 ELSE 0 END) AS isDigital
                    from wpmsb_{$blogID}_wc_orders as p
                    inner join  wpmsb_{$blogID}_woocommerce_order_items as oi on oi.order_id = p.id
                    inner join  wpmsb_{$blogID}_woocommerce_order_itemmeta as im_tot on im_tot.order_item_id = oi.order_item_id
                    inner join  wpmsb_{$blogID}_woocommerce_order_itemmeta as im_pid on im_pid.order_item_id = oi.order_item_id
                    inner join wpmsb_{$blogID}_wc_orders_meta as pm on pm.order_id = p.id
                    INNER JOIN wpmsb_{$blogID}_term_relationships AS tr  ON tr.object_id = im_pid.meta_value
                    INNER JOIN wpmsb_{$blogID}_term_taxonomy AS tt	ON tt.term_taxonomy_id = tr.term_taxonomy_id
                    INNER JOIN wpmsb_{$blogID}_terms as t ON t.term_id = tt.term_taxonomy_id
                WHERE p.type = "shop_order" AND p.status in ("wc-on-hold", "wc-processing", "wc-completed") AND
                    p.date_created_gmt >= "{$date}" AND
                    im_tot.meta_key = "_line_total" AND
                    im_pid.meta_key = "_product_id" AND
                    oi.order_item_type != 'tax' AND
                    (tt.taxonomy = 'product_cat' or tt.taxonomy = 'product_tag')
                    group by oi.order_item_name, p.ID
                ) a
                sqlDigital;

                $rval = $sqlDigital;
            } else {
                $rval = $sql;
            }

            return $rval;
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
