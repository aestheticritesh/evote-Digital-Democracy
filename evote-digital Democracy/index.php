<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Online Election System</title>
    <style>
        body {
            background-color: black;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 10px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2.5rem;
            color: orangered;
        }
        .btn-container {
            display: flex;
            gap: 20px;
        }
        .btn-login {
            background-color: orange;
            border-color: orange;
            color: white;
        }
        .btn-login:hover {
            background-color: darkorange;
            border-color: darkorange;
        }
        .btn-admin {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
        .btn-admin:hover {
            background-color: #0056b3;
            border-color: #004494;
        }
        .hero {
            text-align: center;
            margin-bottom: 50px;
        }
        .hero img {
            max-width: 90%;
            height: auto;
        }
        .hero h2 {
            font-size: 2rem;
            color: orangered;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            color: orangered;
        }
        .social-icons img {
            width: 40px;
            height: 40px;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Online Election System</h1>
            <div class="btn-container">
                <a href="login.php" class="btn btn-login">Login</a>
                <a href="registration.php" class="btn btn-secondary">Register</a>
                <!-- New Admin Login Button -->
                <a href="admin_login.php" class="btn btn-admin">Admin Login</a>
            </div>
        </div>
        <div class="hero">
            <img src="vote1.jpg" alt="Election">
            <h2>Participate in your future. Vote wisely!</h2>
        </div>
        <div class="footer">
            <p>Follow us on</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/ECI/"><img src="fb.png" alt="Facebook"></a>
                <a href="https://www.instagram.com/ecisveep/?hl=en"><img src="i.png" alt="Instagram"></a>
                <a href="https://x.com/ECISVEEP?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor"><img src="X.png" alt="X"></a> <!-- Placeholder for another social media -->
            </div>
        </div>
    </div>
</body>
</html>
