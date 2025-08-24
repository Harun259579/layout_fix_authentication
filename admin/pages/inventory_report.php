<?php
require_once 'dbConfig.php';

$query = "SELECT i.*, p.product_name FROM inventory i JOIN products p ON i.product_id = p.id ORDER BY i.created_at DESC";

$stmt = $DB_con->query($query);

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Inventory Report</h4>
<table class="table table-bordered table-striped">
	<thead class="thead-dark">
		<tr>
			<th>Date</th>
			<th>Product</th>
			<th>Change Type</th>
			<th>Quantity</th>
			<th>Remarks</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($logs as $log):?>
			<tr>
				<td><?= $log['created_at'] ?></td>
				<td><?= htmlspecialchars($log['product_name']) ?></td>
				<td>
					<?php if($log['change_type'] === "in"):?>
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