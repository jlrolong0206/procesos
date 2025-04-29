<?php

$conn = new mysqli('localhost', 'root', '', 'epes_db_n') or die("Could not connect to mysql" . mysqli_error($con));
$conn->set_charset("utf8");
