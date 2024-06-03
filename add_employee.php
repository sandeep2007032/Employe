<?php
// Include database connection
include 'config.php';

session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch admin details from the session or database
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
$last_name = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';

// If session variables are not set, fetch from the database
if (empty($first_name) || empty($last_name)) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT first_name, last_name FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($first_name, $last_name);
        $stmt->fetch();
        $stmt->close();
        // Update session variables
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
    } else {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
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
input[type="number"],
input[type="address"]




{
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
#preview {
            max-width: 100px;
            max-height: 100px;
            display: none;
            margin-top: 10px;
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
            <h2>Add Employee</h2>
            <div id="message"></div>
            <form id="addEmployeeForm" enctype="multipart/form-data">
    <div class="form-group">
        <label for="employee_code">Employee Code:</label>
        <input type="text" id="employee_code" name="employee_code" required>
        <span id="employeeCodeError" style="color: red;"></span>
    </div>
    <div class="form-group">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required>
    </div>
    <div class="form-group">
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>
    </div>
    <div class="form-group">
        <label for="salary">Salary:</label>
        <input type="number" id="salary" name="salary" required>
    </div>
    <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)" required>
        <img id="preview" src="#" alt="Preview Image">
    </div>
    <button type="button" onclick="addEmployee()">Add Employee</button>
</form>

        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var preview = document.getElementById('preview');
                preview.src = reader.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        function addEmployee() {
            
            var form = document.getElementById('addEmployeeForm');
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        document.getElementById('message').innerHTML = this.responseText;
                        clearForm();
                    } else {
                        document.getElementById('message').innerHTML = "Error: Unable to add employee.";
                    }
                }
            };
            xhr.open("POST", "submit_employee.php", true);
            xhr.send(formData);
        }

        function clearForm() {
            document.getElementById('addEmployeeForm').reset();
            document.getElementById('preview').style.display = 'none';
        }
       

    </script>
</body>
</html>
