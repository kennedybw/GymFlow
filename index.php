<?php
  require "plugin.php";
  if (isset($_POST['submit'])) {
    $response = loginUser($_POST['username'], $_POST['password']);
  }
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link type="text/css" rel="stylesheet" href="gymflow.css" />
    <title>GymFlow</title>
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
    
    <?php if (isset($_SESSION['user'])) { ?>
    <div class="welcome-message">
      <h1>Welcome, <?php echo $_SESSION['user']; ?>!</h1>
      <h2 style="text-align: center; text-decoration: underline;">Current date and time:</h2>
      <p><?php date_default_timezone_set("America/Chicago"); echo date('l, F jS Y - h:i A'); ?></p>
    </div>
    <?php } else { ?>
    <div class="login">
      <h1>Login</h1>
      <p class="error"><?php echo @$response; ?></p>
      <form action="" method="post">
        <label for="username">
          <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username" placeholder="Username" id="username" required>
        <label for="password">
          <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password" placeholder="Password" id="password" required>
        <br><a href="register.php">Don't have an account? Register here!</a>
        <input type="submit" name="submit" value="Login">
      </form>
    </div>
    <?php } ?>

    <footer>
      <h5>© 2023 GymFlow</h5>
    </footer>

  </body>

</html>
