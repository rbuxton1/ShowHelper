<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'shUser'); //shUser
    define('DB_PASSWORD', 'Sh0wH3lper!'); //Sh0wH3lper!
    define('DB_DATABASE', 'sh');
    $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    
    $ver = "1.0.0";
?>