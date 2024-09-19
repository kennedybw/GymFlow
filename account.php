<?php
    require 'plugin.php';
    if (!isset($_SESSION['user'])) {
        header("location: index.php");
        exit();
    }

    if (isset($_GET['logout'])) {
        logoutUser();
        header("location: index.php");
    }

    if (isset($_GET['confirm-account-deletion'])) {
        deleteAccount();
        header("location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link type="text/css" rel="stylesheet" href="gymflow.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #333;
        }

        .navbar a {
            color: #f4f4f4;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
        }

        th, td {
            height: 30px;
            padding: 5px;
            text-align: left;
            border: 1px solid #ccc;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 5px 15px;
            border: none;
            cursor: pointer;
            margin: 10px;
            font-size: 1rem;
        }

        button:hover {
            background-color: #555;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        .half-screen-table {
            width: 50%;
            float: left;
        }
    </style>
    <title>Account Details - GymFlow</title>
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
            <a href="caloriccounter.php">Calorie Tracker</a>
        </div>
    </div>
    <a href="schedule.php">Schedule</a>
    <a href="about.php">About Us</a>
    <div class="dropdown">
        <button class="dropbtn">My Account
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="?logout">Logout</a>
            <a href="?confirm-account-deletion">Delete account</a>
        </div>
    </div>
</div>

<h1>Hello, <?php echo $_SESSION["user"] ?>.</h1>

<?php
if (isset($_SESSION['food_data']) && !empty($_SESSION['food_data'])) {
?>
    <h2>Food Details</h2>
    <table class="half-screen-table">
        <tr>
            <th>Food</th>
            <th>Calories</th>
            <th>Date</th>
        </tr>
        <?php foreach ($_SESSION['food_data'] as $data) { ?>
            <tr>
                <td><?php echo htmlspecialchars($data['food']); ?></td>
                <td><?php echo htmlspecialchars($data['calories']); ?></td>
                <td><?php echo htmlspecialchars($data['date']); ?></td>
            </tr>
        <?php } ?>
    </table>
<?php
} else {
?>
    <p>No data available. Please go to <a href="caloriccounter.php">Caloric Counter</a> to submit your data.</p>
<?php
}
?>

</body>

</html>
