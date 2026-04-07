<?php

abstract class Report {
	 
    abstract protected function getsql($prefix);
    abstract protected function getCols($header);
	abstract protected function getRows($data);
	abstract public function getData($market);
	
	protected $db = null;
	protected $config = null;
	protected $args = [];
	
	public function __construct($database, $config) 
    {
		$this->db = $database;
		$this->config = $config;
	}
	

	//args must be added in order of replacement in sql 
	protected function add_arg($newArg){
		if (is_array($newArg) ){
			$this->args = array_merge($this->args, $newArg);
		}
		else {
			$this->args[] = $newArg;
		}	
	}

	protected function sqlhelper_campaigns(){
		$market = ' = %s';
		$campaigns = $this->config["market_ga_campaign"];
		
		if (isset($this->config["ga_campaigns"])){
			$arr = $this->config["ga_campaigns"];
			if (is_array($arr)){			
				$market = " in (" . implode(', ', array_map( function($v){return "%s";}, $arr)) . ")";
				$campaigns = $arr;				
			}
		}
		
		return array("market"=> $market, "campaigns"=>$campaigns);
		
	}

	//returns a string for substituting in the sql statement, like "in ( %s, %s, %s )"
	// $valuesArray is an array of values to be used in the sql statement
	// the values are passed in as %s, so they can be strings or numbers
	protected function sqlhelper_in($valuesArray)
	{
		$in = " in (";
		$in .= implode(', ', array_map( function($v){return "%s";}, $valuesArray));
		$in .= ")";
		
		return $in;
	}

	
	protected function write_log( $data ) {
		if ( true === WP_DEBUG ) {  // 
			if ( is_array( $data ) || is_object( $data ) ) {
				error_log( print_r( $data, true ) );
			} else {
				error_log( $data );
			}
		}
	}
	
	

}  //class































