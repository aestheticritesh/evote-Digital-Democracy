<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, profile_image, aadhar_no, pan_card_no, voter_card_no, mobile_no FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = htmlspecialchars($user['username']);
$email = htmlspecialchars($user['email']);
$profile_image = htmlspecialchars($user['profile_image']) ?: 'default-profile.jpg';
$aadhar_no = htmlspecialchars($user['aadhar_no']);
$pan_card_no = htmlspecialchars($user['pan_card_no']);
$voter_card_no = htmlspecialchars($user['voter_card_no']);
$mobile_no = htmlspecialchars($user['mobile_no']);

// Display error message if any
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Include Bootstrap CSS or your preferred styling here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <img src="uploads/<?php echo $profile_image; ?>?<?php echo time(); ?>" alt="Profile Photo" class="img-fluid rounded-circle">
            </div>
            <div class="col-md-8 text-white">
                <h2 class="font-weight-bold"><?php echo $username; ?></h2>
                <p class="font-weight-bold">Email: <?php echo $email; ?></p>
                <p class="font-weight-bold">Aadhar No: <?php echo $aadhar_no; ?></p>
                <p class="font-weight-bold">PAN Card No: <?php echo $pan_card_no; ?></p>
                <p class="font-weight-bold">Voter Card No: <?php echo $voter_card_no; ?></p>
                <p class="font-weight-bold">Mobile No: <?php echo $mobile_no; ?></p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">Edit Profile Picture</button>
            </div>
        </div>
        
        <!-- Modal for editing profile picture -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile Picture</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        <form action="edit_profile.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="profile_image">Select a new profile image:</label>
                                <input type="file" name="profile_image" id="profile_image" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
