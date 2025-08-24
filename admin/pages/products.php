<?php
include 'dbConfig.php';

//Handle Delete Operation
error_reporting(0);
if(isset($_GET['delete_id']))
{
	$delete_id = (int)base64_decode(urldecode($_GET['delete_id']));

	//Fetch Image

	$stmtImg = $DB_con->prepare("SELECT product_image FROM products WHERE id = ?");
	$stmtImg->execute([$delete_id]);
	$productImg = $stmtImg->fetchColumn();

	if($productImg && file_exists("uploads/$productImg"))
	{
		unlink("uploads/$productImg");
	}

} 

$stmtAttr = $DB_con->prepare("DELETE FROM attributes WHERE product_id = ?");
$stmtAttr->execute([$delete_id]);

$stmtDel = $DB_con->prepare("DELETE FROM products WHERE id = ?");
$stmtDel->execute([$delete_id]);

header('refresh: 5');

//Fetch all products

$stmt = $DB_con->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
	<title>All Products</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha512-Dop/vW3iOtayerlYAqCgkVr2aTr2ErwwTYOvRFUpzl2VhCMJyjQF0Q9TjUXIo6JhuM/3i0vVEt2e/7QQmnHQqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<style type="text/css">
		.thumbnail-img
		{
			width: 80px;
			height: 80px;
			object-fit: cover;
		}

		.color-box
		{
			display: inline-block;
			width: 20px;
			height: 20px;
			border: 10px solid #000;
			margin-right: 4px;
		}
	</style>
</head>
<body>
	<div class="container mt-5">
		<h2 class="mb-4">All Products</h2>

		<a href="?page=addNew" class="btn btn-primary mb-2">Add New Products</a>
		<p></p>
		<table class="table table-bordered table-hover">
			<thead class="thead-dark">
				<tr>
					<th>Image</th>
					<th>Name</th>
					<th>Description</th>
					<th>Stock</th>
					<th>Unit Price</th>
					<th>Selling Price</th>
					<th>Category</th>
					<th>Size</th>
					<th>Colors</th>
					<th>Actions</th>
				</tr>
			</thead>
			<?php

				if($products):?>
					<?php foreach($products as $row):?>
						<?php

							$product_id = $row['id'];
							$encrypted_id = urlencode(base64_encode($product_id));
							$attrStmt = $DB_con->prepare("SELECT sizes, colors FROM attributes WHERE product_id = ?");
							$attrStmt->execute([$product_id]);

							$attrubute = $attrStmt->fetch(PDO::FETCH_ASSOC);
							$sizes = $attrubute['sizes'] ?? '';
							$colors = $attrubute['colors'] ?? '';

							$colorArray = explode(',', $colors);
						?>
				<tr>
					<td>
						<img src="uploads/<?php echo htmlspecialchars($row['product_image']); ?>" alt="No image found" class="thumbnail-img">
					</td>

					<td>
						<?php echo htmlspecialchars($row['product_name']);?>
					</td>

					<td>
						<?php echo htmlspecialchars($row['description']);?>
					</td>

					<td>
						<?php echo (int)$row['stock_amount'];?>
					</td>

					<td>
						<?php echo (int)$row['unit_price'];?>
					</td>

					<td>
						<?php echo (int)$row['selling_price'];?>
					</td>

					<td>
						<?php 

							if(!empty($row['category_id']))
							{
								$catStmt = $DB_con->prepare("SELECT category_name FROM categories WHERE id = ?");

								$catStmt->execute([$row['category_id']]);
								echo htmlspecialchars($catStmt->fetchColumn());
							}

							else
							{
								echo "N/A";
							}
						?>
					</td>

					<td>
						<?php echo $sizes ? htmlspecialchars($sizes) : 'N/A'; ?>

					</td>

					<td>
						<?php

							if($colors):
						?>

						<?php foreach ($colorArray as $color):?>
							<span class="color-box" style="background-color: <?php echo htmlspecialchars($color); ?>" title="<?php echo $color; ?>"></span>
						<?php endforeach; ?>

						<?php else: ?>
							---
						<?php endif; ?>						
					</td>

					<td>
						<a href="?page=edit_product&id=<?php echo $encrypted_id; ?>" class="btn btn-sm btn-warning">Edit</a>
					</td>

					<td>
						<a href="?page=products&delete_id=<?php echo $encrypted_id; ?>" class="btn btn-sm btn-danger">Delete</a>
					</td>

					<td>
						<a href="view_products.php" class="btn btn-sm btn-info">View Details</a>
					</td>
				</tr>

			<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan="8" class="text-center">No products found!
						
					</td>
				</tr>
			<?php endif; ?>
		</table>
	</div>

</body>
</html>