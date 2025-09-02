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
        main {
            margin: 30px;
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
        <p>✅ Congratulations! Your PHP environment is working correctly.</p>
        <p>
            <?php
                echo "Current Server Date & Time: " . date("Y-m-d H:i:s");
            ?>
        </p>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> ApexPlanet Software Pvt Ltd | Internship Program
    </footer>

</body>
</html>