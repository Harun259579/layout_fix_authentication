<?php
require_once __DIR__ . '/../admin/dbConfig.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>My Product Store</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" integrity="sha512-rt/SrQ4UNIaGfDyEXZtNcyWvQeOq0QLygHluFQcSjaGB04IxWhal71tKuzP6K8eYXYB6vJV4pHkXcmFGGQ1/0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="stylesheet" type="text/css" href="asstes/css/style.css">

	<style>
		body
		{
			background: #f7f8fa;
		}
		.main-wrap
		{
			display: flex;
		}

		.left-sidebar
		{
			width: 260px;
			min-width: 260px;
			background: #fff;
			border-right: 1px solid #e9ecef;
		}

		.content-area
		{
			flex: 1;
		}

		.cat-item
		{
			display: flex;
			align-items: center;
			margin-bottom: 8px;
		}

		.cat-item input
		{
			margin-right: 8px;
		}
		
		.product-card
		{
			border: 1px solid #e9ecef;
			border-radius: 8px;
			overflow: hidden;
			background-color: #fff;
		}

		.product-card img
		{
			width: 100%;
			height: 180px;
			object-fit: cover;
		}

		.product-card .p-body
		{
			padding: 12px;
		}

		.sticky-sidebar
		{
			position: sticky;
			top: 20px;
		}
	</style>
</head>
<body>

</body>
</html>