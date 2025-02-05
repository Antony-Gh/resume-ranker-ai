<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Ranker</title>
    <style>
        /* Basic Styling -  REPLACE THIS WITH YOUR ACTUAL CSS */
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #f0f0f0;
            /* Example light gray */
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav li {
            margin-left: 20px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            /* Example dark gray */
        }

        .hero {
            padding: 40px;
            display: flex;
            align-items: center;
            /* Vertically center content */
            justify-content: space-between;
            /* Distribute space between elements */
        }

        .hero-content {
            flex: 1;
            /* Take up available space */
        }

        .hero-image {
            flex: 1;
            /* Take up available space */
            text-align: center;
            /* Center the image */
        }

        .hero-image img {
            max-width: 100%;
            /* Make image responsive */
            height: auto;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            /* Example blue */
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">
            <img src="your-logo.png" alt="ResuRank Logo" width="100">
        </div>
        <nav>
            <ul>
                <li><a href="#">About</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Pricing</a></li>
                <li><a href="#">How It Works</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Sign In</a></li>
                <li><a href="#" class="btn">Get Started for Free</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>AI-Powered Resume Ranking Tool!</h1>
            <p>ResuRanker AI is an advanced tool that analyzes resumes against job descriptions using AI. It delivers
                ranked insights, streamlines candidate evaluation, and generates detailed reports for efficient hiring
                decisions.</p>

            <a href="#" class="btn">Rank Your Resume</a>
            <a href="#" class="btn">Take a Subscription</a>

        </div>
        <div class="hero-image">
            <img src="your-hero-image.png" alt="Hero Image">
        </div>
    </section>

</body>

</html>
