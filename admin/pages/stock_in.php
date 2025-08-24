<?php
require_once 'dbConfig.php';
//Fetch Products
$stmt = $DB_con->query("SELECT id, product_name, stock_amount FROM products ORDER BY product_name");

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	$product_id = $_POST['product_id'];
	$quantity = $_POST['quantity'];
	$remarks = $_POST['remarks'];

	//Update product stock

	$update = $DB_con->prepare("UPDATE products SET stock_amount = stock_amount + ? WHERE id = ? ");
	$update->execute([$quantity, $product_id]);

	//Insert into inventory

	$log = $DB_con->prepare("INSERT INTO inventory (product_id, change_type, quantity, remarks) VALUES (?,'in',?,?)");

	$log->execute([$product_id, $quantity, $remarks]);

	echo "<div class='alert alert-success'>Stock Added Successfully</div>";

	header('location: ?page=products');

}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Stock In</title>
</head>
<body>

	<form method="POST">
		<div class="form-group">
			<label>Select Product</label>
			<select name="product_id" class="form-control" required>
				<option value="">select</option>
				<?php
					foreach($products as $p):?>
						<option value="<?= $p['id'] ?>"><?= $p['product_name'] ?>(Current: <?= $p['stock_amount']?>)</option>
					<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label>Quantity</label>
			<input type="number" name="quantity" class="form-control" required>
		</div>

		<div class="form-group">
			<label>Remarks</label>
			<input type="text" name="remarks" class="form-control">
		</div>

		<button type="submit" class="btn btn-primary">Add Stock</button>
	</form>

</body>
</html>