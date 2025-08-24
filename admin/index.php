<?php
session_start();

if(!isset($_SESSION['admin_logged_in']))
{
	header("Location: login.php");
	exit;
}
include "includes/header.php";
include "includes/sidebar.php";

$page = $_GET['page'] ?? 'dashboard';
$page_path = "pages/".basename($page) . ".php";

echo '<div class = "content">';

if(file_exists($page_path))
{
	include $page_path;
}

else
{
	echo "<div class='alert alert-warning'>404-Page Not Found!</div>";
}

echo '</div>';

include "includes/footer.php";

?>