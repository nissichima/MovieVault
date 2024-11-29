<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Connect database
include('connectdb.php');

// Array of tables to drop
$tables = [
    'RATINGS',
    'PURCHASES',
    'RENTALS',
    'ORDERS',
    'FAVOURITES',
    'CUSTOMER_USERNAME',
    'CUSTOMER_EMAIL',
    'CUSTOMER',
    'MOVIEINFO',
    'MOVIE'
];


foreach ($tables as $table) {
    $dropSql = "DROP TABLE $table CASCADE CONSTRAINTS";
    $stid = oci_parse($conn, $dropSql);
    $result = oci_execute($stid);
    if ($result) {
        echo "Table $table dropped successfully!<br>";
    } else {
        $error = oci_error($stid);
        echo "Error dropping $table: " . $error['message'] . "<br>";
        header('Location: index.php');
    }
    oci_free_statement($stid);
}

oci_close($conn);
?>
