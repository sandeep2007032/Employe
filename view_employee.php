<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch admin details from the session or database
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
$last_name = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Your CSS styles */
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
        #employeeList table {
            width: 100%;
            border-collapse: collapse;
        }

        #employeeList th,
        #employeeList td {
            padding: 10px;
            text-align: left;
        }

        #employeeList th {
            background-color: #f2f2f2;
        }

        #employeeList tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        #employeeList tr:hover {
            background-color: #ddd;
        }

        #employeeList td img {
            max-width: 50px;
            max-height: 50px;
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
                <a href="user_info.php" class="action-btn">User Inform</a>
                <a href="add_employee.php" class="action-btn">Add Employ</a>
                <a href="view_employee.php" class="action-btn">View Employ</a>
                <a href="logout.php" class="action-btn logout-btn">Logout</a>
            </div>
        </div>
        <div class="dashboard">
            <div id="content">
                <?php
                include 'config.php';
                // Fetch all employees from the database
                $sql = "SELECT * FROM employees";
                $result = $conn->query($sql);

                // Check if there are any employees
                if ($result->num_rows > 0) {
                    echo "<h2>Employee List</h2>";
                    echo "<div id='employeeList'>";
                    echo "<table border='1'>";
                    echo "<tr><th>Employee Code</th><th>Full Name</th><th>Address</th><th>Salary</th><th>Image</th><th>Action</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["employee_code"] . "</td>";
                        echo "<td>" . $row["full_name"] . "</td>";
                        echo "<td>" . $row["address"] . "</td>";
                        echo "<td>" . $row["salary"] . "</td>";
                        echo "<td><img src='" . $row["image"] . "' alt='Employee Image' style='max-width: 100px; max-height: 100px;'></td>";
                        echo "<td>";
                        echo "<button onclick='editEmployee(" . $row['id'] . ")'>Edit</button>";
                        echo "<button onclick='deleteEmployee(" . $row['id'] . ")'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "No employees found.";
                }

                $conn->close();
                ?>
                <script>
                function editEmployee(id) {
                    window.location.href = 'edit_employee.php?id=' + id;
                }

                function deleteEmployee(id) {
                    if (confirm("Are you sure you want to delete this employee?")) {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (this.readyState === 4 && this.status === 200) {
                                document.getElementById("employeeList").innerHTML = this.responseText;
                            }
                        };
                        xhr.open("GET", "delete_employee.php?id=" + id, true);
                        xhr.send();
                    }
                }
                </script>
            </div>
        </div>
    </div>
</body>
</html>
