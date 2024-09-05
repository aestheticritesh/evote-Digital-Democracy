<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
include 'db.php';

// Handle adding new candidate
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['candidate_name'])) {
    $candidate_name = trim($_POST['candidate_name']);
    $candidate_image = $_FILES['candidate_image']['name'];
    $target_dir = "candidate_images/";
    $target_file = $target_dir . basename($candidate_image);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate candidate name
    if (empty($candidate_name)) {
        $error_message = "Candidate name is required.";
    }

    // Validate and upload image file
    if (!empty($candidate_image)) {
        // Check if image file is a valid image
        $check = getimagesize($_FILES['candidate_image']['tmp_name']);
        if ($check === false) {
            $error_message = "File is not an image.";
            $upload_ok = 0;
        }

        // Check file size (e.g., 5MB)
        if ($_FILES['candidate_image']['size'] > 5000000) {
            $error_message = "Sorry, your file is too large.";
            $upload_ok = 0;
        }

        // Allow certain file formats
        if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
            $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $upload_ok = 0;
        }

        // Check if $upload_ok is set to 0 by an error
        if ($upload_ok == 0) {
            $error_message = "Sorry, your file was not uploaded.";
        } else {
            // Try to upload file
            if (!move_uploaded_file($_FILES['candidate_image']['tmp_name'], $target_file)) {
                $error_message = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Insert candidate into database if no errors
    if (empty($error_message) && !empty($candidate_name)) {
        $stmt = $conn->prepare("INSERT INTO candidates (name, image, votes) VALUES (?, ?, 0)");
        $stmt->bind_param("ss", $candidate_name, $candidate_image);
        $stmt->execute();
        $stmt->close();
        
        // Redirect to the same page to prevent form resubmission
        header("Location: admin.php");
        exit();
    }
}

// Handle user approval
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_user_id'])) {
    $user_id = intval($_POST['approve_user_id']);
    
    $stmt = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Handle candidate deletion
if (isset($_GET['delete'])) {
    $candidate_id = intval($_GET['delete']); // Ensure candidate_id is an integer
    $stmt = $conn->prepare("DELETE FROM candidates WHERE id=?");
    $stmt->bind_param("i", $candidate_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php"); // Redirect after deletion
    exit();
}

// Fetch all candidates
$candidates_result = $conn->query("SELECT * FROM candidates");

// Fetch all pending users
$pending_users_result = $conn->query("SELECT * FROM users WHERE status = 'pending'");

// Fetch all approved users
$approved_users_result = $conn->query("SELECT * FROM users WHERE status = 'approved'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Online Election System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            color: #fff;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            z-index: -1;
            filter: brightness(50%);
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            max-width: 1500px;
            width: 100%;
            z-index: 1;
            overflow: auto;
            height: 90vh;
        }

        .form-group label {
            color: #ccc;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert-danger {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .table thead th {
            color: #ddd;
        }

        .table tbody td {
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Admin Panel - Online Election System</h1>

        <!-- Logout button -->
        <div class="text-right mb-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Add new candidate form -->
        <form method="post" action="admin.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="candidate_name">Add New Candidate</label>
                <input type="text" name="candidate_name" class="form-control" id="candidate_name" required>
            </div>
            <div class="form-group">
                <label for="candidate_image">Candidate Image</label>
                <input type="file" name="candidate_image" class="form-control" id="candidate_image">
            </div>
            <button type="submit" class="btn btn-primary">Add Candidate</button>
        </form>

        <!-- Display error messages -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger error-message mt-3"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Pending Users -->
        <h2 class="mt-5">Pending User Approvals</h2>
        <?php if ($pending_users_result->num_rows > 0): ?>
            <form method="post" action="admin.php">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Aadhar No</th>
                            <th>Voter Card No</th>
                            <th>Mobile No</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $pending_users_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['aadhar_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['voter_card_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['mobile_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'pending') { ?>
                                        <button type="submit" name="approve_user_id" value="<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-success btn-sm">Approve</button>
                                    <?php } else { ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        <?php else: ?>
            <p>No pending users.</p>
        <?php endif; ?>

        <!-- Approved Users -->
        <h2 class="mt-5">Approved Users</h2>
        <?php if ($approved_users_result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Aadhar No</th>
                        <th>Voter Card No</th>
                        <th>Mobile No</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $approved_users_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['aadhar_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['voter_card_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobile_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No approved users.</p>
        <?php endif; ?>

        <!-- List of candidates -->
        <h2 class="mt-5">Candidates List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Votes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $candidates_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['votes']); ?></td>
                        <td>
                            <a href="admin.php?delete=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this candidate?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
