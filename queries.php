<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Connect database
include('connectdb.php');

// Queries
$query1 = "
SELECT m.MovieTitle, COUNT(DISTINCT r.CustomerID) AS TotalRatings
FROM Movie m
JOIN Ratings r ON m.MovieID = r.MovieID
GROUP BY m.MovieTitle
HAVING COUNT(DISTINCT r.CustomerID) > 1";


$query2 = "
SELECT 
    MIN(p.PurchasePrice) AS MINPRICE, 
    MAX(p.PurchasePrice) AS MAXPRICE, 
    ROUND(AVG(p.PurchasePrice), 2) AS AVGPRICE,
    ROUND(VARIANCE(p.PurchasePrice), 2) AS VARPRICE, 
    ROUND(STDDEV(p.PurchasePrice), 2) AS STDPRICE
FROM Purchases p";

$query3 = "
SELECT 
    F.AddedDate AS ActionDate,
    F.MovieID, 
    M.MovieTitle,
    'FAVOURITES' AS Action
FROM FAVOURITES F
LEFT JOIN MOVIE M ON F.MovieID = M.MovieID
UNION ALL
SELECT 
    O.OrderTime AS ActionDate,
    O.MovieID, 
    M.MovieTitle,
    'ORDERS' AS Action
FROM ORDERS O
LEFT JOIN MOVIE M ON O.MovieID = M.MovieID
ORDER BY ActionDate";

$query4 = "
SELECT 
    F.CustomerID, 
    C.CustomerName,
    F.AddedDate AS ActionDate
FROM FAVOURITES F
LEFT JOIN CUSTOMER C ON F.CustomerID = C.CustomerID
WHERE TO_CHAR(F.AddedDate, 'MON-YYYY') = 'JUN-2024'
UNION 
SELECT
    O.CustomerID,
    C.CustomerName,
    O.OrderTime AS ActionDate
FROM ORDERS O
LEFT JOIN CUSTOMER C ON O.CustomerID = C.CustomerID
WHERE TO_CHAR(O.OrderTime, 'MON-YYYY') = 'JUN-2024'";

// Execute and display results for each query
$queries = [$query1, $query2, $query3, $query4];
foreach ($queries as $index => $sql) {
    $stid = oci_parse($conn, $sql);
    $result = oci_execute($stid);

    if ($result) {
        echo "<h3>Results for Query " . ($index + 1) . ":</h3>";
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
        echo "Error executing query " . ($index + 1) . ": " . $error['message'] . "<br>";
    }
}

// Close the connection
oci_close($conn);
?>
