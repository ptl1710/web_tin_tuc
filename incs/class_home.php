<?php
class homelib extends dblib{
	
	function resgister() {
		$error = array();
		$data = array();
		
		$data ['username'] = isset ( $_POST ['username'] ) ? $_POST ['username'] : '';
		$data ['email'] = isset ( $_POST ['email'] ) ? $_POST ['email'] : '';
		$data ['password'] = isset ( $_POST ['password'] ) ? $_POST ['password'] : '';
		
		if (empty($data ['username'])) {
			$error['username'] = 'Bạn chưa nhập tên';
		}
		
		if (empty($data ['email'])) {
			$error['email'] = 'Bạn chưa email';
		}
		
		if (!filter_var($data ['email'], FILTER_VALIDATE_EMAIL)) { 
			
			$error['email'] = 'Email không đúng định dạng';
		}
		
		if (empty($data ['password'])) {
			$error['password'] = 'Bạn chưa nhập password';
		}
		
		if (!$error) {
			
			$data ['password'] = md5($data ['password']);
			$data["createdate"] = date("Y-m-d H:i:s");
			
			$this->insert("user", $data);
			$data['password'] = $_POST["password"];
			
			$error["note"] = "Đăng ký thành công";
		}
		else {
			$error["note"] = "Đăng ký thất bại";
		}
		
		$message[0] = $error;
		$message[1] = $data;
		
		return $message;
	}
	
	function login() {
		$error = array();
		$data = array();
		
		$data ['username'] = isset ( $_POST ['username'] ) ? $_POST ['username'] : '';
		$data ['password'] = isset ( $_POST ['password'] ) ? $_POST ['password'] : '';
		
		if (empty($data ['username'])) {
			$error['username'] = 'Bạn chưa nhập tên';
		}
		
		if (empty($data ['password'])) {
			$error['password'] = 'Bạn chưa nhập password';
		}
		
		if (!$error) {
			
			$username = $data ['username'];
			$password = md5($data ['password']);
			
			$sql = "SELECT count(*) FROM user WHERE username = '$username' AND password = '$password' LIMIT 1";
			
			$result = $this->get_row($sql);
			
			if ($result > 0) {
				$error['message'] = "Đăng nhập thành công";
				
				setcookie("user", $username, time() + (86400 * 30));
				
			}
			else {
				$error['message'] = "username hoặc password không đúng!";
			}
		}
		
		$message[0] = $error;
		$message[1] = $data;
		
		return $message;
	}
}
?>