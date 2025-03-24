<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Add Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task = $_POST['task'];
    $stmt = $conn->prepare("INSERT INTO tasks (user_id, task) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $task);
    $stmt->execute();
}

// Mark Task as Completed
if (isset($_GET['complete'])) {
    $task_id = $_GET['complete'];
    $stmt = $conn->prepare("UPDATE tasks SET status = 'completed' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
}

// Delete Task
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
}

// Fetch Tasks
$result = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$result->bind_param("i", $user_id);
$result->execute();
$tasks = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
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
        .task-form {
            max-width: 600px;
            margin: 0 auto;
        }
        .task-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .task-item {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        .task-item.completed {
            background-color: #e2f9e6;
        }
        .task-item.pending {
            background-color: #f9f9f9;
        }
        .task-btn {
            border-radius: 5px;
        }
        .container {
            max-width: 900px;
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
        <a class="navbar-brand" href="homepage.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="notes.php">Note-Taking</a></li>
                <li class="nav-item"><a class="nav-link" href="expenses.php">Expense Tracker</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- To-Do List Container -->
<div class="container mt-5">
    <h2 class="text-center">📌 To-Do List</h2>

    <!-- Task Form -->
    <form method="POST" class="task-form d-flex mt-3">
        <input type="text" name="task" class="form-control" placeholder="Add a new task" required>
        <button type="submit" name="add_task" class="btn btn-success ms-2 task-btn">Add Task</button>
    </form>

    <!-- Task List -->
    <div class="task-list mt-4">
        <?php while ($row = $tasks->fetch_assoc()) { ?>
            <div class="task-item card shadow-lg <?php echo $row['status'] == 'completed' ? 'completed' : 'pending'; ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="<?php echo $row['status'] == 'completed' ? 'text-decoration-line-through text-muted' : ''; ?>">
                        <?php echo htmlspecialchars($row['task']); ?>
                    </span>
                    <div>
                        <?php if ($row['status'] == 'pending') { ?>
                            <a href="?complete=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning task-btn">✔ Mark Done</a>
                        <?php } ?>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger task-btn">🗑 Delete</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
