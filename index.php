<?php
// Include database connection
include 'config.php';

session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT first_name, last_name, email FROM users WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name, $email);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Fetch total number of employees
$sql = "SELECT COUNT(*) FROM employees";
$result = $conn->query($sql);
$total_employees = $result->fetch_row()[0];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .container {
            display: flex;
            margin: 100px;
        }

        .dashboard {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex-grow: 1;
        }

        .dashboard-left {
            width: 200px;
            background-color: #4CAF50;
            border-radius: 10px;
            padding: 20px;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-info {
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .action-btn {
            padding: 10px 20px;
            background-color: #fff;
            color: #4CAF50;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
            margin: 5px;
        }

        .action-btn:hover {
            background-color: #4CAF50;
            color: #fff;
        }

        .logout-btn {
            background-color: #f44336;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }

        .admin-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .admin-info img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .admin-info span {
            font-weight: bold;
        }

        #content {
            margin-left: 20px;
        }

        .employee-count {
            background-color: #e0f7fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-left">
            <div class="admin-info">
             
                <span><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></span>
            </div>
            <div class="actions">
            <a href="index.php" class="action-btn">Dashboard</a>
                <a href="user_info.php" class="action-btn">User Info</a>
                <a href="add_employee.php" class="action-btn">Add Employee</a>
                <a href="view_employee.php" class="action-btn">View Employees</a>
                <a href="logout.php" class="action-btn logout-btn">Logout</a>
            </div>
        </div>
        <div class="dashboard">
            <div id="content">
                <div class="welcome-message">
                    <h2>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h2>
                </div>
                <div class="employee-count">
                    Total Employees: <?php echo $total_employees; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadContent(page) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("content").innerHTML = this.responseText;
                    // Remove active class from all action buttons
                    var actionButtons = document.querySelectorAll('.action-btn');
                    actionButtons.forEach(function(button) {
                        button.classList.remove('active');
                    });
                    // Add active class to the clicked button
                    event.target.classList.add('active');
                }
            };
            xhttp.open("GET", page, true);
            xhttp.send();
        }
    </script>
</body>
</html>
