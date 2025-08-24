<?php
session_start();
include "dbConfig.php";

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$username = $_POST['username'] ?? '';
	$password = $_POST['password'] ?? '';

	$stmt = $DB_con->prepare("SELECT * FROM admins WHERE username = ?");
	$stmt->execute([$username]);

	if($stmt->rowCount() === 1)
	{
		$admin = $stmt->fetch(PDO::FETCH_ASSOC);
		if(password_verify($password, $admin['password']))
		{
			$_SESSION['admin_logged_in'] = $admin['id'];
			$_SESSION['admin_username'] = $admin['username'];
			header("Location: index.php");
			exit;
		}

		else
		{
			$error = "Invalid Password";
		}
	}

	else
	{
		$error = "Admin user not found!";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Login</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" integrity="sha512-rt/SrQ4UNIaGfDyEXZtNcyWvQeOq0QLygHluFQcSjaGB04IxWhal71tKuzP6K8eYXYB6vJV4pHkXcmFGGQ1/0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-light">
	<div class="container mt-5">
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card shadow">
					<div class="card-body">
						<h4 class="card-title text-center">Admin Login</h4>

						<?php if($error):?>
							<div class="alert alert-danger">
								<?= htmlspecialchars($error);?>
							</div>
						<?php endif; ?>

						<form method="POST">
							<div class="form-group">
								<label>Username</label>
								<input type="text" name="username" class="form-control" required>
							</div>

							<div class="form-group">
								<label>Password</label>
								<input type="password" name="password" class="form-control" required>
							</div>

							<button type="submit" class="btn btn-primary btn-block">Login</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>