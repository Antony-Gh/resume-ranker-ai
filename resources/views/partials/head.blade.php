<!-- Basic Meta Tags -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="keywords"
    content="Resume Ranker AI, Resume Analyzer, AI Resume Review, Job Application Optimization, AI Hiring Tool, Resume Matching, ATS Resume Tool, Resume Scoring, Career Tools, HR Tech, AI Recruitment, Job Matching AI" />

<!-- Icons -->
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}" />
<link rel="icon" type="image/webp" sizes="32x32" href="{{ asset('images/logo.webp') }}" />
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

<title>@yield('title', 'Resume Ranker AI')</title>

<meta name="theme-color" content="#000000" />
<meta name="description"
    content="Resume Ranker AI is an intelligent tool that analyzes and ranks resumes based on job descriptions using AI. Upload resumes, get instant insights, and optimize your hiring process effortlessly." />
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base-url" content="{{ url('/') }}">

<!-- Microsoft Meta Tags -->
<meta name="msapplication-TileColor" content="#000000" />

<!-- Open Graph Meta Tags for Social Media -->
<meta property="og:title" content="Resume Ranker AI - Intelligent Resume Analysis and Ranking Tool" />
<meta property="og:description"
    content="Boost your hiring decisions with Resume Ranker AI. Upload resumes, compare them against job descriptions, and find the best fit using artificial intelligence." />
<meta property="og:image" content="{{ asset('images/og-image.jpg') }}" />
<meta property="og:image:type" content="image/jpeg" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:alt" content="Resume Ranker AI Preview" />
<meta property="og:url" content="{{ url('/') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="Resume Ranker AI" />

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="Resume Ranker AI - AI Resume Matching Tool" />
<meta name="twitter:description"
    content="Streamline hiring with AI-powered resume analysis. Rank and review resumes efficiently." />
<meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}" />
<meta name="twitter:site" content="@ResumeRankerAI" />

<!-- Cache Control -->
<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="expires" content="0" />

<!-- Stylesheets -->
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<link rel="stylesheet" href="{{ mix('css/Font.css') }}">
<link rel="stylesheet" href="{{ mix('css/Header.css') }}">
<link rel="stylesheet" href="{{ mix('css/Footer.css') }}">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,400;0,700;1,400;1,700&family=Lato:ital,wght@0,400;0,700;1,400;1,700&family=Montserrat:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.7.0/css/all.css"/>
