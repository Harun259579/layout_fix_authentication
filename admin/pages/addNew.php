<?php
error_reporting(0);
include 'dbConfig.php';

$errmsg = '';
$successmsg = '';

//Fetch Categories

$cat_stmt = $DB_con->prepare("SELECT * FROM categories");
$cat_stmt->execute();
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['btnsave']))
{
	$productname = $_POST['product_name'];
	$description = $_POST['description'];
	$productstock = $_POST['product_stock'];
	$category_id = $_POST['category_id'];
	$unit_price = $_POST['unit_price'];
	$selling_price = $_POST['selling_price']; 
	$has_attributes = isset($_POST['has_attributes']) ? 1 : 0;
	$book_type = $_POST['book_type'] ?? null;
	$virtual_file = null;
	$sizes = isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '';
	$colors = isset($_POST['colors']) ? $_POST['colors'] : '';

	//Product Image Upload

	$image_name = '';

	if(!empty($_FILES['product_image']['name']))
	{
		$image_name = time().'_'.$_FILES['product_image']['name'];
		move_uploaded_file($_FILES['product_image']['tmp_name'], "uploads/".$image_name);
	}

	else if($book_type === 'downloadable')
	{
		$image_name = 'pdf-icon.png';
	}

	//Upload Virtual pdf book

	if($book_type === 'downloadable' && isset($_FILES['virtual_pdf']['name']) && $_FILES['virtual_pdf']['error'] == 0)
	{
		$upload_dir = "uploads/virtual_contents/";
		if(!is_dir($upload_dir))
		{
			mkdir($upload_dir, 0777, true);
		}

		$virtual_file = time().'_'.basename($_FILES['virtual_pdf']['name']);
		
		move_uploaded_file($_FILES['virtual_pdf']['tmp_name'], $upload_dir.$virtual_file);
	}
	

	if(empty($errmsg))
	{
		$stmt = $DB_con->prepare("INSERT INTO products (product_name, description, product_image, unit_price, selling_price, stock_amount, has_attributes, category_id, book_type, virtual_file) VALUES (:pname, :pdesc,:ppic,:uprice, :sprice, :pstock,:hasattr,:cat_id, :btype, :vfile)");

		$stmt->bindParam(':pname',$productname);
		$stmt->bindParam(':pdesc',$description);
		$stmt->bindParam(':ppic',$image_name);
		$stmt->bindParam(':uprice',$unit_price);
		$stmt->bindParam(':sprice',$selling_price);
		$stmt->bindParam(':pstock',$productstock);
		$stmt->bindParam(':hasattr',$has_attributes);
		$stmt->bindParam(':cat_id',$category_id);
		$stmt->bindParam(':btype',$book_type);
		$stmt->bindParam(':vfile',$virtual_file);

		if($stmt->execute())
		{
			$lastProductId = $DB_con->lastInsertId();

			if($has_attributes)
			{
				$attr_stmt = $DB_con->prepare("INSERT INTO attributes(product_id, sizes, colors) VALUES (:pid, :sizes, :colors)");

				$attr_stmt->bindParam(':pid', $lastProductId);
				$attr_stmt->bindParam(':sizes', $sizes);
				$attr_stmt->bindParam(':colors', $colors);
				$attr_stmt->execute();

			}

			$successmsg = "New Product Inserted Successfully";
		}

		else
		{
			$errmsg = "Error while inserting";
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add New Products</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha512-Dop/vW3iOtayerlYAqCgkVr2aTr2ErwwTYOvRFUpzl2VhCMJyjQF0Q9TjUXIo6JhuM/3i0vVEt2e/7QQmnHQqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

	<div class="container mt-5">
		<h3 class="mb-4">Add Products</h3>

		<?php if(!empty($errmsg)) echo "<div class='alert alert-danger'>$errmsg</div>"; ?>

		<?php if(!empty($successmsg)) echo "<div class='alert alert-danger'>$successmsg</div>"; ?>

		<form method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label>Product name:</label>
				<input type="text" name="product_name" class="form-control" required>
			</div>

			<div class="form-group">
				<label>Description:</label>
				<textarea name="description" class="form-control"></textarea>
			</div>

			<div class="form-group">
				<label>Product Image:</label>
				<input type="file" name="product_image" class="form-control" required>
			</div>

			<div class="form-group">
				<label>Unit Price:</label>
				<input type="number" name="unit_price" class="form-control" required>
			</div>

			<div class="form-group">
				<label>Selling Price:</label>
				<input type="number" name="selling_price" class="form-control" required>
			</div>

			<div class="form-group">
				<label>Stock Amount:</label>
				<input type="number" name="product_stock" class="form-control" required>
			</div>

			<div class="form-group">
				<label>Category:</label>
				<select name="category_id" id="categorySelect" class="form-control" required>
					<option value="">Select Category</option>
					<?php
						foreach ($categories as $cat): ?>
							<option value="<?=  $cat['id']; ?>"><?= htmlspecialchars($cat['category_name']); ?></option>
						<?php endforeach; ?>
				</select>
			</div>

			<div id="bookTypeSection" class="form-group" style="display: none;">
				<label>Book Type:</label>
				<div class="form-check form-check-inline">
					<input type="radio" name="book_type" class="form-check-input" value="paper" id="paper">
					<label class="form-check-label">Paper Binding</label>
				</div>

				<div class="form-check form-check-inline">
					<input type="radio" name="book_type" class="form-check-input" value="downloadable" id="downloadable">
					<label class="form-check-label">Downloadable</label>
				</div>
				<div id="pdfUploadSection" class="form-group" style="display: none;">
					<label>Upload PDF Book</label>
					<input type="file" name="virtual_pdf" accept=".pdf" class="form-control-file">
				</div>
			</div>
			<div class="form-check mb-3">
				<input type="checkbox" name="has_attributes" class="form-check-input" id="hasAttributes" onchange="toggleAttributes()">

				<label class="form-check-label">Has Attributes ?</label>
			</div>

			<div id ="attributeSection" style="display: none;">
				<div class="form-group">
					<label>Sizes:</label>
					<label class="checkbox-inline mr-2">
						<input type="checkbox" name="sizes[]" value="L">L
					</label>

					<label class="checkbox-inline mr-2">
						<input type="checkbox" name="sizes[]" value="XL">XL
					</label>

					<label class="checkbox-inline mr-2">
						<input type="checkbox" name="sizes[]" value="XXL">XXL
					</label>
				</div>

				<div class="form-group">
					<label>Colors:</label>
					<input type="color" name="" class="color-input">
					<button type="button" class="btn btn-sm btn-secondary" onclick="addColor()">Add Color</button>
					<div id="colorList" class="mt-2"></div>
					<input type="hidden" name="colors" id="colors">
				</div>
			</div>

			<button type="submit" name="btnsave" class="btn btn-success">Save</button>
		</form>
	</div>
</body>
</html>

<script type="text/javascript">
	let selectedColors = [];

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

	//Book Type Selection

	document.addEventListener('DOMContentLoaded', function(){

		const categorySelect = document.getElementById('categorySelect');

		const bookTypeSection = document.getElementById('bookTypeSection');
		const pdfSection = document.getElementById('pdfUploadSection');

		categorySelect.addEventListener('change', function(){

			const selectedText = categorySelect.options[categorySelect.selectedIndex].text.toLowerCase();
			if(selectedText == 'books')
			{
				bookTypeSection.style.display = 'block';
			}
			
			else
			{
				bookTypeSection.style.display = 'none';
				pdfSection.style.disply = 'none';
				document.querySelectorAll('input[name="book_type"]').forEach(r =>r.checked = false);
			}
		});

		document.querySelectorAll('input[name="book_type"]').forEach(rb => {

				rb.addEventListener('change', function(){

					pdfSection.style.display = (this.value === 'downloadable') ? 'block' : 'none';
				});
		});
	});
</script>