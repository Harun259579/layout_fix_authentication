<?php
if(session_status() === PHP_SESSION_NONE) session_start();

$BASE = defined('BASE_URL') ? BASE_URL : ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://'.($_SERVER['HTTP_HOST'] ?? 'localhost').'/'. 'ecommerce');

$isLoggedIn = !empty($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'Account';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="<?= $BASE ?>">My Store</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="<?= $BASE ?>">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= $BASE ?>/index.php?view=all">All Products</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="index.php?view=all">About Us</a>
      </li>

       <li class="nav-item">
        <a class="nav-link" href="index.php?view=all">Contact Us</a>
      </li>          
    </ul>
    <ul class="navbar-nav">
      <li class="nav-item">
        <button class="btn btn-outline-light mr-2" data-toggle="modal" data-target=" ">Login</button>
      </li>

      <li class="nav-item">
        <button class="btn btn-primary mr-2" data-toggle="modal" data-target=" ">Register</button>
      </li>
    </ul>
  </div>
</nav>