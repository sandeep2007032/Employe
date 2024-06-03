<?php
// Include database connection
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_code = htmlspecialchars(trim($_POST['employee_code']));
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $address = htmlspecialchars(trim($_POST['address']));
    $salary = htmlspecialchars(trim($_POST['salary']));

    $sql = "SELECT id FROM employees WHERE employee_code = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Email is already registered
            $stmt->close();
            echo "Email is already registered. <a href='add_employee.php'>Login here</a>";
        
        }}
    // Directory where images will be uploaded
    $target_dir = "uploads/";

    // Check if the directory exists, if not, create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $image_name = basename($image['name']);
        $target_file = $target_dir . $image_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            $image_path = $target_file;

            // Insert employee details into the database
            $sql = "INSERT INTO employees (employee_code, full_name, address, salary, image) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sssis", $employee_code, $full_name, $address, $salary, $image_path);
                if ($stmt->execute()) {
                    echo "Employee added successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "Please select an image to upload.";
    }
}

$conn->close();
?>
