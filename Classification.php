<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "task"; // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to get classifications (assuming a 'priority' classification for simplicity)
$sql = "SELECT DISTINCT priority FROM tasks ORDER BY priority DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Task Classifications</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Link your CSS file -->
</head>

<body>

    <div class="container">
        <h1>Task Classifications</h1>

        <!-- Navigation and other HTML here (reuse from previous sections as needed) -->

        <section class="classification-list">
            <h2>Task Priorities</h2>
            <?php
            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>" . $row["priority"] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No classifications found</p>";
            }
            ?>
        </section>
    </div>

</body>

</html>

<?php
// Close connection
$conn->close();
?>

<style>
    /* style.css */

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #212529;
        line-height: 1.5;
        margin: 0;
        padding: 20px;
    }

    .container {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 30px auto;
    }

    h1 {
        color: #007bff;
        text-align: center;
        margin-bottom: 30px;
    }

    .classification-list {
        margin-top: 20px;
    }

    .classification-list ul {
        list-style-type: none;
        /* Removes default list style */
        padding: 0;
    }

    .classification-list li {
        background: #e9ecef;
        margin-bottom: 8px;
        padding: 10px;
        border-radius: 3px;
        font-size: 1.1em;
        letter-spacing: 0.5px;
    }

    /* Responsive design adjustments */
    @media (max-width: 768px) {
        .container {
            width: 90%;
            padding: 15px;
        }
    }
</style>