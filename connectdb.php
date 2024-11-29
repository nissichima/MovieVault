<?php
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
?>
