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
$sql = "SELECT first_name, last_name, email, location, pin_code FROM users WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name, $email, $location, $pin_code);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $location = htmlspecialchars(trim($_POST['location']));
    $pin_code = htmlspecialchars(trim($_POST['pin_code']));
    
    // Update the user's details in the database
    $update_sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, location = ?, pin_code = ? WHERE id = ?";
    if ($update_stmt = $conn->prepare($update_sql)) {
        $update_stmt->bind_param("sssssi", $first_name, $last_name, $email, $location, $pin_code, $user_id);
        if ($update_stmt->execute()) {
            // User details updated successfully
            echo "User details updated successfully!";
            // Update session variables if necessary
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
        } else {
            echo "Error updating user details: " . $update_stmt->error;
        }
        $update_stmt->close();
    } else {
        echo "Error preparing update statement: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Info</title>
    <link rel="stylesheet" href="styles.css">
</head>
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


/* Add this CSS to your existing styles */

/* Adjustments for smaller screens */
@media only screen and (max-width: 600px) {
    .container {
        flex-direction: column;
        align-items: center;
        margin: 20px;
    }

    .dashboard-left, .dashboard {
        width: auto;
        margin-bottom: 20px;
    }
}

/* Additional styling for form fields */
.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

</style>
<body><div class="container">
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


    
                <form id="userInfoForm" method="post">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>">
                    </div>
                    <div class="form-group">
                        <label for="pin_code">Pin Code:</label>
                        <input type="text" id="pin_code" name="pin_code" value="<?php echo htmlspecialchars($pin_code); ?>">
                    </div>
                    <input type="submit" value="Save">
                </form>
                <div id="message"></div>
            </div>
        </div>
    </div>
    </div>
        </div>
    </div>

   
</body>
</html>
