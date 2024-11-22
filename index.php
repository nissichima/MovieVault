<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Operations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            text-align: center;
            margin-bottom: 20px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f9;
        }
        h2 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Movie Vault</h1>
        <form method="POST">
            <button type="submit" name="action" value="drop">Drop Tables</button>
            <button type="submit" name="action" value="create">Create Tables</button>
            <button type="submit" name="action" value="populate">Populate Tables</button>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        // Database connection
        $conn = oci_connect(
            'w64li',
            '05136747',
            '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(Host=oracle.scs.ryerson.ca)(Port=1521))(CONNECT_DATA=(SID=orcl)))'
        );

        if (!$conn) {
            echo "<p>Database connection failed.</p>";
            exit;
        }

        if ($action === 'drop') {
            include('drop_tables.php');
        } elseif ($action === 'create') {
            include('create_tables.php');
            echo "<p>Tables created successfully.</p>";
            displayTables($conn);
        } elseif ($action === 'populate') {
            include('populate_tables.php');
            echo "<p>Tables populated successfully.</p>";
            displayTables($conn);
        }

        
    }

    function displayTables($conn) {
    $tableNames = ['MOVIE', 'MOVIEINFO', 'CUSTOMER', 'CUSTOMER_USERNAME', 'CUSTOMER_EMAIL', 'FAVOURITES', 'ORDERS', 'RENTALS', 'PURCHASES', 'RATINGS'];
	
	$conn = oci_connect(
            'w64li',
            '05136747',
            '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(Host=oracle.scs.ryerson.ca)(Port=1521))(CONNECT_DATA=(SID=orcl)))'
        );

        if (!$conn) {
            echo "<p>Database connection failed.</p>";
            exit;
        }
        
    foreach ($tableNames as $tableName) {
        echo "<h2>Table: $tableName</h2>";
        $query = "SELECT * FROM $tableName";
        $stid = oci_parse($conn, $query);

        if (!$stid) {
            $error = oci_error($conn);
            echo "<p>Error preparing query for $tableName: " . htmlspecialchars($error['message']) . "</p>";
            continue;
        }

        if (oci_execute($stid)) {
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
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            $error = oci_error($stid);
            echo "<p>Error fetching data from $tableName: " . htmlspecialchars($error['message']) . "</p>";
        }
    }
}
    ?>
</body>
</html>
