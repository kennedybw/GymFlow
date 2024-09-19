<?php
    require "plugin.php";
    if (isset($_POST['submit'])) {
        $response = registerUser($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm-password']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link type="text/css" rel="stylesheet" href="gymflow.css" />
    <title>Register - GymFlow</title>
</head>

<body>

<div class="navbar">
    <a href="index.php">Homepage</a>
    <div class="dropdown">
        <button class="dropbtn">Services
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="workoutcustomizer.php">Workout Customizer</a>
            <a href="caloriccounter.php">Workout Caloric Counter</a>
        </div>
    </div>
    <a href="schedule.php">Schedule</a>
    <a href="about.php">About Us</a>
    <a href="account.php">My Account</a>
</div>

<img src="GymFlow_Logo.png" alt="Gym Flow logo" width="600px" height="400px">
<br>

<div class="login">
    <h1>Register</h1>
    <p class="error"><?php echo @$response; ?></p>
    <form action="" method="post">
        <label for="email">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="email" placeholder="Email" id="email" required>

        <label for="username">
            <i class="fas fa-lock"></i>
        </label>
        <input type="text" name="username" placeholder="Username" id="username" required>

        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="text" name="password" placeholder="Password" id="password" required>

        <label for="confirm-password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="text" name="confirm-password" placeholder="Confirm Password" id="confirm-password" required>

        <input type="submit" name="submit" value="Register">
    </form>
</div>
</body>
</html>
