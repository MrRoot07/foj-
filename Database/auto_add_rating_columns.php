<?php
/**
 * Auto-add rating columns to request table if they don't exist
 * Run this file once to set up the rating feature
 * Access via: http://localhost/foj--main/Database/auto_add_rating_columns.php
 */

// Database connection
$con = mysqli_connect("localhost", "root", "", "royal_express_db");

if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$errors = [];
$success = [];

// Check and add rating column
$check_rating = "SHOW COLUMNS FROM request LIKE 'rating'";
$result = mysqli_query($con, $check_rating);
if (mysqli_num_rows($result) == 0) {
    $sql = "ALTER TABLE `request` ADD COLUMN `rating` INT(1) DEFAULT NULL COMMENT 'Rating from 1 to 5'";
    if (mysqli_query($con, $sql)) {
        $success[] = "Added 'rating' column";
    } else {
        $errors[] = "Error adding 'rating' column: " . mysqli_error($con);
    }
} else {
    $success[] = "Column 'rating' already exists";
}

// Check and add rating_comment column
$check_comment = "SHOW COLUMNS FROM request LIKE 'rating_comment'";
$result = mysqli_query($con, $check_comment);
if (mysqli_num_rows($result) == 0) {
    $sql = "ALTER TABLE `request` ADD COLUMN `rating_comment` TEXT DEFAULT NULL COMMENT 'Optional comment with rating'";
    if (mysqli_query($con, $sql)) {
        $success[] = "Added 'rating_comment' column";
    } else {
        $errors[] = "Error adding 'rating_comment' column: " . mysqli_error($con);
    }
} else {
    $success[] = "Column 'rating_comment' already exists";
}

// Check and add rating_date column
$check_date = "SHOW COLUMNS FROM request LIKE 'rating_date'";
$result = mysqli_query($con, $check_date);
if (mysqli_num_rows($result) == 0) {
    $sql = "ALTER TABLE `request` ADD COLUMN `rating_date` DATETIME DEFAULT NULL COMMENT 'Date when rating was submitted'";
    if (mysqli_query($con, $sql)) {
        $success[] = "Added 'rating_date' column";
    } else {
        $errors[] = "Error adding 'rating_date' column: " . mysqli_error($con);
    }
} else {
    $success[] = "Column 'rating_date' already exists";
}

// Check and add index
$check_index = "SHOW INDEX FROM request WHERE Key_name = 'idx_rating'";
$result = mysqli_query($con, $check_index);
if (mysqli_num_rows($result) == 0) {
    $sql = "ALTER TABLE `request` ADD INDEX `idx_rating` (`rating`)";
    if (mysqli_query($con, $sql)) {
        $success[] = "Added index 'idx_rating'";
    } else {
        $errors[] = "Error adding index: " . mysqli_error($con);
    }
} else {
    $success[] = "Index 'idx_rating' already exists";
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Columns Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2563eb;
            margin-bottom: 20px;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 12px;
            border-radius: 6px;
            margin: 10px 0;
            border-left: 4px solid #10b981;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            margin: 10px 0;
            border-left: 4px solid #ef4444;
        }
        .info {
            background: #dbeafe;
            color: #1e40af;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            border-left: 4px solid #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Rating Columns Setup</h1>
        
        <?php if (!empty($success)): ?>
            <h2>Success Messages:</h2>
            <?php foreach ($success as $msg): ?>
                <div class="success">✓ <?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <h2>Errors:</h2>
            <?php foreach ($errors as $msg): ?>
                <div class="error">✗ <?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (empty($errors)): ?>
            <div class="info">
                <strong>✅ Setup Complete!</strong><br>
                All rating columns have been added successfully. You can now use the rating feature.
                <br><br>
                <a href="../tracking.php" style="color: #2563eb; text-decoration: underline;">Go to Tracking Page</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

