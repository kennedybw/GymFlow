<?php
/*
 * plugin.php
 * Stores commonly used values and functions so they can be easily
 * changed wherever necessary.
 *
 * This file is only viewable on the server side, and is additionally blocked
 * via .htaccess
 *
 */

//Starting a session to store user id locally.
session_start();
//Store server IP.
define('SERVER', 'localhost');
//Store database username.
define('USERNAME', 'gymflow');
//Store database password.
define('PASSWORD', 'GymTester12');
//Store database name.
define('DATABASE', 'gym');

/*
 * consoleLog
 * JavaScript based implementation used for debugging purposes.
 *
 */
function consoleLog($output, $forward = true)
{
    $logger = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($forward) {
        $logger = '<script>' . $logger . '</script>';
    }
    echo $logger;
}

/*
 * sqlConnect
 * Attempts to connect to a MySQL database using the provided
 * constants.
 *
 */
function sqlConnect()
{
    // New SQL connection.
    $mysqli = new \MySQLi(SERVER, USERNAME, PASSWORD, DATABASE);
    // If connection succeeds...
    if ($mysqli -> connect_errno == 0) {
        // Set charset and return $mysqli
        $mysqli -> set_charset("utf8mb4");
        return $mysqli;
    // Else...
    } else {
        // Provide an error log of the events.
        consoleLog("SQL connection failure. Error code " . $mysqli -> connect_errno
            . ". Please verify the server address, name, username, and password provided.");
        // Return false (ie: connection failure)
        return false;
    }
}

/*
 * userValidation
 * Compares given records to those in the database.
 *
 */
function userValidation($value, $mysqli, $id)
{
    // SQL to pull the asked for record.
    $stmt = $mysqli -> prepare("SELECT " . $id ." FROM users WHERE " . $id . " = ?");
    $stmt -> bind_param("s", $value);
    $stmt -> execute();
    $result = $stmt -> get_result();
    // Save associative array in $data
    $data = $result -> fetch_assoc();
    // Make sure the array isn't null
    if ($data != null) {
        return 0;
    }
    return 1;
}

/*
 * registerUser
 * Runs safety and logic checks on user signup information, then if valid adds
 * the user info to the database.
 *
 */
function registerUser($email, $username, $password, $confirmPassword)
{
    // Attempt to connect to the database.
    $mysqli = sqlConnect();

    // Trim input.
    $email = trim($email);
    $username = trim($username);
    $password = trim($password);
    $confirmPassword = trim($confirmPassword);

    // Verify input
    $args = func_get_args();
    foreach ($args as $value) {
        if (empty($value)) {
            return "Input cannot be left empty!";
        } elseif (preg_match("/([<|>])/", $value)) {
            return "<> characters are not allowed!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "The email provided is invalid!";
        }
    }

    // Check if email address or username already exist.
    if (userValidation($email, $mysqli, "email") == 0) {
        return "Email already exists!";
    } elseif (userValidation($username, $mysqli, "username") == 0) {
        return "Username already exists!";
    }

    // Check password length.
    if (strlen($password) > 32) {
        return "Password is too long!";
    } elseif ($password != $confirmPassword) {
        return "Passwords don't match!";
    }

    // Encrypt user password.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database.
    $stmt = $mysqli -> prepare("INSERT INTO users(username, password, email) VALUES(?,?,?)");
    $stmt -> bind_param("sss", $username, $hashedPassword, $email);
    $stmt -> execute();
    if ($stmt -> affected_rows != 1) {
        return "An error occurred. Please try again :c";
    } else {
        // Redirect to homepage
        header("location: index.php");
        exit();
    }
}

/*
 * loginUser
 * Verifies given information and signs in the user if it is correct.
 *
 */
function loginUser($username, $password)
{
    // Attempt to connect to the database.
    $mysqli = sqlConnect();

    // Trim input.
    $username = trim($username);
    $password = trim($password);

    if ($username == "" || $password == "") {
        return "Both fields are required";
    }

    // SQL command for lookup
    $sql = "SELECT username, password FROM users WHERE username = ?";
    // Find record in database
    $stmt = $mysqli -> prepare($sql);
    $stmt -> bind_param("s", $username);
    $stmt -> execute();
    $result = $stmt -> get_result();
    // Store.
    $data = $result -> fetch_assoc();

    // Check if the given password matches the saved one.
    if ($data == null || !password_verify($password, $data["password"])) {
        return "Wrong username or password";
    } else {
        // Save username to sessionid
        $_SESSION["user"] = $username;
        // Redirect to account details page
        header("location: account.php");
        exit();
    }
}

/*
 * logoutUser
 * Removes sessionid to logout user.
 *
 */
function logoutUser() {
    // Delete client-side sessionid
    session_destroy();
    // Redirect to homepage
    header("location: index.php");
    exit();
}

/*
 * deleteAccount
 * Removes user information from database and deletes sessionid
 *
 */
function deleteAccount()
{
    // Connect to database
    $mysqli = sqlConnect();
    // Delete record storing user
    $sql = "DELETE FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();

    // If this record does not exist, record an error.
    if ($stmt->affected_rows != 1) {
        return "An error occurred. Please try again";
    } else {
        // Delete client-side sessionid
        session_destroy();
        // Redirect to homepage
        header("location: index.php");
        exit();
    }
}
