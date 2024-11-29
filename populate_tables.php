<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Connect database
include('connectdb.php');

// Define each SQL statement separately
$queries = [
    "INSERT INTO CUSTOMER (CUSTOMERID, OPENACCOUNTDATE, CUSTOMERNAME) 
     VALUES (1, TO_DATE('2022-11-23', 'YYYY-MM-DD'), 'John Doe')",

    "INSERT INTO CUSTOMER_USERNAME (CUSTOMERID, USERNAME) 
     VALUES (1, 'johndoe')",

    "INSERT INTO CUSTOMER_EMAIL (CUSTOMERID, EMAIL) 
     VALUES (1, 'johndoe@example.com')",

    "INSERT INTO CUSTOMER (CUSTOMERID, OPENACCOUNTDATE, CUSTOMERNAME) 
     VALUES (2, TO_DATE('2020-01-05', 'YYYY-MM-DD'), 'Jan Smith')",

    "INSERT INTO CUSTOMER_USERNAME (CUSTOMERID, USERNAME) 
     VALUES (2, 'jansmith')",

    "INSERT INTO CUSTOMER_EMAIL (CUSTOMERID, EMAIL) 
     VALUES (2, 'jansmith@example.com')",

    "INSERT INTO CUSTOMER (CUSTOMERID, OPENACCOUNTDATE, CUSTOMERNAME) 
     VALUES (3, TO_DATE('2010-05-13', 'YYYY-MM-DD'), 'Yas Ko')",

    "INSERT INTO CUSTOMER_USERNAME (CUSTOMERID, USERNAME) 
     VALUES (3, 'yasko')",

    "INSERT INTO CUSTOMER_EMAIL (CUSTOMERID, EMAIL) 
     VALUES (3, 'yasko@example.com')",

    "INSERT INTO CUSTOMER (CUSTOMERID, OPENACCOUNTDATE, CUSTOMERNAME) 
     VALUES (4, TO_DATE('2024-07-15', 'YYYY-MM-DD'), 'Alex Lee')",

    "INSERT INTO CUSTOMER_USERNAME (CUSTOMERID, USERNAME) 
     VALUES (4, 'alexlee')",

    "INSERT INTO CUSTOMER_EMAIL (CUSTOMERID, EMAIL) 
     VALUES (4, 'alexlee@example.com')",

    "INSERT INTO Movie (MovieID, ReleaseDate, MovieTitle, Genre) 
     VALUES (1, TO_DATE('2023-01-01', 'YYYY-MM-DD'), 'The Great Adventure', 'Action')",

    "INSERT INTO Movie (MovieID, ReleaseDate, MovieTitle, Genre) 
     VALUES (2, TO_DATE('2024-08-16', 'YYYY-MM-DD'), 'The Last Laugh', 'Comedy')",

    "INSERT INTO Movie (MovieID, ReleaseDate, MovieTitle, Genre) 
     VALUES (3, TO_DATE('2024-09-01', 'YYYY-MM-DD'), 'The Notebook', 'Romance')",

    "INSERT INTO MovieInfo (MovieID, AverageRating) 
     VALUES (1, 3)",

    "INSERT INTO MovieInfo (MovieID, AverageRating) 
     VALUES (2, 2)",

    "INSERT INTO MovieInfo (MovieID, AverageRating) 
     VALUES (3, 1)",

    "INSERT INTO Ratings (CustomerID, MovieID, Rating) 
     VALUES (1, 1, 3)",

    "INSERT INTO Ratings (CustomerID, MovieID, Rating) 
     VALUES (2, 1, 3)",

    "INSERT INTO FAVOURITES (CUSTOMERID, MOVIEID, ADDEDDATE) 
     VALUES (1, 2, TO_DATE('2023-05-21', 'YYYY-MM-DD'))",

    "INSERT INTO FAVOURITES (CUSTOMERID, MOVIEID, ADDEDDATE) 
     VALUES (2, 3, TO_DATE('2024-06-21', 'YYYY-MM-DD'))",

    "INSERT INTO FAVOURITES (CUSTOMERID, MOVIEID, ADDEDDATE) 
     VALUES (2, 2, TO_DATE('2023-06-21', 'YYYY-MM-DD'))",

    "INSERT INTO ORDERS (ORDERID, ORDERTIME, CUSTOMERID, MOVIEID) 
     VALUES (1, TO_DATE('2024-01-07', 'YYYY-MM-DD'), 1, 2)",

    "INSERT INTO ORDERS (ORDERID, ORDERTIME, CUSTOMERID, MOVIEID) 
     VALUES (2, TO_DATE('2024-01-08', 'YYYY-MM-DD'), 1, 3)",

    "INSERT INTO ORDERS (ORDERID, ORDERTIME, CUSTOMERID, MOVIEID) 
     VALUES (3, TO_DATE('2024-02-22', 'YYYY-MM-DD'), 3, 1)",

    "INSERT INTO ORDERS (ORDERID, ORDERTIME, CUSTOMERID, MOVIEID) 
     VALUES (4, TO_DATE('2024-05-15', 'YYYY-MM-DD'), 2, 2)",

    "INSERT INTO ORDERS (ORDERID, ORDERTIME, CUSTOMERID, MOVIEID) 
     VALUES (5, TO_DATE('2024-06-24', 'YYYY-MM-DD'), 1, 3)",

    "INSERT INTO ORDERS (ORDERID, ORDERTIME, CUSTOMERID, MOVIEID) 
     VALUES (6, TO_DATE('2024-01-07', 'YYYY-MM-DD'), 1, 1)",

    "INSERT INTO PURCHASES (ORDERID, PurchasePrice) 
     VALUES (1, 5.99)",

    "INSERT INTO PURCHASES (ORDERID, PurchasePrice) 
     VALUES (2, 20.99)",

    "INSERT INTO PURCHASES (ORDERID, PurchasePrice) 
     VALUES (3, 40.99)",

    "INSERT INTO PURCHASES (ORDERID, PurchasePrice) 
     VALUES (4, 29.99)",

    "INSERT INTO RENTALS (ORDERID, RENTALPERIOD, RentalPrice) 
     VALUES (1, '6 Months', 6.99)",

    "INSERT INTO RENTALS (ORDERID, RENTALPERIOD, RentalPrice) 
     VALUES (2, '4 months', 8.99)"
];

// Execute each query separately
foreach ($queries as $query) {
    $stid = oci_parse($conn, $query);
    $result = oci_execute($stid);
    if (!$result) {
        $error = oci_error($stid);
        echo "Error inserting data: " . $error['message'] . "<br>";
        oci_free_statement($stid);
        oci_close($conn);
        exit;
    }
    oci_free_statement($stid);
}

// Commit the transaction
oci_commit($conn);

// Close the connection
oci_close($conn);

?>
