<?php
    require "workout.php";

    if (!isset($_SESSION['user'])) {
        header("location: index.php");
        exit();
    }

    $result = "";
    if (isset($_POST['submit'])) {
        $response = saveWorkoutPreferences(
            $_SESSION['user'],
            $_POST['gender'],
            $_POST['age'],
            $_POST['height'],
            $_POST['weight'],
            $_POST['exerciseLevel'],
            $_POST['goals'],
            $_POST['exerciseType'],
            $_POST['equipment'],
            $_POST['preferedLocation'],
            $_POST['exerciseDuration']
        );
        if ($response) {
            $result = "Workout preferences saved successfully!";
        } else {
            $result = "Error saving workout preferences. Please try again.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link type="text/css" rel="stylesheet" href="gymflow.css" />
    <title>Workout Routine Builder - GymFlow</title>
</head>

<body>
    <H1>Workout Routine Builder</H1>

    <div class="navbar">
        <a href="index.php">Homepage</a>
        <div class="dropdown">
            <button class="dropbtn">Services
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="workoutcustomizer.php">Workout Customizer</a>
                <a href="caloriccounter.php">Calorie Tracker</a>
            </div>
        </div>
        <a href="schedule.php">Schedule</a>
        <a href="about.php">About Us</a>
        <a href="account.php">My Account</a>
    </div>

    <form action="" method="post">
        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>
        <br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" min="10" max="120" required>
        <br>

        <label for="height">Height (cm):</label>
        <input type="number" id="height" name="height" min="100" max="250" required>
        <br>

        <label for="weight">Weight (kg):</label>
        <input type="number" id="weight" name="weight" min="30" max="300" required>
        <br>

        <label for="exerciseLevel">Exercise Level:</label>
        <select name="exerciseLevel" id="exerciseLevel" required>
            <option value="very_good">Very Good</option>
            <option value="good">Good</option>
            <option value="average">Average</option>
            <option value="unfit">Unfit</option>
        </select>
        <br>

        <label for="goals">Goals:</label>
        <textarea name="goals" id="goals" placeholder="Enter your fitness goals here" required></textarea>
        <br>

        <label for="exerciseType">Exercise Type:</label>
        <select name="exerciseType[]" id="exerciseType" multiple required>
            <option value="walking">Walking</option>
            <option value="jogging">Jogging</option>
            <option value="dance">Dance</option>
            <option value="yoga">Yoga</option>
            <option value="team_sport">Team Sport</option>
            <option value="i_dont_exercise">I Don't Exercise</option>
            <option value="gym">Gym</option>
        </select>
        <br>

        <label for="equipment">Equipment:</label>
        <input type="text" id="equipment" name="equipment" placeholder="Enter available equipment" required>
        <br>

        <label for="preferedLocation">Preferred Location:</label>
        <select name="preferedLocation" id="preferedLocation" required>
            <option value="gym">Gym</option>
            <option value="home">Home</option>
            <option value="outdoors">Outdoors</option>
        </select>
        <br>

        <label for="exerciseDuration">Exercise Duration (minutes):</label>
        <input type="number" id="exerciseDuration" name="exerciseDuration" min="5" max="180" required>
        <br>

        <input type="submit" name="submit" value="Save Workout Preferences">
        <p><?php echo $result; ?></p>
    </form>

    <h5>© 2023 GymFlow</h5>
</body>
</html>