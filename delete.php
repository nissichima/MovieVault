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
    // Retrieve inputs from the form
    $searchTable = strtoupper(trim($_POST['search_table'] ?? ''));
    $searchAttribute = strtoupper(trim($_POST['search_attribute'] ?? ''));
    $searchValue = trim($_POST['search_value'] ?? '');

    // Validate inputs
    if (empty($searchTable) || empty($searchAttribute) || empty($searchValue)) {
        echo "<p>Please provide all required inputs (table, search attribute, and search value).</p>";
        exit;
    }

    // Construct the SQL DELETE query
    $query = "DELETE FROM $searchTable WHERE $searchAttribute = :search_value";

    // Prepare the statement
    $stid = oci_parse($conn, $query);

    if (!$stid) {
        $error = oci_error($conn);
        echo "<p>Error preparing query: " . htmlspecialchars($error['message']) . "</p>";
        exit;
    }

    // Bind parameter to prevent SQL injection
    oci_bind_by_name($stid, ':search_value', $searchValue);

    // Execute the query
    if (oci_execute($stid, OCI_NO_AUTO_COMMIT)) {
        oci_commit($conn);
        echo "<p>Row deleted successfully from table '$searchTable' where '$searchAttribute' = '$searchValue'.</p>";
    } else {
        $error = oci_error($stid);
        echo "<p>Error executing delete query: " . htmlspecialchars($error['message']) . "</p>";
    }

    // Free the statement
    oci_free_statement($stid);
}

oci_close($conn);

?>
