<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexPlanet Internship - Task 1</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f9f9;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        header {
            background: #00796B;
            color: white;
            padding: 20px;
        }
        header h1 {
            margin: 0;
        }
        main {
            margin: 30px auto;
            max-width: 700px;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            padding: 25px;
            margin: 20px 0;
        }
        .status {
            font-size: 1.1em;
            margin: 10px 0;
        }
        .success {
            color: #2e7d32;
            font-weight: bold;
        }
        .error {
            color: #c62828;
            font-weight: bold;
        }
        footer {
            margin-top: 40px;
            padding: 15px;
            background: #004D40;
            color: white;
        }
    </style>
</head>
<body>

    <header>
        <h1>ApexPlanet Internship</h1>
        <h2>Task 1: Setting Up the Development Environment</h2>
    </header>

    <main>
        <div class="card">
            <p class="status success">✅ Congratulations! Your PHP environment is working correctly.</p>
            <p>Current Server Date & Time: 
                <strong>
                    <?php echo date("Y-m-d H:i:s"); ?>
                </strong>
            </p>
        </div>

        <div class="card">
            <?php
                // Database connection (default XAMPP: user=root, no password)
                $mysqli = @new mysqli("localhost", "root", "", "test_db");

                if ($mysqli->connect_errno) {
                    echo "<p class='status error'>❌ Database connection failed: " . $mysqli->connect_error . "</p>";
                } else {
                    echo "<p class='status success'>✅ Connected to MySQL Database (test_db)</p>";
                    
                    // Fetch server time from MySQL
                    $result = $mysqli->query("SELECT NOW() AS db_time");
                    $row = $result ? $result->fetch_assoc() : null;
                    echo "<p>Database Server Time: <strong>" . ($row['db_time'] ?? 'N/A') . "</strong></p>";
                }
            ?>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> ApexPlanet Software Pvt Ltd | Internship Program
    </footer>

</body>
</html>
