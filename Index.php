<?php
// Start the session
session_start();

// Check if the user is logged in
// Hello i am shahin
//Good
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tasks from the database
$user_id = $_SESSION['user_id']; // Assuming you store user_id in session upon login
$sql = "SELECT id, title, description, due_date, status FROM tasks WHERE user_id = $user_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Task Manager - Home</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Make sure this path is correct -->
</head>

<body>

    <div class="container">
        <h1>Welcome to Your Task Manager</h1>

        <!-- Adding Navigation -->
        <nav>
            <ul>
                <li><a href="Index.php">Home</a></li>
                <li><a href="add new tasks.php">Add New Task</a></li>
                <!-- Add other navigation links here -->
                <li><a href="Classification.php">View Tasks</a></li>
                <li><a href="Settings.php">Settings</a></li>
                <li><a href="Login.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Existing Task List Section -->
        <section class="task-list">
            <h2>Your Tasks</h2>
            <?php
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='task'>";
                    echo "<h3>" . $row["title"] . "</h3>";
                    echo "<p>" . $row["description"] . "</p>";
                    echo "<p>Due: " . $row["due_date"] . "</p>";
                    echo "<p>Status: " . $row["status"] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No tasks found</p>";
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
    /* Reset margin and padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        color: #333;
        line-height: 1.6;
    }

    .container {
        width: 80%;
        margin: auto;
        overflow: hidden;
    }

    h1 {
        text-align: center;
        margin: 20px 0;
    }

    nav ul {
        list-style: none;
        background: #333;
        text-align: center;
        padding: 10px 0;
    }

    nav ul li {
        display: inline;
        margin: 0 10px;
    }

    nav ul li a {
        text-decoration: none;
        color: white;
        font-size: 18px;
        padding: 5px 10px;
    }

    nav ul li a:hover {
        background: #575757;
        border-radius: 5px;
    }

    .task-list {
        background: #e2e2e2;
        padding: 20px;
        margin-top: 20px;
        border-radius: 5px;
    }

    /* Add more styles as per your application's functionality and aesthetic requirements */
</style>
