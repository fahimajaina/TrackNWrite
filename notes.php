<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Add Note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_note'])) {
    $title = $_POST['title'];
    $note = $_POST['note'];
    $stmt = $conn->prepare("INSERT INTO notes (user_id, title, note) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $note);
    $stmt->execute();
}

// Update Note
if (isset($_GET['edit'])) {
    $note_id = $_GET['edit'];
    $new_title = $_POST['title'];
    $new_note = $_POST['note'];
    if ($new_note) {
        $stmt = $conn->prepare("UPDATE notes SET title = ?, note = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $new_title, $new_note, $note_id, $user_id);
        $stmt->execute();
    }
}

// Delete Note
if (isset($_GET['delete'])) {
    $note_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $note_id, $user_id);
    $stmt->execute();
}

// Fetch Notes
$result = $conn->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$result->bind_param("i", $user_id);
$result->execute();
$notes = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note-Taking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #333;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }
        .navbar-nav .nav-link {
            font-size: 1.1rem;
            padding-left: 20px;
        }
        .container {
            max-width: 800px;
            padding-top: 50px;
        }
        .heading {
            font-weight: bold;
            font-size: 2rem;
            color: #444;
        }
        .form-control, .btn {
            border-radius: 10px;
        }
        .note-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            padding: 20px;
            transition: box-shadow 0.3s ease-in-out;
        }
        .note-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .note-card p {
            color: #333;
        }
        .btn-warning, .btn-danger {
            border-radius: 5px;
        }
        .modal-header {
            background-color: #444;
            color: white;
        }
        .modal-footer .btn-primary {
            border-radius: 5px;
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
                <li class="nav-item"><a class="nav-link" href="todo.php">To-Do List</a></li>
                <li class="nav-item"><a class="nav-link" href="expenses.php">Expense Tracker</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Note-Taking Container -->
<div class="container">
    <h2 class="heading text-center">📝 Note-Taking</h2>

    <!-- Add Note Form -->
    <form method="POST" class="d-flex mt-4">
        <input type="text" name="title" class="form-control" placeholder="Note Title" required>
        <input type="text" name="note" class="form-control ms-2" placeholder="Write your note here" required>
        <button type="submit" name="add_note" class="btn btn-success ms-2">Add Note</button>
    </form>

    <!-- Notes List -->
    <div class="mt-5">
        <ul class="list-group">
            <?php while ($row = $notes->fetch_assoc()) { ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                        <span><?php echo htmlspecialchars($row['note']); ?></span>
                    </div>
                    <div>
                        <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">✏ Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger ms-2">🗑 Delete</a>
                    </div>
                </li>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Note</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="?edit=<?php echo $row['id']; ?>">
                                    <div class="mb-3">
                                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="note" class="form-control" value="<?php echo htmlspecialchars($row['note']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Update Note</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
