<?php
/*
 * workout.php
 * Contains variables and functions needed for the workout customizer.
 *
 */

// Borrowing the common functions from plugin.php
require "plugin.php";

// Since the workout data is tied to the users account,
// we need to be able to retrieve their username from session_id
$user = $_SESSION['user'];

function retrieveWorkoutPreferences($user)
{
    $mysqli = sqlConnect();
    if ($mysqli) {

        // SQL command to pull all records from workout_preferences under this users account
        $stmt = $mysqli->prepare("SELECT * FROM WORKOUT_PREFERENCES WHERE USERNAME = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        // Save result
        $result = $stmt->get_result();
        // Save associative array in $output
        $output = $result->fetch_assoc();

        if ($output == null) {
            consoleLog("Workout preferences have never been recorded for this user.");
            return null;
        } else {
            return $output;
        }
    }
}

function saveWorkoutPreferences($user, $gender, $age, $height, $weight, $exerciseLevel, $goals, $exerciseType, $equipment, $preferedLocation, $exerciseDuration)
{
    $mysqli = sqlConnect();
    if ($mysqli) {

        $stmt = $mysqli->prepare("INSERT INTO WORKOUT_PREFERENCES(USERNAME, GENDER, AGE, HEIGHT, WEIGHT, EXERCISE_LEVEL, GOALS, EXERCISE_TYPE, EQUIPMENT, PREFERRED_LOCATION, EXERCISE_DURATION) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiisssssi", $user, $gender, $age, $height, $weight, $exerciseLevel, $goals, $exerciseType, $equipment, $preferedLocation, $exerciseDuration);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            consoleLog("An error occurred. Please try again.");
        } else {
            echo "Successfully added to database.";
        }
    }
}
?>
