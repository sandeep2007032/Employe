
<?php
// Include database connection
include 'config.php'; // This file should contain your database connection details

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $mobile = htmlspecialchars(trim($_POST['mobile']));
    $password = htmlspecialchars(trim($_POST['password']));
    
    // Validate inputs (example: email and mobile)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        die("Invalid mobile number");
    }
    
    // Check if the email is already registered
    $sql = "SELECT id FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Email is already registered
            $stmt->close();
            echo "Email is already registered. <a href='login.php'>Login here</a>";
        } else {
            $stmt->close();
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
            // Prepare and execute the SQL statement
            $sql = "INSERT INTO users (first_name, last_name, email, mobile, password) VALUES (?, ?, ?, ?, ?)";
    
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sssss", $first_name, $last_name, $email, $mobile, $hashed_password);
                
                if ($stmt->execute()) {
                    // Redirect to login page after successful registration
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
                
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="phone"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .signin-link {
            text-align: center;
            margin-top: 20px;
        }

        .signin-link a {
            color: #333;
            text-decoration: none;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 600px) {
            form {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h2>Registration Form</h2>
    <form action="register.php" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>
        
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="mobile">Mobile:</label>
        <input type="tel" id="mobile" name="mobile" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        
        <input type="submit" value="Register">
    </form>
    
    <div class="signin-link">
        Already have an account? <a href="login.php">Sign in</a>
    </div>
</body>
</html>
