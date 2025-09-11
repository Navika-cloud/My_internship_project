<?php
include "config.php";
$id = $_GET["id"];

$conn->query("DELETE FROM posts1 WHERE id=$id");
header("Location: index.php");
exit;
