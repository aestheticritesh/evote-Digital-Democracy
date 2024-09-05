<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $image_file = $_FILES['profile_image'];
    $image_name = basename($image_file['name']);
    $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($image_ext, $allowed_ext)) {
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . $image_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($image_file['tmp_name'], $upload_file)) {
            // Fetch current profile image to delete it later if it's not the default one
            $stmt = $conn->prepare("SELECT profile_image FROM users WHERE id=?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $current_profile_image = $user['profile_image'];

            // Update the profile image in the database
            $stmt = $conn->prepare("UPDATE users SET profile_image=? WHERE id=?");
            $stmt->bind_param("si", $image_name, $user_id);
            $stmt->execute();

            // Delete the old profile image from the server if it's not the default one
            if ($current_profile_image != 'default-profile.jpg' && file_exists($upload_dir . $current_profile_image)) {
                unlink($upload_dir . $current_profile_image);
            }

            header("Location: vote.php");  // Redirect to profile page after update
            exit();
        } else {
            $error_message = "Failed to move the uploaded file.";
        }
    } else {
        $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }

    // Redirect back to profile page with error message
    header("Location: profile.php?error=" . urlencode($error_message));
    exit();
} else {
    header("Location: vote.php");
    exit();
}
?>
