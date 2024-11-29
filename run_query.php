<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Connect database
include('connectdb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!empty($_POST['custom_query'])) {
        $sql = trim($_POST['custom_query']); 
        if (substr($sql, -1) === ';') {
            $sql = rtrim($sql, ';');
        }
	
    $stid = oci_parse($conn, $sql);
    $result = oci_execute($stid);

    if ($result) {
        echo "<h3>Results for Query:</h3>";
        echo "<table border='1'>";
        $ncols = oci_num_fields($stid);
        
        // Print table headers
        echo "<tr>";
        for ($i = 1; $i <= $ncols; $i++) {
            $colname = oci_field_name($stid, $i);
            echo "<th>" . htmlspecialchars($colname, ENT_QUOTES | ENT_HTML5) . "</th>";
        }
        echo "</tr>";

        // Print table rows
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            echo "<tr>";
            foreach ($row as $item) {
                echo "<td>" . ($item !== null ? htmlspecialchars($item, ENT_QUOTES | ENT_HTML5) : "&nbsp;") . "</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        $error = oci_error($stid);
        echo "Error executing query: " . $error['message'] . "<br>";
    }
}
}

// Close the connection
if ($conn) {
    oci_close($conn);
}
?>
