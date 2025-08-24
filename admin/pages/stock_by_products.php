<?php
require_once "dbConfig.php";

//Fetch Products
$stmt = $DB_con->query("SELECT id, product_name, stock_amount FROM products ORDER BY product_name");

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$selected_id = $_GET['product_id'] ?? null;
$logs = []; 

if($selected_id)
{
	$query = "SELECT i.*, p.product_name FROM inventory i JOIN products p ON i.product_id = p.id WHERE i.product_id = ? ORDER BY i.created_at DESC";

	$stmt2 = $DB_con->prepare($query);
	$stmt2->execute([$selected_id]);
	$logs = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Stock by Product</title>
</head>
<body>
	<h4>See stock by Product</h4>

	<form method="get" class="form-inline mb-3">		
		<input type="hidden" name="page" value="stock_by_products">
		<label>Select Product:</label>
		<select name="product_id" class="form-control mr-2" required>
			<option value="">select</option>
			<?php
				foreach ($products as $p):?>
				<option value="<?= $p['id']?>" <?= ($selected_id == $p['id']) ? 'selected' : '' ?>> 
						<?= $p['product_name'] ?>				
					</option>
			<?php endforeach; ?>
		</select>
		<button type="submit" class="btn btn-primary">View</button>
	</form>

	<?php if ($selected_id && count($logs) > 0): ?>
		<h5>Stock History for: <?= htmlspecialchars($logs[0]['product_name']) ?></h5>
		<table class="table table-bordered table-striped">
			<thead class="thead-dark">
				<tr>
					<th>Date</th>
					<th>Change Type</th>
					<th>Quantity</th>
					<th>Remarks</th>
				</tr>
			</thead>
		
		<tbody>
			<?php foreach($logs as $log):?>				
			<tr>
				<td><?= $log['created_at'] ?></td>
				<td>
					<?php if($log['change_type'] === 'in'):?>
						<span class="badge badge-success">IN</span>
						<?php else: ?>
							<span class="badge badge-danger">Out</span>
						<?php endif; ?>
				</td>
				<td><?= $log['quantity'] ?></td>
				<td><?= htmlspecialchars($log['remarks']) ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php elseif ($selected_id): ?>
		<div class="alert alert-info">No stock history found for this product.</div>
	<?php endif; ?>
</body>
</html>
