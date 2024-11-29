<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Connect database
include('connectdb.php');

// SQL to create tables 
$sql1 = "
CREATE TABLE MOVIE (
    MovieID NUMBER NOT NULL PRIMARY KEY,  
    ReleaseDate DATE DEFAULT TO_DATE('1900-01-01', 'YYYY-MM-DD'),  
    MovieTitle VARCHAR2(20) DEFAULT 'Test Name' NOT NULL,
    Genre VARCHAR2(50) DEFAULT 'Romance' NOT NULL
)";
$sql2 = "
CREATE TABLE MOVIEINFO (
    MovieID NUMBER NOT NULL PRIMARY KEY,                    
    Descriptions VARCHAR2(255),                     
    Subtitles VARCHAR2(100),                       
    Directors VARCHAR2(100),                   
    Languages VARCHAR2(100),                
    Producers VARCHAR2(100),                      
    Cast VARCHAR2(255),                         
    LengthOfMovie NUMBER,                         
    AverageRating NUMBER CHECK (AverageRating >= 0 AND AverageRating <= 5),  
    AgeRating VARCHAR2(10),      
    FOREIGN KEY (MOVIEID) REFERENCES MOVIE(MOVIEID) ON DELETE CASCADE                   
)";
$sql3 = "
CREATE TABLE CUSTOMER (
    CustomerID VARCHAR2(10) PRIMARY KEY,
    OpenAccountDate DATE NOT NULL,
    CustomerName VARCHAR2(50) NOT NULL
)";
$sql4 = "
CREATE TABLE CUSTOMER_USERNAME (
    CustomerID VARCHAR2(10) REFERENCES CUSTOMER(CustomerID) ON DELETE CASCADE,
    Username VARCHAR2(50) UNIQUE NOT NULL
)";
$sql5 = "
CREATE TABLE CUSTOMER_EMAIL (
    CustomerID VARCHAR2(10) REFERENCES CUSTOMER(CustomerID) ON DELETE CASCADE,
    Email VARCHAR2(100) UNIQUE NOT NULL
)";
$sql6 = "
CREATE TABLE FAVOURITES (
    CUSTOMERID VARCHAR2(10) REFERENCES CUSTOMER(CustomerID) ON DELETE CASCADE,                
    MOVIEID NUMBER REFERENCES MOVIE(MovieID),                         
    AddedDate DATE NOT NULL                
)";
$sql7 = "
CREATE TABLE ORDERS (
    OrderID NUMBER PRIMARY KEY,
    OrderTime DATE NOT NULL,
    CustomerID VARCHAR2(10) REFERENCES CUSTOMER(CustomerID) ON DELETE CASCADE,
    MovieID NUMBER REFERENCES MOVIE(MovieID)
)";
$sql8 = "
CREATE TABLE RENTALS (
    OrderID NUMBER PRIMARY KEY REFERENCES ORDERS(OrderID) ON DELETE CASCADE,
    RentalPeriod VARCHAR2(15) NOT NULL,
    RentalPrice NUMBER DEFAULT 5.99
)";
$sql9 = "
CREATE TABLE PURCHASES (
    OrderID NUMBER PRIMARY KEY REFERENCES ORDERS(OrderID) ON DELETE CASCADE,
    PurchasePrice NUMBER DEFAULT 10.99
)";
$sql10 = "
CREATE TABLE RATINGS (
    CustomerID VARCHAR2(10) REFERENCES CUSTOMER(CustomerID) ON DELETE CASCADE,
    MovieID NUMBER REFERENCES MOVIE(MovieID) ON DELETE CASCADE,
    Rating NUMBER CHECK (Rating >= 0 AND Rating <= 5),
    PRIMARY KEY (CustomerID, MovieID)
)";

// Execute SQL one by one
$queries = [$sql1, $sql2, $sql3, $sql4, $sql5, $sql6, $sql7, $sql8, $sql9, $sql10];

foreach ($queries as $sql) {
    $stid = oci_parse($conn, $sql);
    $result = oci_execute($stid);

    if ($result) {

    } else {
        $error = oci_error($stid);
        echo "Error: " . $error['message'] . "<br>";
        header('Location: index.php');
    }
}


oci_close($conn);
?>
