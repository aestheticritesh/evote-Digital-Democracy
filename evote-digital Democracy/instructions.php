<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Instructions - Online Election System</title>
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3); /* Faded overlay */
            z-index: -1;
        }
        .content {
            background: rgba(255, 255, 255, 0.9); /* White background with transparency */
            padding: 40px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
        h1, h2 {
            font-family: 'Georgia', serif; /* Update to Georgia font for headers */
            color: #2c3e50;
        }
        p {
            font-family: 'Verdana', sans-serif; /* Update to Verdana font for paragraphs */
            font-size: 18px;
            line-height: 1.6;
        }
        .section {
            margin: 30px 0;
        }
        .footer {
            background: #2c3e50;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Background Overlay -->
    <div class="background-overlay"></div>
    
    <div class="container mt-5">
        <div class="content">
            <h1>Voting Instructions</h1>
            <div class="section">
                <h2>Step 1: Login</h2>
                <p>Log in to your account using your registered email and password. If you don’t have an account, you can register by clicking on the ‘Sign Up’ button.</p>
            </div>
            <div class="section">
                <h2>Step 2: Verify Your Identity</h2>
                <p>We take your security seriously. You will need to verify your identity using the OTP sent to your registered email or phone number.</p>
            </div>
            <div class="section">
                <h2>Step 3: Cast Your Vote</h2>
                <p>Once logged in, you will be directed to the voting page. Review the ballot and make your selections. Double-check your choices before submitting.</p>
            </div>
            <div class="section">
                <h2>Step 4: Submit Your Vote</h2>
                <p>After making your selections, click the ‘Submit’ button to cast your vote. You will receive a confirmation message once your vote is successfully submitted.</p>
            </div>
            <div class="section">
                <h2>Need Help?</h2>
                <p>If you encounter any issues, please visit our <a href="helpcenter.php">Help Center</a> or contact our support team at [support email/phone number].</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <p>&copy; <script>document.write(new Date().getFullYear());</script> Election System. All rights reserved.</p>
    </footer>
</body>
</html>
