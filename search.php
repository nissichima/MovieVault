<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Database connection
$conn = oci_connect(
    'n75nguye',  // username
    '07312181',  // password
    '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(Host=oracle.scs.ryerson.ca)(Port=1521))(CONNECT_DATA=(SID=orcl)))'  // connection string
);

if (!$conn) {
    $m = oci_error();
    echo "Connection failed: " . $m['message'];
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user inputs
    $table = strtoupper(trim($_POST['search_table'] ?? ''));
    $attribute = strtoupper(trim($_POST['search_attribute'] ?? ''));
    $value = trim($_POST['search_value'] ?? '');

	// Validate input
	if (empty($table)) {
        echo "<p>Please provide valid inputs.</p>";
        exit;
	}

	// Construct the SQL query
    // If search value is provided, filter by the search value; otherwise, fetch all rows
    if (empty($value) && empty($attribute)) {
        // If no search value is provided, fetch all rows from the table
        $query = "SELECT * FROM $table";
    } else {
        // If a search value is provided, filter rows based on the search attribute and value
        $query = "SELECT * FROM $table WHERE $attribute = :value";
    }

    // Prepare and execute the statement
    $stid = oci_parse($conn, $query);

    if (!$stid) {
        $error = oci_error($conn);
        echo "<p>Error preparing query: " . htmlspecialchars($error['message']) . "</p>";
        exit;
    }

	// Bind the value to the placeholder only if the search value is provided
    if (!empty($value)) {
        oci_bind_by_name($stid, ':value', $value);
    }

    // Execute the query
    if (oci_execute($stid)) {
        echo "<h2>Search Results from Table: $table</h2>";
        echo "<table>";
        echo "<tr>";

        // Fetch column names
        $ncols = oci_num_fields($stid);
        for ($i = 1; $i <= $ncols; $i++) {
            $colName = oci_field_name($stid, $i);
            echo "<th>$colName</th>";
        }
        echo "</tr>";

        // Fetch rows
        $rowCount = 0;
        while ($row = oci_fetch_assoc($stid)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
            $rowCount++;
        }
        echo "</table>";

        if ($rowCount === 0) {
            echo "<p>No records found matching your criteria.</p>";
        }
    } else {
        $error = oci_error($stid);
        echo "<p>Error executing query: " . htmlspecialchars($error['message']) . "</p>";
    }

    // Free the statement
    oci_free_statement($stid);
}


// Close the connection
oci_close($conn);

?>
