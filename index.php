<?php
include 'db_config.php';

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$category = $_GET['category'] ?? '';

$where = "1";
if ($from && $to) {
    $where .= " AND date BETWEEN '$from' AND '$to'";
}
if ($category) {
    $where .= " AND category = '$category'";
}

$sql = "SELECT * FROM expenses WHERE $where ORDER BY date DESC";
$result = $conn->query($sql);

$catResult = $conn->query("SELECT DISTINCT category FROM expenses");

$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daily Expense Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>💸 Daily Expense Tracker</h2>
        <a href="add_expense.php" class="btn btn-success"> Add Expense</a>
    </div>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>" placeholder="From date">
        </div>
        <div class="col-md-3">
            <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>" placeholder="To date">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">-- All Categories --</option>
                <?php while($cat = $catResult->fetch_assoc()): ?>
                    <option value="<?= $cat['category'] ?>" <?= ($category == $cat['category']) ? 'selected' : '' ?>>
                        <?= $cat['category'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100"> Filter</button>
        </div>
    </form>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount (₹)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): 
                            $total += $row['amount'];
                        ?>
                        <tr>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['category'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= number_format($row['amount'], 2) ?></td>
                            <td>
                                <a href="edit_expense.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"> Edit</a>
                                <a href="delete_expense.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"> Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">No expenses found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="text-end fw-bold fs-5">
            Total: ₹ <?= number_format($total, 2) ?>
        </div>
    </div>
</div>
</body>
</html>
