<?php
include 'db.php';
session_start();

$error_message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $voter_card_no = $_POST['voter_card_no'];
    $captcha = $_POST['captcha'];

    // Check if CAPTCHA is correct
    if ($captcha != $_SESSION['captcha']) {
        $error_message = "Invalid CAPTCHA.";
    } else {
        // Prepare SQL statement to check username, password, and voter card number
        $stmt = $conn->prepare("SELECT id, password, status FROM users WHERE username = ? AND voter_card_no = ?");
        $stmt->bind_param("ss", $username, $voter_card_no);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $hashed_password, $status);
        if ($stmt->fetch()) {
            // Check if password is correct
            if (password_verify($password, $hashed_password)) {
                // Check if user is approved
                if ($status === 'approved') {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    header("Location: vote.php"); // Redirect to home page
                    exit();
                } else {
                    $error_message = "Your registration is pending approval.";
                }
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Invalid username or password or voter card number.";
        }
        $stmt->close();
    }
}

// Generate CAPTCHA code
$_SESSION['captcha'] = rand(1000, 9999);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
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
            max-width: 500px;
            width: 100%;
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
    <div class="container">
        <h1 class="text-center">Login</h1>
         <!-- Logout button -->
         <div class="text-right mb-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="form-group">
                <label for="voter_card_no">Voter Card Number</label>
                <input type="text" name="voter_card_no" class="form-control" id="voter_card_no" required>
            </div>
            <div class="form-group">
                <label for="captcha">CAPTCHA: <?php echo $_SESSION['captcha']; ?></label>
                <input type="text" name="captcha" class="form-control" id="captcha" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
