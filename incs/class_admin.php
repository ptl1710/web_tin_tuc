<?php
class adminlib extends dblib {
		
	function get_dropdown_category($list, $value) {
		
		$info = '<option value="0">Chọn chuyên mục</option>';
		for ($i = 0; $i < count($list); $i++) {
			
			$selected = $list[$i]["category_id"] == $value ? 'selected' : '';
			
			$info .= '<option value="'.$list[$i]["category_id"].'" '.$selected.' >'.$list[$i]["name"].'</option>';
		}
		
		return $info;
	}
	
	function add_post() {
		$error = array ();
		$data = array ();
		
		$data['title'] = isset($_POST['title']) ? $_POST['title'] : '';
		$data['content'] = isset($_POST['content']) ? $_POST['content'] : '';
		$data['category_id'] = isset($_POST['category_id']) && $_POST['category_id'] != 0 ? $_POST['category_id'] : '';
		
		if (empty($data['title'])) {
			$error['title'] = 'Bạn chưa nhập tiêu đề';
		}
		
		if (empty($data['content'])) {
			$error['content'] = 'Bạn chưa nhập nội dung';
		}
		
		if (empty($data['category_id'])) {
			$error['category_id'] = 'Bạn chưa chọn chuyên mục';
		}
		
		$target_dir = "../images/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
		
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if ($check !== false) {
			$uploadOk = 1;
		}
		else {
			$error["image"] = "File không phải là hình.";
			$uploadOk = 0;
		}
		if (file_exists($target_file)) {
			$error["image"] = "Lổi, file đã tồn tại trên thư mục images rồi";
			$uploadOk = 0;
		}
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			$error["image"] = "Lổi, kích thước file quá lớn";
			$uploadOk = 0;
		}
		if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
			$error["image"] = "Lổi, chỉ cho phép file có phần mở rộng là JPG, JPEG, PNG & GIF.";
			$uploadOk = 0;
		}
		
		if ($uploadOk != 0) {
			if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				$error["image"] = "Xin lổi, đã xảy ra lổi trong khi upload file.";
			}
			else {
				$data['image'] = basename($_FILES["fileToUpload"]["name"]);
			}
		}
		
		if (!$error) {
			$data["createdate"] = date("Y-m-d H:i:s");
			$this->insert("posts", $data);
			$error["note"] = "Thêm bài viết thành công";
			header('Location:post.php');
			die();
		}
		else {
			$error["note"] = "Thêm bài viết thất bại";
		}
		
		$message[0] = $error;
		$message[1] = $data;
		
		return $message;
	}
	
	
	function update_post($post_id) {
		$error = array ();
		$data = array ();
		
		$data['title'] = isset($_POST['title']) ? $_POST['title'] : '';
		$data['content'] = isset($_POST['content']) ? $_POST['content'] : '';
		$data['category_id'] = isset($_POST['category_id']) && $_POST['category_id'] != 0 ? $_POST['category_id'] : '';
		
		if (empty($data['title'])) {
			$error['title'] = 'Bạn chưa nhập tiêu đề';
		}
		
		if (empty($data['content'])) {
			$error['content'] = 'Bạn chưa nhập nội dung';
		}
		
		if (empty($data['category_id'])) {
			$error['category_id'] = 'Bạn chưa chọn chuyên mục';
		}
		
		if ($_FILES["fileToUpload"]["tmp_name"] != '') {
			$target_dir = "../images/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
			
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if ($check !== false) {
				$uploadOk = 1;
			}
			else {
				$error["image"] = "File không phải là hình.";
				$uploadOk = 0;
			}
			
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				$error["image"] = "Lổi, kích thước file quá lớn";
				$uploadOk = 0;
			}
			if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				$error["image"] = "Lổi, chỉ cho phép file có phần mở rộng là JPG, JPEG, PNG & GIF.";
				$uploadOk = 0;
			}
			
			if ($uploadOk != 0) {
				if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$error["image"] = "Xin lổi, đã xảy ra lổi trong khi upload file.";
				}
				else {
					$data['image'] = basename($_FILES["fileToUpload"]["name"]);
				}
			}
		}
		
		if (!$error) {
			
			$this->update("posts", $data, "post_id = $post_id");
			$error["note"] = "Cập nhật bài viết thành công";
		}
		else {
			$error["note"] = "Cập nhật bài viết thất bại";
		}
		
		$message[0] = $error;
		$message[1] = $data;
		
		return $message;
	}
	
	function remove_post($post_id) {
		
		$sql = "SELECT image FROM posts WHERE post_id = $post_id";
		$link = $this->get_row($sql);
		unlink('../images/'.$link['image']);
		
		$this->remove("posts", "post_id = $post_id");
		header('Location:post.php');
		die();
	}
	
	function login() {
		$error = array ();
		$data = array ();
		
		$data['username'] = isset($_POST['username']) ? $_POST['username'] : '';
		$data['password'] = isset($_POST['password']) ? $_POST['password'] : '';
		
		if (empty($data['username'])) {
			$error['username'] = 'Bạn chưa nhập tên';
		}
		
		if (empty($data['password'])) {
			$error['password'] = 'Bạn chưa nhập password';
		}
		
		if (! $error) {
			
			$username = $data['username'];
			$password = md5($data['password']);
			
			$sql = "SELECT count(*) FROM user WHERE username = '$username' AND password = '$password' AND permission = 1 LIMIT 1";
			
			$result = $this->get_row_number($sql);
			
			if ($result > 0) {
				$error['message'] = "Đăng nhập thành công";
				
				setcookie("admin", $username, time() + (86400 * 30));
				header("Location:index.php");
				die();
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