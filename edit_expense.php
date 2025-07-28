<?php
include 'db_config.php';

$id = $_GET['id'] ?? 0;
$error = '';
$success = '';

if (!$id || !is_numeric($id)) {
    die("Invalid Expense ID");
}

$stmt = $conn->prepare("SELECT * FROM expenses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$expense = $result->fetch_assoc();
$stmt->close();

if (!$expense) {
    die("Expense not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (!$date || !$category || !$amount || $amount <= 0) {
        $error = "Please fill all required fields correctly.";
    } else {
        $stmt = $conn->prepare("UPDATE expenses SET date = ?, category = ?, description = ?, amount = ? WHERE id = ?");
        $stmt->bind_param("sssdi", $date, $category, $description, $amount, $id);
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
    <title>Edit Expense</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4"> Edit Expense</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4">
        <div class="mb-3">
            <label>Date <span class="text-danger">*</span></label>
            <input type="date" name="date" class="form-control" value="<?= $expense['date'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Category <span class="text-danger">*</span></label>
            <input type="text" name="category" class="form-control" value="<?= $expense['category'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="2"><?= $expense['description'] ?></textarea>
        </div>

        <div class="mb-3">
            <label>Amount (₹) <span class="text-danger">*</span></label>
            <input type="number" name="amount" class="form-control" step="0.01" value="<?= $expense['amount'] ?>" required>
        </div>
<br>
        <button type="submit" class="btn btn-primary"> Update Expense</button>
        <br>
        <a href="index.php" class="btn btn-secondary">← Back</a>
    </form>
</div>
</body>
</html>
