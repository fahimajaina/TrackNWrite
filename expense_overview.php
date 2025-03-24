<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current month and year
$current_month = date("F");
$current_year = date("Y");
$previous_year = date("Y", strtotime("-1 year"));
$previous_month = date("F", strtotime("-1 month"));

// Fetch total expenses for the current year only
$result = $conn->prepare("SELECT SUM(amount) AS total FROM expenses WHERE user_id = ? AND YEAR(date) = ?");
$result->bind_param("ii", $user_id, $current_year);
$result->execute();
$total_expense_current_year = $result->get_result()->fetch_assoc()['total'] ?? 0;

// Fetch total expenses for the previous year
$previous_year_result = $conn->prepare("SELECT SUM(amount) AS total FROM expenses WHERE user_id = ? AND YEAR(date) = ?");
$previous_year_result->bind_param("ii", $user_id, $previous_year);
$previous_year_result->execute();
$total_expense_previous_year = $previous_year_result->get_result()->fetch_assoc()['total'] ?? 0;

// Fetch expenses by category
$category_result = $conn->prepare("SELECT expense_name, SUM(amount) AS total FROM expenses WHERE user_id = ? GROUP BY expense_name ORDER BY total DESC");
$category_result->bind_param("i", $user_id);
$category_result->execute();
$categories = $category_result->get_result();

// Fetch expenses by time periods
// Weekly expense
$weekly_result = $conn->prepare("SELECT SUM(amount) AS total FROM expenses WHERE user_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");
$weekly_result->bind_param("i", $user_id);
$weekly_result->execute();
$weekly_expense = $weekly_result->get_result()->fetch_assoc()['total'] ?? 0;

// Current month's expense
$monthly_result = $conn->prepare("SELECT SUM(amount) AS total FROM expenses WHERE user_id = ? AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = ?");
$monthly_result->bind_param("ii", $user_id, $current_year);
$monthly_result->execute();
$monthly_expense = $monthly_result->get_result()->fetch_assoc()['total'] ?? 0;

// Previous month's expense
$previous_month_result = $conn->prepare("SELECT SUM(amount) AS total FROM expenses WHERE user_id = ? AND MONTH(date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(date) = ?");
$previous_month_result->bind_param("ii", $user_id, $current_year);
$previous_month_result->execute();
$previous_month_expense = $previous_month_result->get_result()->fetch_assoc()['total'] ?? 0;

// Yearly expense (for current and previous year)
$yearly_result = $conn->prepare("SELECT SUM(amount) AS total FROM expenses WHERE user_id = ? AND YEAR(date) = ?");
$yearly_result->bind_param("ii", $user_id, $current_year);
$yearly_result->execute();
$yearly_expense_current_year = $yearly_result->get_result()->fetch_assoc()['total'] ?? 0;

$previous_yearly_result = $conn->prepare("SELECT SUM(amount) AS total FROM expenses WHERE user_id = ? AND YEAR(date) = ?");
$previous_yearly_result->bind_param("ii", $user_id, $previous_year);
$previous_yearly_result->execute();
$yearly_expense_previous_year = $previous_yearly_result->get_result()->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Overview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome CDN -->
    <style>
        body {
            background-color: #121212;
            font-family: 'Roboto', sans-serif;
            color: #e0e0e0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
        }
        .overview-card {
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            padding: 25px;
            margin-bottom: 20px;
            background-color: #1e1e1e;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .overview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        h2 {
            font-size: 32px;
            color: #03a9f4;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }
        h4, h5 {
            color: #8bc34a;
        }
        .list-group-item {
            background-color: #2c2c2c;
            border: 1px solid #444;
            padding: 15px;
            font-size: 16px;
            color: #e0e0e0;
        }
        .list-group-item span {
            font-weight: bold;
            color: #ffffff;
        }
        .badge {
            background-color: #ff5722;
            color: #fff;
        }
        .card-header {
            background-color: #03a9f4;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 12px;
            border-radius: 10px 10px 0 0;
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 18px;
            color: #ffffff;
            background-color: #03a9f4;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .back-btn i {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<a href="expenses.php" class="back-btn">
    <i class="fas fa-arrow-left"></i>Back to Expense Tracker
</a>

<div class="container mt-5">
    <h2 class="text-center mb-4">📊 Expense Overview</h2>

    <div class="overview-card">
        <h4>Total Expenses for <?php echo $current_year; ?>: <span>$<?php echo number_format($total_expense_current_year, 2); ?></span></h4>
    </div>

    <div class="overview-card">
        <h5>Weekly Expenses: <span>$<?php echo number_format($weekly_expense, 2); ?></span></h5>
    </div>

    <div class="overview-card">
        <h5>Monthly Expenses</h5>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo $current_month; ?>: <span>$<?php echo number_format($monthly_expense, 2); ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo $previous_month; ?>: <span>$<?php echo number_format($previous_month_expense, 2); ?></span>
            </li>
        </ul>
    </div>

    <div class="overview-card">
        <h5>Yearly Expenses</h5>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo $current_year; ?>: <span>$<?php echo number_format($yearly_expense_current_year, 2); ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo $previous_year; ?>: <span>$<?php echo number_format($yearly_expense_previous_year, 2); ?></span>
            </li>
        </ul>
    </div>

    <div class="overview-card">
        <h5>Expenses by Category</h5>
        <ul class="list-group">
            <?php while ($row = $categories->fetch_assoc()) { ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($row['expense_name']); ?>
                    <span class="badge bg-primary">$<?php echo number_format($row['total'], 2); ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
