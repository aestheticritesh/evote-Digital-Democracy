<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Online Election System</title>
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            <h1>Feedback</h1>
            <?php if (isset($_POST['submit'])): ?>
                <div id="thank-you-message">
                    <p>Thank you for your feedback!</p>
                </div>
            <?php else: ?>
                <form action="feedback.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="feedback">Feedback</label>
                        <textarea class="form-control" id="feedback" name="feedback" rows="5" required></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <p>&copy; <script>document.write(new Date().getFullYear());</script> Election System. All rights reserved.</p>
    </footer>
</body>
</html>
