<?php
// Include database connection
include 'config.php';

// Check if employee ID is provided in the URL
if(isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    // Delete employee from the database
    $sql_delete = "DELETE FROM employees WHERE id = ?";
    if ($stmt_delete = $conn->prepare($sql_delete)) {
        $stmt_delete->bind_param("i", $employee_id);
        if ($stmt_delete->execute()) {
            // After deleting the employee, fetch the updated list of employees
            $sql_select = "SELECT * FROM employees";
            $result = $conn->query($sql_select);

            // Check if there are any employees left
            if ($result->num_rows > 0) {

                echo "<table border='1'>";
                echo "<tr><th>Employee Code</th><th>Full Name</th><th>Address</th><th>Salary</th><th>Image</th> <th>Action</th></tr>";
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
            } else {
                echo "No employees found.";
            }
        } else {
            echo "Error deleting employee: " . $conn->error;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
} else {
    echo "Employee ID not provided.";
    exit();
}
?>
