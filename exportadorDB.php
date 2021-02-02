<?php
$db_host="localhost";
$db_name="db_grandt";
$db_user="root";
$db_pass="";
include 'simpleXLSX.php';
$xlsx = new SimpleXLSX( 'planteles.xlsx' );
try {
   $conn = new PDO( "mysql:host=$db_host;dbname=$db_name", "$db_user", "$db_pass");
   $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$stmt = $conn->prepare( "INSERT INTO players (name, club, position, value) VALUES (?, ?, ?, ?)");
$stmt->bindParam( 1, $name);
$stmt->bindParam( 2, $club);
$stmt->bindParam( 3, $position);
$stmt->bindParam( 4, $value);

foreach ($xlsx->rows() as $fields) {
    $name = $fields[0];
    $club = $fields[1];
    $position = $fields[2];
    $value = $fields[3];
    $stmt->execute();
}


function getID ()

?>