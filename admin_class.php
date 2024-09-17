<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$type = array("","users","faculty_list","student_list");
		$type2 = array("","admin","faculty","student");
			$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM {$type[$login]} where email = '".$email."' and password = '".md5($password)."'  ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_type'] = $login;
					$_SESSION['login_view_folder'] = $type2[$login].'/';
		$academic = $this->db->query("SELECT * FROM academic_list where is_default = 1 ");
		if($academic->num_rows > 0){
			foreach($academic->fetch_array() as $k => $v){
				if(!is_numeric($k))
					$_SESSION['academic'][$k] = $v;
			}
		}
				return 1;
		}else{
			return 2;
		}
	}
}