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
            max-width: 800px;
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
                <a href="#" class="action-btn-active" onclick="loadContent('dashboard.php')">Dashboard</a>
                <a href="user_info.php" class="action-btn " onclick="loadContent('user_info.php')"> User Infor</a>
                <a href="#" class="action-btn" onclick="loadContent('add_employee.php')">Add Employ</a>
                <a href="#" class="action-btn" onclick="loadContent('view_employee.php')">View Employ</a>
                <!-- <a href="#" class="action-btn" onclick="loadContent('edit_employee.php')"></a> -->
                <a href="logout.php" class="action-btn logout-btn">Logout</a>
            </div>
        </div>
        <div class="dashboard">
            <div id="content">
                <!-- Content will be loaded here -->
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