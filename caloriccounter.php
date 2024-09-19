<?php
    // Import functions for the calorie tracker database.
    require "calories.php";
    // We save the date in the users session_id to save the position when
    // they check past and future calorie logs.

    if (!isset($_SESSION['user'])) {
        header("location: index.php");
        exit();
    }

    if ($_SESSION['date'] == null) {
        //If they don't have a date saved, save today's date.
        $_SESSION['date'] = date("Y/m/d");
    }
    // Empty result variable is used to view status of functions.
    $result = "";
    // If the user submits the new item form, and the values are not empty...
    if (isset($_POST['submit'])) if ($_POST['item'] != null && $_POST['cal'] != null) {
        // Add the item, the calorie count, the username, and the date it was added to the database.
        addMeal($_POST['item'], $_POST['cal'], $_SESSION['user'], $_SESSION['date']);
        // Return to result if needed for viewing.
        $result = "Successfully saved!";

        // Store the submitted data in the session
        $_SESSION['food_data'][] = [
        'food' => $_POST['item'],
        'calories' => $_POST['cal'],
        'date' => $_SESSION['date']
        ];
    }
    // If the user sends a GET request using the previous button...
    if (isset($_GET['previous'])) {
        // Subtract the date by 1 day
        // We do this because the propagateHTML function pulls the users calorie logs according to
        // the date that's passed to the function.
        $_SESSION['date'] = date("Y/m/d", strtotime("-1 day", strtotime($_SESSION['date'])));
    }
    // If the user sends a GET request using the next button...
    if (isset($_GET['next'])) {
        // Add 1 day to the date
        // We do this because the propagateHTML function pulls the users calorie logs according to
        // the date that's passed to the function.
        $_SESSION['date'] = date("Y/m/d", strtotime("+1 day", strtotime($_SESSION['date'])));
    }
    // If there's a request to reset the date...
    if (isset($_GET['reset'])) {
        // Set the users session date to today's date.
        $_SESSION['date'] = date("Y/m/d");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link type="text/css" rel="stylesheet" href="gymflow.css" />
    <title>Calorie Tracker - GymFlow</title>
</head>

<body>
    <H1>Calorie Tracker</H1>

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

        <h2>Showing caloric intake for <?php echo $_SESSION['date']; ?></h2>

        <!-- This sends the request to the server to reset the users saved date in the calorie logger.  -->
        <a href="?reset=true">
            <button name="reset">Reset date</button>
        </a>

        <div>
            <?php
            /*
             * propagateHTML()
             * This function uses retrieveCalories() from calories.php in order to
             * pull the users log for the given date from the database.
             * It then displays this information by iterating through it and adding
             * it to a table which is displayed for the user.
             *
             */
            function propagateHTML()
            {
                // Table start
                echo "<table>";
                // Add table headers
                echo "<tr><th>Food</th><th>Calories</th></tr>";
                // Attempt to pull data from the database based on the given date.
                $output = retrieveCalories($_SESSION['date']);
                // Validate the output
                if ($output != null) {
                    // $output is n associative array, therefore each value in this foreach loop
                    // is an array of the rows that were pulled from the database.
                    foreach ($output as $innerArray) {
                        // This verifies that $innerArray, which represents a single row, is valid.
                        if (is_array($innerArray)) {
                            // Start table row.
                            echo "<tr>";
                            // Start static variable sum, this is how we display the total calories of that given date.
                            static $sum = 0;
                            // This iterates through the provided row.
                            foreach ($innerArray as $value) {
                                // Add a row item for each value in this array
                                echo "<td>" . $value . "</td>";
                                // If $value is an int, which only occurs when $value = calories, then
                                // we add them to sum.
                                if (is_int($value)) {
                                    // Add the value to the sum
                                    $sum += $value;
                                }
                            }
                            // Close the row
                            echo "</tr>";
                        } else {
                            // If it's not an array, then print the single value.
                            // This is really only here for testing/error logging purposes.
                            // Will probably be removed/reconfigured in future versions.
                            echo $innerArray;
                        }
                    }
                }
                // Close table
                echo "</table>";
                // Validate that $sum exists (otherwise this would result in an error)
                if (isset($sum)) {
                    // If so, output the total.
                    echo "<p> Total calories consumed: " . $sum . "</p>";
                } else {
                    // Otherwise...
                    echo "<p> No meals have been tracked! </p>";
                }
            }
            // Run the function
            propagateHTML();
            ?>
            <br>
        </div>

        <!-- This is the form for adding new meal items. -->
        <form action="" method="post">
            <input type="text" id="item" name="item" placeholder="Add what you ate here...">
            <br>
            <input type="text" id="cal" name="cal" placeholder="Input caloric intake for this meal here...">
            <br>
            <input type="submit" name="submit" value="Save">
            <!-- This displays the value of result, which is typically a status or error message. -->
            <p> <?php echo $result; ?></p>
        </form>

        <a href="?previous=true">
            <input type="button" name="previous" value="<- Previous">
        </a>
        <a href="?next=true">
            <input type="button" name="next" value="Next ->">
        </a>
    </div>

    <h5>© 2023 GymFlow</h5>
</body>
</html>
