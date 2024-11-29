<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Connect database
include('connectdb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Retrieve inputs from the form
    $searchTable = strtoupper(trim($_POST['search_table'] ?? ''));
    $searchAttribute = strtoupper(trim($_POST['search_attribute'] ?? ''));
    $searchValue = trim($_POST['search_value'] ?? '');
    $updateAttribute = strtoupper(trim($_POST['update_attribute'] ?? ''));
    $updateValue = trim($_POST['update_value'] ?? '');

    // Validate inputs
    if (empty($searchTable) || empty($searchAttribute) || empty($searchValue) || empty($updateAttribute) || empty($updateValue)) {
        echo "<p>Please provide all required inputs (table, search attribute, search value, update attribute, and update value).</p>";
        exit;
    }

    // Construct the SQL query with placeholders
    $query = "UPDATE $searchTable SET $updateAttribute = :update_value WHERE $searchAttribute = :search_value";

    // Prepare the statement
    $stid = oci_parse($conn, $query);

    if (!$stid) {
        $error = oci_error($conn);
        echo "<p>Error preparing query: " . htmlspecialchars($error['message']) . "</p>";
        exit;
    }

    // Bind parameters to prevent SQL injection
    oci_bind_by_name($stid, ':update_value', $updateValue);
    oci_bind_by_name($stid, ':search_value', $searchValue);

    // Execute the query
    if (oci_execute($stid, OCI_NO_AUTO_COMMIT)) {
        oci_commit($conn);
        echo "<p>Update successful: '$updateAttribute' in table '$searchTable' updated to '$updateValue' where '$searchAttribute' = '$searchValue'.</p>";
    } else {
        $error = oci_error($stid);
        echo "<p>Error executing query: " . htmlspecialchars($error['message']) . "</p>";
    }

    // Free the statement
    oci_free_statement($stid);
}

echo "<p>Updated data:\n</p>";
include('search.php');
?>
