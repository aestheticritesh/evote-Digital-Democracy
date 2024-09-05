<?php
include 'db.php';
session_start();

$registration_successful = false;
$error_message = "";

// Define the directory for uploaded images
$upload_dir = 'uploads/';

// Ensure the upload directory exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $aadhar_no = $_POST['aadhar_no'];
    $voter_card_no = $_POST['voter_card_no'];
    $mobile_no = $_POST['mobile_no'];
    $email = $_POST['email'];
    
    // Initialize variables for image upload
    $image_path = "";
    
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $image_file = $_FILES['profile_image'];
        $image_name = basename($image_file['name']);
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate image extension
        if (!in_array($image_ext, $allowed_ext)) {
            $error_message = "Invalid image file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        } else {
            $image_path = uniqid() . '.' . $image_ext;
            if (!move_uploaded_file($image_file['tmp_name'], $image_path)) {
                $error_message = "Failed to upload image.";
            }
        }
    }

    // Validate password complexity
    if (!preg_match("/^(?=.*\d{4,})(?=.*[A-Z]{2,}).{8,}$/", $password)) {
        $error_message = "Password must be at least 8 characters with minimum 4 digits and 2 uppercase letters.";
    }

    // Validate Aadhar number length and format
    if (strlen($aadhar_no) !== 12 || !ctype_digit($aadhar_no)) {
        $error_message = "Aadhar number must be exactly 12 digits.";
    }

    // Validate mobile number length and format
    if (strlen($mobile_no) !== 10 || !ctype_digit($mobile_no)) {
        $error_message = "Mobile number must be exactly 10 digits.";
    }

    if (empty($error_message)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL statement with placeholders for parameters
        $stmt = $conn->prepare("INSERT INTO users (username, password, aadhar_no, voter_card_no, mobile_no, email, profile_image, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sssssss", $username, $hashed_password, $aadhar_no, $voter_card_no, $mobile_no, $email, $image_path);

        // Execute the prepared statement
        if ($stmt->execute()) {
            $registration_successful = true;
            // Close the statement
            $stmt->close();

            // Redirect to login page after 2 seconds
            header("refresh:2;url=login.php");
            exit; // Ensure no further code is executed
        } else {
            if ($stmt->errno == 1062) { // MySQL error code for duplicate entry
                $error_message = "Duplicate entry";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Registration</title>
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
            filter: brightness(40%); /* Adjust brightness to create a faded effect */
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            max-width: 700px;
            width: 90%;
            z-index: 1;
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

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .alert-danger {
            font-size: 1.25rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Registration Form</h1>
        <!-- Logout button -->
        <div class="text-right mb-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <?php if ($registration_successful): ?>
            <div class="alert alert-success" role="alert">
                Registration completed successfully! Redirecting to login page...
            </div>
        <?php endif; ?>
        <form method="post" action="registration.php" enctype="multipart/form-data">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password (at least 8 characters with minimum 4 digits and 2 uppercase letters)</label>
                <input type="password" name="password" class="form-control" id="password" pattern="(?=.*\d{4,})(?=.*[A-Z]{2,}).{8,}" required>
            </div>
            <div class="form-group">
                <label for="aadhar_no">Aadhar Number (12 digits)</label>
                <input type="text" name="aadhar_no" class="form-control" id="aadhar_no" pattern="[0-9]{12}" value="<?php echo htmlspecialchars($_POST['aadhar_no'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label for="voter_card_no">Voter Card Number</label>
                <input type="text" name="voter_card_no" class="form-control" id="voter_card_no" value="<?php echo htmlspecialchars($_POST['voter_card_no'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label for="mobile_no">Mobile Number (10 digits)</label>
                <input type="text" name="mobile_no" class="form-control" id="mobile_no" pattern="[0-9]{10}" value="<?php echo htmlspecialchars($_POST['mobile_no'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_image">Profile Image (JPG, JPEG, PNG, GIF)</label>
                <input type="file" name="profile_image" class="form-control-file" id="profile_image" accept=".jpg, .jpeg, .png, .gif">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>
