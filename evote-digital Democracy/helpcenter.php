<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - Online Election System</title>
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
            <h1>Help Center</h1>
            <div class="section">
                <h2>Frequently Asked Questions (FAQs)</h2>
                <p><strong>Q1: How do I register to vote?</strong></p>
                <p>A1: To register, click on the ‘Sign Up’ button on the home page and fill out the required information.</p>

                <p><strong>Q2: How do I reset my password?</strong></p>
                <p>A2: Click on the ‘Forgot Password’ link on the login page and follow the instructions to reset your password.</p>

                <p><strong>Q3: How can I check if my vote was successfully submitted?</strong></p>
                <p>A3: After submitting your vote, you will receive a confirmation message. You can also log in to your account and view your voting history.</p>
            </div>
            <div class="section">
                <h2>Contact Support</h2>
                <p>If you need further assistance, please contact our support team:</p>
                <ul>
                    <li>Email: support@electionsystem.com</li>
                    <li>Phone: 1-800-123-4567</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <p>&copy; <script>document.write(new Date().getFullYear());</script> Election System. All rights reserved.</p>
    </footer>
</body>
</html>
