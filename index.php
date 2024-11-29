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
			<h2>Tables</h2>
            <button type="submit" name="action" value="drop">Drop Tables</button>
            <button type="submit" name="action" value="create">Create Tables</button>
            <button type="submit" name="action" value="populate">Populate Tables</button>
			<button type="submit" name="action" value="queries">Query Tables</button>
			<div>
				<!--- <input type="text" name="custom_query" placeholder="Enter Custom Query"> --->
				<textarea name="custom_query" placeholder="Enter Custom Query" rows="5" cols="45"></textarea>
				<br>
				<button type="submit" name="action" value="run_query">Run Query</button>
			</div>
            <div>
				<h2>Records</h2>
				<input type="text" name="search_table" placeholder="table">
				<br>
				<input type="text" name="search_attribute" placeholder="search attribute">
				<input type="text" name="search_value" placeholder="search value">
				<br>
				<input type="text" name="update_attribute" placeholder="update attribute">
				<input type="text" name="update_value" placeholder="update value">
            </div>
            	<button type="submit" name="action" value="search">Search</button>
				<button type="submit" name="action" value="update">Update</button>
				<button type="submit" name="action" value="delete">Delete</button>

        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        // Connect database
		include('connectdb.php');

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
        } elseif ($action === 'queries') {
            include('queries.php');
            echo "<p>Tables queried successfully.</p>";
		} elseif ($action === 'search') {
			include('search.php');
		} elseif ($action === 'update') {
			include('update.php');
		} elseif ($action === 'delete') {
			include('delete.php');
		} elseif ($action === 'run_query') {
			include('run_query.php');
		}

        
    }

    function displayTables($conn) {
    $tableNames = ['MOVIE', 'MOVIEINFO', 'CUSTOMER', 'CUSTOMER_USERNAME', 'CUSTOMER_EMAIL', 'FAVOURITES', 'ORDERS', 'RENTALS', 'PURCHASES', 'RATINGS'];
	
	// Connect database
	include('connectdb.php');
	
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
