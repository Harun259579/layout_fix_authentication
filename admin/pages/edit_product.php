<?php
include 'dbConfig.php';

if(!isset($_GET['id']))
{
	die('Invalid Request');
}

$decoded_id = base64_decode(urldecode($_GET['id']));

$stmt = $DB_con->prepare("SELECT * FROM products WHERE id = ?");

$stmt->execute([$decoded_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$product)
{
	die("Product Not Found!");
}

//Attribute Fetch

$stmtAttr = $DB_con->prepare("SELECT * FROM attributes WHERE product_id = ?");

$stmtAttr->execute([$decoded_id]);

$attribute = $stmtAttr->fetch(PDO::FETCH_ASSOC);

//Handle Update
if(isset($_POST['update']))
{
	$productname = $_POST['product_name'];
	$description = $_POST['description'];
	$productstock = $_POST['product_stock'];
	$unit_price = $_POST['unit_price'];
	$selling_price = $_POST['selling_price'];
	$category_id = $_POST['category_id'];
	$has_attributes = isset($_POST['has_attributes']) ? 1 : 0;
	$sizes = isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '';
	$colors = $_POST['colors'];

	//Image Reupload
	$new_image = $product['product_image'];

	if(!empty($_FILES['product_image']['name']))
	{
		$imgfile = $_FILES['product_image']['name'];
		$tem_dir = $_FILES['product_image']['tmp_name'];
		$imgext = strtolower(pathinfo($imgfile, PATHINFO_EXTENSION));
		$valid_extensions = ['jpg','jpeg','png','gif'];
		$upload_dir = "uploads/";

		if(in_array($imgext, $valid_extensions))
		{
			$new_image = rand(1000, 1000000000).".".$imgext;
			move_uploaded_file($tem_dir, $upload_dir.$new_image);
			if(file_exists($upload_dir.$product['product_image']))
			{
				unlink($upload_dir.$product['product_image']);
			}
		}
	}

	//Database Update
	$stmt = $DB_con->prepare("UPDATE products SET product_name = ?, description = ?, product_image = ?, unit_price = ?, selling_price = ?, stock_amount = ?, has_attributes = ?, category_id = ? WHERE id = ?");
	$stmt->execute([$productname, $description, $new_image, $unit_price, $selling_price, $productstock, $has_attributes, $category_id, $decoded_id]);

	//Attributes table update

	if($has_attributes)
	{
		if($attribute)
		{
			$stmtAttr = $DB_con->prepare("UPDATE attributes SET sizes = ?, colors = ? WHERE product_id = ?");
			$stmtAttr->execute([$sizes, $colors, $decoded_id]);
		}

		else
		{
			$stmtAttr = $DB_con->prepare("INSERT INTO attributes(product_id, sizes, colors) VALUES (?,?,?)");
			$stmtAttr->execute([$decoded_id, $sizes, $colors]);
		}
	}

	else
	{
		$stmtAttr = $DB_con->prepare("DELETE FROM attributes WHERE product_id = ?");
		$stmtAttr->execute([$decoded_id]);
	}

	$success = "Product updated successfully";
	header('refresh: 5 products.php');
}

//All Categories Fetch

$cats = $DB_con->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Update Product</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha512-Dop/vW3iOtayerlYAqCgkVr2aTr2ErwwTYOvRFUpzl2VhCMJyjQF0Q9TjUXIo6JhuM/3i0vVEt2e/7QQmnHQqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

	<div class="container mt-5">
		<h3 class="mb-4">Edit Product</h3>

		<?php
			if(!empty($success)) echo "<div class='alert alert-success'>$success</div>";
		?>

		<form method="POST" enctype="multipart/form-data">
			<div class="form-group">
				<label>Product name:</label>
				<input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name'])?>">
			</div>

			<div class="form-group">
				<label>Description:</label>
				<textarea name="description" class="form-control"><?= htmlspecialchars($product['description'])?></textarea>
			</div>

			<div class="form-group">
				<label>Stock Amount:</label>
				<input type="number" name="product_stock" class="form-control" value="<?= (int)$product['stock_amount']?>">
			</div>

			<div class="form-group">
				<label>Category:</label>
				<select name="category_id" class="form-control" required>
					<?php 

						foreach ($cats as $cat):?>
							<option value="<?= $cat['id']?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['category_name']); ?>						
							</option>
						<?php endforeach; ?>
				</select>
			</div>

			<div class="form-group">
				<label>Current Image:</label>
				<img src="uploads/<?= $product['product_image']?>" alt="Image Not Found" width="100"><br><br>
				<input type="file" name="product_image" class="form-control-file">
			</div>

			<div class="form-group">
				<label>Unit Price:</label>
				<input type="number" name="unit_price" class="form-control" value="<?= (int)$product['unit_price']?>">
			</div>

			<div class="form-group">
				<label>Selling Price:</label>
				<input type="number" name="selling_price" class="form-control" value="<?= (int)$product['selling_price']?>">
			</div>

			<div class="form-check">
				<input type="checkbox" name="has_attributes" class="form-check-input" id="hasAttributes" <?= $product['has_attributes'] ? 'checked' : '' ?> onclick="toggleAttribute()">

				<label class="form-check-label">Has Attributes?</label>
			</div>

			<div id="attributeSection" style="display: <?= $product['has_attributes'] ? 'block' : 'none'?>;">
				<div class="form-group mt-2">
					<label>Sizes:</label>
					<?php $selectedSizes = explode(',',$attribute['sizes'] ?? ''); ?>
					<?php foreach(['L','XL','XXL'] as $size):?>
						<label class="mr-2">
							<input type="checkbox" name="sizes[]" value="<?= $size ?>" <?= in_array($size, $selectedSizes) ? 'checked' : ''?>><?= $size ?>
						</label>
					<?php endforeach; ?>
				</div>

				<div class="form-group">
					<label>Add Color:</label>
					<input type="color" name="" class="color-input" value="#000000">
					<button type="button" class="btn btn-sm btn-secondary" onclick="addColor()">Add Color</button>
					<div id="colorList" class="mt-2"></div>
					<input type="hidden" name="colors" id="colors" value="<?= $attribute['colors'] ?? '' ?>">
				</div>							
			</div>

			<button type="submit" name="update" class="btn btn-success">Update Product</button>

			<a href="?page=products" class="btn btn-secondary">Back</a>
		</form>>
	</div>
</body>
</html>

<script type="text/javascript">
	let selectedColors = <?= json_encode(explode(',',$attribute['colors']?? '')) ?>;

	function toggleAttributes()
	{
		const attrSection = document.getElementById('attributeSection');
		attrSection.style.display = document.getElementById('hasAttributes').checked ? 'block' : 'none';
	}

	function addColor()
	{
		const colorInput = document.querySelector('.color-input');
		const color = colorInput.value;

		if(!selectedColors.includes(color))
		{
			selectedColors.push(color);
			updateColorList();
		}
	}

	function updateColorList()
	{
		const colorList = document.getElementById('colorList');
		const colorInput = document.getElementById('colors');
		colorList.innerHTML = '';

		selectedColors.forEach((color, index) => {

			const colorBox = document.createElement('div');
			colorBox.style.display = 'inline-block';
			colorBox.style.backgroundColor = color;
			colorBox.style.width = '30px';
			colorBox.style.height = '30px';
			colorBox.style.marginRight = '5px';
			colorBox.style.border = '1px solid #000';
			colorBox.title = color;

			colorBox.onclick = () =>
			{
				selectedColors.splice(index, 1);
				updateColorList();
			};

			colorList.appendChild(colorBox);
		});

		colorInput.value = selectedColors.join(',');
	}

	updateColorList();
</script>