<?php
include 'db_config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (!$date || !$category || !$amount || $amount <= 0) {
        $error = "Please fill all required fields correctly.";
    } else {
        $stmt = $conn->prepare("INSERT INTO expenses (date, category, description, amount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $date, $category, $description, $amount);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4"> Add New Expense</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4">
        <div class="mb-3">
            <label>Date <span class="text-danger">*</span></label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Category <span class="text-danger">*</span></label>
            <input type="text" name="category" class="form-control" placeholder="e.g. Food, Travel, Shopping" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Optional"></textarea>
        </div>

        <div class="mb-3">
            <label>Amount (₹) <span class="text-danger">*</span></label>
            <input type="number" name="amount" class="form-control" step="0.01" required>
        </div>
<br>
        <button type="submit" class="btn btn-success"> Save Expense</button>
<br>
        <a href="index.php" class="btn btn-secondary">← Back</a>
    </form>
</div>
</body>
</html>
