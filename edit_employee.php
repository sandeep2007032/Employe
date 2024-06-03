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

// Check if employee ID is provided in the URL
if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    // Fetch employee details from the database
    $sql = "SELECT * FROM employees WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $employee_code = $row['employee_code'];
            $full_name = $row['full_name'];
            $address = $row['address'];
            $salary = $row['salary'];
        } else {
            echo "Employee not found.";
            exit();
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }
} else {
    echo "Employee ID not provided.";
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $new_employee_code = $_POST['employee_code'];
    $new_full_name = $_POST['full_name'];
    $new_address = $_POST['address'];
    $new_salary = $_POST['salary'];

    // Update employee details in the database
    $sql = "UPDATE employees SET employee_code=?, full_name=?, address=?, salary=? WHERE id=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssi", $new_employee_code, $new_full_name, $new_address, $new_salary, $employee_id);
        if ($stmt->execute()) {
            echo "Employee details updated successfully.";
        } else {
            echo "Error updating employee details: " . $conn->error;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="styles.css">
    <style>
       

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
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
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-left">
            <div class="admin-info">
                <img src="admin_icon.png" alt="Admin Icon">
                <span><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></span>
            </div>
            <div class="actions">
                <a href="dashboard.php" class="action-btn">Dashboard</a>
                <a href="user_info.php" class="action-btn">User Info</a>
                <a href="add_employee.php" class="action-btn">Add Employee</a>
                <a href="view_employee.php" class="action-btn">View Employees</a>
                <a href="logout.php" class="action-btn logout-btn">Logout</a>
            </div>
        </div>
        <div class="dashboard">
            <div id="content">
                <h2>Edit Employee</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $employee_id; ?>" method="post">
                    <div class="form-group">
                        <label for="employee_code">Employee Code:</label>
                        <input type="text" id="employee_code" name="employee_code" value="<?php echo htmlspecialchars($employee_code); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" required><?php echo htmlspecialchars($address); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary:</label>
                        <input type="number" id="salary" name="salary" value="<?php echo htmlspecialchars($salary); ?>" required>
                    </div>
                    <button type="submit">Update Employee</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

