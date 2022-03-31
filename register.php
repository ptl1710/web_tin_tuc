<?php
function inc()
{
	include 'incs/class_db.php';
	include 'incs/class_home.php';
}

inc();

$homelib = new homelib();

if (isset($_POST["register_action"])) {
	$message = $homelib->resgister();
	$error = $message[0];
	$data = $message[1];
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Đăng ký</title>
    <meta charset="UTF-8">
</head>

<body>
    <h1>Đăng ký</h1>
    <form method="post" action="register.php">
        <table cellspacing="0" cellpadding="5">
            <tr>
                <td>Tên của bạn</td>
                <td><input type="text" name="username" id="username"
                        value="<?php echo isset($data['username']) ? $data['username'] : ''; ?>" />
                    <?php echo isset($error['username']) ? $error['username'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Email của bạn</td>
                <td><input type="text" name="email" id="email"
                        value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" />
                    <?php echo isset($error['email']) ? $error['email'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Mật khẩu của bạn</td>
                <td><input type="text" name="password" id="password"
                        value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>" />
                    <?php echo isset($error['password']) ? $error['password'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button type="button" name="register_action" onclick="location.href='index.php'">Đăng Kí</button>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>