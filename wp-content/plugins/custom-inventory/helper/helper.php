<?php 

class Helper{

	protected $db = null;

	function __construct(){

		global $wpdb;

		$this->db = $wpdb; 

	}

	public function insert_deposit($order_id, $deposit){

		$this->db->query("INSERT INTO `wp_order_deposit` (`id`, `order_id`, `deposit`) VALUES (NULL, '".$order_id."', '".$deposit."')");
	}

	public function get_sum_deposit($order_id){

		$querystr = "
		    SELECT SUM(deposit) FROM `wp_order_deposit` WHERE  order_id =".$order_id."
		 ";

		$sum = $this->db->get_var($querystr);

		return $sum;
	}


	public function get_deposits($order_id){

		$querystr = "
		    SELECT * FROM `wp_order_deposit` WHERE  order_id =".$order_id."
		 ";

		$deposits = $this->db->get_results($querystr, OBJECT);

		return $deposits;
	}
}