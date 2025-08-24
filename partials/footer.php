<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
	function loadProducts(catIds = [])
	{
		$.ajax({
			url: 'fetch_products.php',
			method: 'POST',
			data: { categories: catIds },
			success: function(html)
			{
				$('#productGrid').html(html);
			}
		});
	}

	$(function()
	{
		const checked = [];

		$('.cat-check:checked').each(function(){checked.push($(this).val()); });

		loadProducts(checked);

		$(document).on('change','.cat-check', function(){

				const ids = [];
				$('.cat-check:checked').each(function(){ids.push($(this).val()); });

			loadProducts(ids);	
		});

		$('#clearFilter').on('click', function(){

			$('.cat-check').prop('checked', false);

			loadProducts([]);
		});

		const qp = new URLSearchParams(window.location.search);
		if(qp.get('view') == 'all'){loadProducts([]);}
	});
</script>