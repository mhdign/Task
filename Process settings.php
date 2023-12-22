<?php
@session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection file
require_once 'Process settings.php'; // Adjust with your database connection file

// Define variables and initialize with empty values
$email = $new_password = $confirm_password = "";
$email_err = $new_password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate new email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter a new email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate new password if it has been entered
    if (!empty(trim($_POST["new_password"]))) {
        if (strlen(trim($_POST["new_password"])) < 6) {
            $new_password_err = "Password must have at least 6 characters.";
        } else {
            $new_password = trim($_POST["new_password"]);
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm the password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if ($new_password != $confirm_password) {
                $confirm_password_err = "Password did not match.";
            }
        }
    }

    // Check input errors before updating the database
    if (empty($email_err) && empty($new_password_err) && empty($confirm_password_err)) {

        // Initialize an array to hold update statements
        $updates = [];
        $params = [];
        $types = '';

        // Check if email needs to be updated
        if (!empty($email)) {
            $updates[] = "email = ?";
            $params[] = &$email;
            $types .= 's';
        }

        // Check if password needs to be updated
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $updates[] = "password = ?";
            $params[] = &$hashed_password;
            $types .= 's';
        }

        // Construct the SQL query with dynamic updates
        if (count($updates) > 0) {
            $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
            $params[] = &$_SESSION['user_id'];
            $types .= 'i';

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to login page with success message
                    header("location: settings.php?status=success");
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Close connection
    mysqli_close($link);
}

// Here, add any additional processing or feedback as necessary
