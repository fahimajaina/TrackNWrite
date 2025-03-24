<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Add Expense
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_expense'])) {
    $expense_name = $_POST['expense_name'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $stmt = $conn->prepare("INSERT INTO expenses (user_id, expense_name, amount, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $expense_name, $amount, $date);
    $stmt->execute();
}

// Update Expense
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_expense'])) {
    $expense_id = $_POST['expense_id'];
    $expense_name = $_POST['expense_name'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    if ($expense_name && $amount && $date) {
        $stmt = $conn->prepare("UPDATE expenses SET expense_name = ?, amount = ?, date = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sdssi", $expense_name, $amount, $date, $expense_id, $user_id);
        $stmt->execute();
    }
}

// Delete Expense
if (isset($_GET['delete'])) {
    $expense_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $expense_id, $user_id);
    $stmt->execute();
}

// Fetch Expenses
$result = $conn->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY date DESC");
$result->bind_param("i", $user_id);
$result->execute();
$expenses = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }
        .navbar {
            background-color: #4CAF50;
        }
        .navbar a {
            color: #fff;
        }
        .container {
            max-width: 900px;
        }
        .expense-card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
        }
        .expense-actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="homepage.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="todo.php">To-Do List</a></li>
                <li class="nav-item"><a class="nav-link" href="notes.php">Note-Taking</a></li>
                <li class="nav-item"><a class="nav-link" href="expense_overview.php">Expense Overview</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center text-dark mb-4">💰 Expense Tracker</h2>

    <!-- Add Expense Form -->
    <form method="POST" class="d-flex gap-3 flex-wrap mb-4">
        <input type="text" name="expense_name" class="form-control w-100" placeholder="Expense Name" required>
        <input type="number" name="amount" class="form-control w-100" placeholder="Amount" step="0.01" required>
        <input type="date" name="date" class="form-control w-100" required>
        <button type="submit" name="add_expense" class="btn btn-success w-auto">Add Expense</button>
    </form>

    <!-- Expenses List -->
    <?php while ($row = $expenses->fetch_assoc()) { ?>
        <div class="expense-card">
            <div class="d-flex justify-content-between align-items-center">
                <h5><?php echo htmlspecialchars($row['expense_name']); ?> <span class="text-muted">($<?php echo number_format($row['amount'], 2); ?>)</span></h5>
                <span class="text-muted"> <?php echo $row['date']; ?> </span>
            </div>
            <div class="expense-actions">
                <button class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">✏ Edit</button>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm btn-icon">🗑 Delete</a>
            </div>
        </div>
        
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="expense_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="expense_name" class="form-control" value="<?php echo htmlspecialchars($row['expense_name']); ?>" required>
                            <input type="number" name="amount" class="form-control" value="<?php echo $row['amount']; ?>" step="0.01" required>
                            <input type="date" name="date" class="form-control" value="<?php echo $row['date']; ?>" required>
                            <button type="submit" name="update_expense" class="btn btn-primary w-100">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
