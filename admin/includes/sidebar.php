<div class="sidebar bg-dark text-white p-3" style="width: 250px; height: 100vh; position: fixed;">
	
	<div class="text-center mb-3">
		<?php

			if(!isset($_SESSION)) session_start();
			require_once __DIR__.'/../dbConfig.php';
			$admin_id = $_SESSION['admin_logged_in'] ?? null;

			$adminPhoto = 'default.jpg';
			$adminName = 'Admin';

			if($admin_id)
			{
				$stmt = $DB_con->prepare("SELECT * FROM admins WHERE id =?");
				$stmt->execute([$admin_id]);
				$admin = $stmt->fetch(PDO::FETCH_ASSOC);

				if($admin)
				{
					$adminPhoto = !empty($admin['photo']) ? $admin['photo'] : 'default.jpg';
					$adminName = $admin['username'];
				}
			}		

		?>

		<img src="../uploads/admins/<?= htmlspecialchars($adminPhoto) ?>" alt="No photo found" class="rounded-circle" width="80" height="80">

		<h5 class="mt-2"><?= htmlspecialchars($adminName) ?></h5>
	</div>

	<h4 class="mb-3">Admin Panel</h4>

	<a href="index.php?page=dashboard" class="d-block mb-2 text-white">Dashboard</a>
	<a href="index.php?page=products" class="d-block mb-2 text-white">Products</a>
	<a href="index.php?page=categories" class="d-block mb-2 text-white">Categories</a>
	<a href="index.php?page=attributes" class="d-block mb-2 text-white">Attributes</a>

	<div class="mb-2">
		<a href="#inventorySubmenu" data-toggle="collapse" class="dropdown-toggle d-block text-white">Inventory</a>

		<ul class="collapse list-unstyled pl-3" id="inventorySubmenu">
			<li>
				<a href="index.php?page=stock_in" class="text-white d-block py-1">Stock In</a>
			</li>
			<li>
				<a href="index.php?page=stock_out" class="text-white d-block py-1">Stock Out</a>
			</li>
			<li>
				<a href="index.php?page=stock_by_products" class="text-white d-block py-1">Stock by Products</a>
			</li>
			<li>
				<a href="index.php?page=inventory_report" class="text-white d-block py-1">Reports</a>
			</li>
		</ul>
	</div>
	<a href="index.php?page=admin_profile" class="d-block mb-2 text-white">Change Profile</a>
	<a href="logout.php" class="d-block text-white">Logout</a>
</div>
