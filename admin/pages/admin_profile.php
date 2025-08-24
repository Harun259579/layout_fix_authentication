<?php
if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

require_once __DIR__.'/../dbConfig.php';

$admin_id = $_SESSION['admin_logged_in'] ?? null;

if(!$admin_id)
{
	header('location: ../login.php');
	exit;
}

//Fetch Admin Info

$stmt = $DB_con->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$username = $_POST['username'];
	$email = $_POST['email'];
	$current_password = $_POST['current_password'];
	$new_password = $_POST['new_password'];
	$confirm_password = $_POST['confirm_password'];
	$photo_name = $admin['photo'];

	//Upload

	if(!empty($_FILES['photo']['name']))
	{
		$target_dir = "../uploads/admins/";
		if(!is_dir($target_dir))
		{
			mkdir($target_dir, 0777, true);
		}

		$photo_name = time(). "-".$_FILES['photo']['name'];

		move_uploaded_file($_FILES['photo']['tmp_name'], $target_dir.$photo_name);
	}

	//Password
	if(!empty($current_password))
	{
		if(password_verify($current_password, $admin['password']))
		{
			if($new_password == $confirm_password)
			{
				$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
			}

			else
			{
				$message = "<div class='alert alert-danger'>New password do not match</div>";
			}
		}

		else
		{
			$message = "<div class='alert alert-danger'>Current password is incorrect</div>";
		}
	}

	else
	{
		$hashed_password = $admin['password'];
	}

	//Update Database
	if(empty($message))
	{
		$stmt = $DB_con->prepare("UPDATE admins SET username = ?, email = ?, password = ?, photo = ? WHERE id = ?");
		$stmt->execute([$username,$email, $hashed_password, $photo_name, $admin_id]);

		$message = "<div class='alert alert-success'>Profile Updated Successfully</div>";

		//Refresh Form

		$stmt = $DB_con->prepare("SELECT * FROM admins WHERE id = ?");
		$stmt->execute([$admin_id]);
		$admin = $stmt->fetch(PDO::FETCH_ASSOC); 
		
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Profile</title>
</head>
<body>
	<h4>Admin Profile</h4>
	<?= $message ?>
	<form method="POST" enctype="multipart/form-data">
		<div class="form-group">
			<label>Username:</label>
			<input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" class="form-control" required>
		</div>

		<div class="form-group">
			<label>Email:</label>
			<input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" class="form-control" required>
		</div>

		<hr>

		<h6>Change Password</h6>
		<div class="form-group">
			<label>Current Password:</label>
			<input type="password" name="current_password" class="form-control">
		</div>

		<div class="form-group">
			<label>New Password:</label>
			<input type="password" name="new_password" class="form-control">
		</div>

		<div class="form-group">
			<label>Confirm Password:</label>
			<input type="password" name="confirm_password" class="form-control">
		</div>

		<hr>

		<div class="form-group">
			<label>Profile Photo:</label>
			<?php if($admin['photo']):?>
			<img src="../uploads/admins/<?= $admin['photo'] ?>" width="80" class="rounded mb-2" alt="no image found">
			<?php endif; ?>
			<input type="file" name="photo" class="form-control-file">
		</div>

		<button type="submit" class="btn btn-success">Update Profile</button>
	</form>
	
</body>
</html>