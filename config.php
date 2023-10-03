<?php
define("DB_SERVER", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "userlogs");

# Connection
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
date_default_timezone_set('Asia/Kolkata');
# Check connection
if (!$link) {
  die("Connection failed: " . mysqli_connect_error());
}