<?php
// Start the session
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
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
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                $email = trim($_POST["email"]);
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before updating the database
    if (empty($email_err) && empty($new_password_err) && empty($confirm_password_err)) {

        // Prepare an update statement
        $sql = "UPDATE users SET email = ?, password = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_email, $param_password, $param_id);

            // Set parameters
            $param_email = $email;
            $param_password = password_hash($new_password, PASSWORD_DEFAULT); // Creates a password hash
            $param_id = $_SESSION['user_id'];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>

<body>
    <div class="container">
        <h1>Account Settings</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>
</body>

</html>

<style>
    /* style.css for Settings Page */

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f2f2f2;
        color: #333;
        margin: 0;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
    }

    h1 {
        color: #007bff;
        text-align: center;
        margin-bottom: 30px;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    /* Input and Textarea Styles */
    input[type="email"],
    input[type="password"],
    input[type="text"],
    textarea {
        width: calc(100% - 22px);
        /* Adjust width calculation as necessary */
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 1rem;
        /* Adjust the font size as necessary */
        line-height: 1.5;
        /* Standard line height for inputs */
        display: block;
        /* Ensures the input fields don't inline with other elements */
        box-sizing: border-box;
        /* Includes padding and border in the element's total width and height */
    }

    /* Submit Button Style */
    input[type="submit"] {
        width: auto;
        /* Adjust width to content, use 100% for full width */
        padding: 10px 20px;
        /* Adjust padding to increase/decrease size */
        font-size: 1rem;
        /* Keep font size consistent with other inputs */
        /* Rest of your button styles */
    }


    input[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        text-transform: uppercase;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover,
    input[type="submit"]:focus {
        background-color: #0056b3;
    }

    .help-block {
        color: #ff6666;
        font-size: 0.8em;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            width: 90%;
            padding: 15px;
        }
    }
</style>