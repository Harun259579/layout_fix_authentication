<?php
require_once 'partials/header.php';
require_once 'partials/navbar.php';
?>
<div class="container-fluid mt-4">
  <div class="row">
    <!--Sidebar-->
    <div class="col-md-3 col-lg-2">
      <?php require_once 'partials/sidebar.php'; ?>
    </div>
    <!--Main content-->
    <div class="col-md-9 col-lg-10">
      
      <!--Carousel-->

    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="assets/images/1.jpg" class="d-block w-100 slider-img" alt="slide1">
          <div class="carousel-caption d-none d-md-block">
            <h5>Welcome to My Shop</h5>
            <p>Best Products, Best Prices</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="assets/images/2.jpg" class="d-block w-100 slider-img" alt="...">

          <div class="carousel-caption d-none d-md-block">
            <h5>Fresh Arrivals</h5>
            <p>Check out the Latest!</p>
          </div>

        </div>
        <div class="carousel-item">
          <img src="assets/images/3.jpg" class="d-block w-100 slider-img" alt="...">

          <div class="carousel-caption d-none d-md-block">
            <h5>Hot Deals!</h5>
            <p>Don't miss out Today</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </button>
        </div>

        <!--Products-->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="mb-0">Products</h4>
          <small class="text-muted">Filter by categories from left</small>
        </div>

        <div id="productGrid" class="row"></div>
      </div>
    </div>
  </div>

<?php require_once 'partials/footer.php'; ?>