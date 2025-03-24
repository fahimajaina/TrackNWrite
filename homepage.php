<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .navbar {
            background-color: #222222;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .nav-link {
            font-size: 1.1rem;
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card h4 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .card p {
            font-size: 1rem;
            color: #555;
        }
        .container {
            max-width: 1200px;
        }
        .welcome-text {
            font-weight: bold;
            font-size: 2rem;
            color: #333;
        }
        .lead {
            font-size: 1.2rem;
            color: #666;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">User Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="todo.php">To-Do List</a></li>
                <li class="nav-item"><a class="nav-link" href="notes.php">Note-Taking</a></li>
                <li class="nav-item"><a class="nav-link" href="expenses.php">Expense Tracker</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Welcome Section -->
<div class="container text-center mt-5">
    <h1 class="welcome-text">Welcome, <?php echo $_SESSION['name']; ?>!</h1>
    <p class="lead">Manage your tasks, notes, and expenses easily with our simple tools.</p>
    
    <div class="row mt-5">
        <div class="col-md-4">
            <a href="todo.php" class="text-decoration-none">
                <div class="card shadow-lg p-4 text-center">
                    <h4>📌 To-Do List</h4>
                    <p>Manage your daily tasks and stay organized.</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="notes.php" class="text-decoration-none">
                <div class="card shadow-lg p-4 text-center">
                    <h4>📝 Note-Taking</h4>
                    <p>Keep your important notes and ideas in one place.</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="expenses.php" class="text-decoration-none">
                <div class="card shadow-lg p-4 text-center">
                    <h4>💰 Expense Tracker</h4>
                    <p>Track your spending and manage your finances.</p>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
