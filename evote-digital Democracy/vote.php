<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include 'db.php';

// Check if user has already voted
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT voted, username, email, profile_image FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$already_voted = $user['voted'];
$username = htmlspecialchars($user['username']);
$email = htmlspecialchars($user['email']);
$profile_image = htmlspecialchars($user['profile_image']) ?: 'default-profile.jpg';

// Handle voting form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['candidate_id'])) {
    if (!$already_voted) {
        $candidate_id = $_POST['candidate_id'];

        // Update candidate votes
        $stmt = $conn->prepare("UPDATE candidates SET votes = votes + 1 WHERE id=?");
        $stmt->bind_param("i", $candidate_id);
        $stmt->execute();

        // Mark user as voted
        $stmt = $conn->prepare("UPDATE users SET voted = TRUE WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Set a session variable to indicate successful vote (optional)
        $_SESSION['vote_success'] = true;

        // Redirect to prevent form resubmission on refresh
        header("Location: vote.php");
        exit();
    }
}

// If user has already voted and tries to access vote.php, show the message
if ($already_voted && !isset($_SESSION['vote_success'])) {
    $already_voted_message = true;
} else {
    $already_voted_message = false;
}

// Fetch all candidates
$stmt = $conn->prepare("SELECT * FROM candidates");
$stmt->execute();
$candidates_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Your Favourite Candidate!!</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }

        .background-fade {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            z-index: -1; /* Make sure the fade effect is behind the content */
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding: 20px;
            position: fixed;
            width: 250px;
        }

        .sidebar img {
            width: 100%;
            height: auto;
            border-radius: 50%;
        }

        .sidebar h4 {
            margin-top: 20px;
            font-size: 1.2rem;
        }

        .sidebar p {
            font-size: 0.9rem;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        .btn-logout {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-logout:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .alert-success {
            font-size: 1.5rem;
            font-weight: bold;
            padding: 30px;
            text-align: center;
        }

        .table {
            background-color: white;
            border-radius: 8px;
            margin: 0 auto; /* Center the table */
            max-width: 800px; /* Set a max width */
        }

        h1 {
            font-size: 5rem;
            font-family: 'Lucida Handwriting', cursive;
            color: #ffffff;
        }

        .candidate-info {
            display: flex;
            align-items: center;
        }

        .candidate-info img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-right: 25px;
        }

        .vote-button {
            display: flex;
            justify-content: center;
        }

        .vote-button button {
            font-size: 1.25rem; /* Increase font size */
            padding: 10px 20px; /* Increase padding */
            border-radius: 5px; /* Add border radius for rounded corners */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.nav-button').click(function(e) {
                e.preventDefault();
                const target = $(this).data('target');
                $('.main-content').load(target);
            });
        });
    </script>
</head>
<body>
<div class="background-fade"></div>
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="uploads/<?php echo $profile_image; ?>" alt="Profile Photo">
        <h4><?php echo $username; ?></h4>
        <p>Email: <?php echo $email; ?></p>
        <p><a href="#" class="btn btn-light btn-sm nav-button" data-target="profile.php">View Profile</a></p>
        
    </div>

    <div class="main-content">
        <h1 class="text-center">Vote - Online Election System</h1>

        <div class="text-right mb-3">
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>

        <?php if (isset($_SESSION['vote_success']) && $_SESSION['vote_success']): ?>
            <div class="alert alert-success" role="alert" style="font-size: 1.25rem; font-weight: bold;">
                Vote cast successfully!
            </div>
            <?php unset($_SESSION['vote_success']); ?>
        <?php endif; ?>

        <?php if ($already_voted_message): ?>
            <div class="alert alert-info" role="alert" style="font-size: 1.25rem; font-weight: bold;">
                You have already voted!!!
            </div>
        <?php endif; ?>

        <?php if (!$already_voted): ?>
            <form method="post" action="vote.php">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Candidate</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($candidate = $candidates_result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="candidate-info">
                                        <img src="candidate_images/<?php echo htmlspecialchars($candidate['image']); ?>" alt="Candidate Picture">
                                        <h3><?php echo htmlspecialchars($candidate['name']); ?></h3>
                                    </div>
                                </td>
                                <td class="vote-button">
                                    <button type="submit" name="candidate_id" value="<?php echo $candidate['id']; ?>" class="btn btn-primary">Vote</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

