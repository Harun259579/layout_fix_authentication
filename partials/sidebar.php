<?php
$catStmt = $DB_con->query("SELECT id, category_name FROM categories ORDER BY category_name");

$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<aside class="left-sidebar p-3">
	<div class="sticky-sidebar">
		<h5 class="mb-3">Filter by Category</h5>
		<div id="catBox">
			<?php foreach($categories as $c): ?>
				<label class="cat-item">
					<input type="checkbox" class="cat-check" value="<?= (int)$c['id']?>" name="cat_name"><span><?= htmlspecialchars($c['category_name']) ?></span>
				</label>
			<?php endforeach; ?>
		</div>

		<button id="clearFilter" class="btn btn-sm btn-outline-secondary mt-2">Clear</button>
	</div>
</aside>