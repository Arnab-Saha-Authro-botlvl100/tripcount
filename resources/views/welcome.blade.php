<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="{{ url('/image/favicon.png') }}">
    <title>TripCount</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Transparent navbar */
        .navbar {
            background: transparent;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: rgba(240, 248, 255, 0.703);
            backdrop-filter: blur(10px);
            /* Adds the blur effect */
            -webkit-backdrop-filter: blur(10px);
            /* For Safari support */
        }

        .navbar-nav .nav-link {
            color: #000;
            font-weight: 500;
            margin-right: 15px;
        }

        .navbar-nav .nav-link:hover {
            color: #007bff;
        }

        .btn-register {
            background-color: #007bff;
            color: #fff;
            border-radius: 20px;
            padding: 6px 18px;
        }

        .hero {
            background: url('{{ url('/image/hero_bg.png') }}') no-repeat center, rgba(255, 255, 255, 0.3);
            background-size: 80% auto;
            padding: 120px 20px 90px;
            position: relative;
            background-position: 40rem 0px;
        }

        .hero h1 {
            font-size: 3rem;
            /* Default desktop size */
        }

        .hero p {
            font-size: 1.2rem;
            /* Default desktop size */
            line-height: 1.6;
        }

        .hero-container {
            padding: 2rem 0;
        }

        .hero-title {
            font-size: 2.2rem;
            line-height: 1.2;
            font-weight: 700;
        }

        .hero-text {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
        }

        .btn-get-started {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 50px;
            background: #0066ff;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .hero-image {
            max-height: 400px;
            width: auto;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .btn-get-started:hover {
            background-color: #0056b3;
            color: white;
        }


        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.8rem;
                text-align: center;
            }

            .hero-text {
                font-size: 1rem;
                text-align: center;
            }

            .btn-get-started {
                display: block;
                margin: 0 auto;
                max-width: 200px;
                text-align: center;
            }

            .hero-image {
                max-height: 300px;
                margin-bottom: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.5rem;
            }

            .hero-text {
                font-size: 0.9rem;
            }

            .hero-image {
                max-height: 250px;
            }
        }

        /* Large Tablet (768px - 1000px) */
        @media (max-width: 1000px) {
            .hero {
                background-size: cover;
                background-position: center 0rem;
                padding: 80px 20px 60px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
                color: #fff;
            }
        }

        /* Small Tablet (below 768px) */
        @media (max-width: 767.98px) {
            .hero {
                background-size: cover;
            }

            .hero h1 {
                font-size: 1.3rem;
            }

            .hero p {
                font-size: 0.9rem;
            }
        }

        /* Mobile (480px and below) */
        @media (max-width: 480px) {
            .hero {
                background-size: cover;
                background-position: center;
                padding: 40px 15px 30px;
            }

            .hero h1 {
                font-size: 1.5rem;
            }

            .hero p {
                font-size: 0.8rem;
                line-height: 1.5;
            }
        }


        .foursquare-section {
            background-color: var(--background-color);
        }

        .social-brands .badge {
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .social-brands .badge:hover {
            background-color: #e9ecef !important;
            transform: translateY(-2px);
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: rgba(184, 218, 255, 0.644);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-link {
            color: #0d6efd;
            text-decoration: none;
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            background-color: rgba(13, 110, 253, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .feature-card:hover .icon-wrapper {
            background-color: rgba(13, 110, 253, 0.2);
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        .featured-services {
            position: relative;
            padding: 5rem 0;
            /* color: white; */
        }

        .featured-services {
            background:
                linear-gradient(rgba(73, 85, 255, 0.889),
                    rgba(31, 50, 195, 0.679)),
                url('/image/feature_bg.png') no-repeat center center;
            background-size: cover;
            /* min-height: 100vh; or your preferred height */
            /* width: 100%; */
        }

        .services-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            /* max-width: 900px; */
            margin: 0 auto;
        }

        .service-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 50px;
            background: rgba(200, 235, 243, 0.841);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .service-item:hover {
            background: rgba(255, 255, 255, 0.538);
            transform: translateY(-3px);
        }

        .service-icon {
            font-size: 1.2rem;
            color: #4dabf7;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .services-list {
                gap: 1rem;
            }

            .service-item {
                padding: 0.8rem 1.2rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .services-list {
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }

            .service-item {
                width: 80%;
                justify-content: center;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            h1.display-4 {
                font-size: 2.5rem;
            }

            .feature-card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .social-brands {
                gap: 0.5rem;
            }

            .social-brands .badge {
                font-size: 0.8rem;
                padding: 0.5rem !important;
            }
        }

        .product-section {
            background-color: #f8f9fa;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .section-divider {
            width: 80px;
            height: 3px;
            background-color: #3498db;
            margin-top: 1rem;
        }

        .image-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            background: white;
        }

        .image-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }

        .card-body {
            padding: 20px;
        }

        .card-body h3 {
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
            color: #2c3e50;
        }

        .card-body p {
            color: #7f8c8d;
            margin-bottom: 1.2rem;
            font-size: 0.95rem;
        }

        .btn-link {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .btn-link:hover {
            color: #2980b9;
        }

        .btn-link::after {
            content: '→';
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .btn-link:hover::after {
            transform: translateX(3px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-image {
                height: 150px;
            }

            .card-body {
                padding: 15px;
            }

            .card-body h3 {
                font-size: 1.3rem;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }

            .product-card {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .section-title {
                font-size: 1.8rem;
            }

            .product-name {
                font-size: 1.3rem;
            }
        }

        .about-contact-section {
            padding: 5rem;
            /* background-color: #f8f9fa; */
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: #3498db;
        }

        .lead-text {
            font-size: 1.2rem;
            color: #7f8c8d;
            line-height: 1.6;
        }

        .about-title {
            font-size: 1.8rem;
            color: #3498db;
            font-weight: 600;
        }

        .about-text {
            color: #555;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .about-image-wrapper {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .about-image {
            background-size: cover;
            width: 80%;
            transition: transform 0.5s ease;
        }

        .about-image:hover {
            transform: scale(1.03);
        }

        .cta-link {
            display: inline-block;
            color: #3498db;
            font-weight: 600;
            text-decoration: none;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .cta-link:hover {
            color: #2980b9;
            transform: translateX(5px);
        }

        .cta-link::after {
            content: '→';
            margin-left: 5px;
            transition: margin-left 0.3s ease;
        }

        .cta-link:hover::after {
            margin-left: 8px;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .content-wrapper {
                padding-right: 0;
                margin-bottom: 3rem;
            }

            .section-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }

            .about-title {
                font-size: 1.6rem;
            }
        }

        .tripcount-footer {
            background-color: #a3cdf75e;
            color: #000000;
            padding: 3rem 0 1rem;
            font-family: 'Arial', sans-serif;
        }

        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
        }

        .footer-tagline {
            color: #828283;
            line-height: 1.6;
        }

        .footer-heading {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #666666;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: #3498db;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #919496;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #3498db;
        }

        .footer-contact {
            list-style: none;
            padding: 0;
        }

        .footer-contact li {
            margin-bottom: 1rem;
            color: #919496;
            display: flex;
            align-items: flex-start;
            line-height: 1.5;
        }

        .footer-contact i {
            margin-right: 10px;
            color: #3498db;
            margin-top: 3px;
        }

        .newsletter-form .form-control {
            background: rgba(255, 255, 255, 0.712);
            border: none;
            color: #fff;
            margin-bottom: 1rem;
        }

        .newsletter-form .form-control::placeholder {
            color: #bdc3c7;
        }

        .newsletter-form .btn {
            width: 100%;
        }

        .newsletter-note {
            color: #bdc3c7;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
        }

        .copyright {
            color: #bdc3c7;
            margin-bottom: 0;
        }

        .policy-link {
            color: #bdc3c7;
            text-decoration: none;
            margin-left: 1rem;
            transition: color 0.3s ease;
        }

        .policy-link:hover {
            color: #3498db;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .footer-logo {
                font-size: 1.5rem;
            }

            .footer-heading {
                margin-top: 1.5rem;
            }

            .policy-link {
                display: block;
                margin: 0.5rem 0;
            }
        }
    </style>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>

    <script>
        document.querySelectorAll('.navbar a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                const position = target.offsetTop - navbarHeight;

                window.scrollTo({
                    top: position,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-5">
            <a class="navbar-brand fw-bold" href="#">
                <img src="{{ url('/image/tripcount.png') }}" alt="TripCount Logo" height="35">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-register" href="{{route('register')}}">Register Now →</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container-fluid hero-container">
            <div class="row align-items-center g-0 g-md-5">
                <!-- Text Column - Order changes on mobile -->
                <div class="col-lg-6 col-md-12 order-2 order-md-1 px-4 px-md-5 py-4">
                    <h1 class="hero-title mb-3 mb-md-4">Best Accounting<br>Travel Management with us</h1>
                    <p class="hero-text mb-3 mb-md-4">Our primary care services focus on your mental health, wellness,
                        and treatment of mind illnesses.</p>
                    <a href="#" class="btn btn-primary btn-get-started">Get Started →</a>
                </div>

                <!-- Image Column -->
                <div class="col-lg-6 col-md-12 order-1 order-md-2">
                    <img src="{{ url('/image/hero.jpg') }}" alt="Dashboard Screenshot" class="img-fluid hero-image">
                </div>
            </div>
        </div>
    </section>

    <section class="foursquare-section py-5">
        <div class="container-fluid">
            <!-- Header with Social Brands -->
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h1 class="display-6 mb-4"><b>TRUSTED BY</b></h1>
                    <div class="social-brands d-flex flex-wrap justify-content-evenly gap-3">
                        <span class="badge bg-light text-dark p-2">tumblr</span>
                        <span class="badge bg-light text-dark p-2">Pinterest</span>
                        <span class="badge bg-light text-dark p-2">Turfitch</span>
                        <span class="badge bg-light text-dark p-2">Behance</span>
                        <span class="badge bg-light text-dark p-2">BeReal.</span>
                        <span class="badge bg-light text-dark p-2">facebook</span>
                    </div>
                </div>
            </div>

            <!-- Why Choose Us -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-4 mb-4"><strong>Why Choose US?</strong></h2>
                    <p class="lead">Our solutions are designed to save time, reduce errors, and help you grow with
                        confidence.</p>
                    <p>Built with purpose. Trusted by professionals.</p>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="row g-4">
                <!-- Cloud Based ERP -->
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4 border rounded-3">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-cloud fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Cloud Based ERP</h3>
                        <p>Cloud Based ERP system that can be used anywhere, system that can be used anywhere</p>
                        <a href="#" class="btn btn-link ps-0">Read more →</a>
                    </div>
                </div>

                <!-- Easy To Use -->
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4 border rounded-3">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-hand-pointer fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Easy To Use</h3>
                        <p>Easy to use even for the newbies in accounts, en for the newbies in accounts</p>
                        <a href="#" class="btn btn-link ps-0">Read more →</a>
                    </div>
                </div>

                <!-- Multi Branch -->
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4 border rounded-3">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-code-branch fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Multi Branch</h3>
                        <p>Easy to manage multiple branches in single admin. Easy to manage multiple branches.</p>
                        <a href="#" class="btn btn-link ps-0">Read more →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="featured-services" id="services">
        <div class="container-fluid py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3 text-white">Featured Service</h2>
                <p class="lead text-white mb-1">Our solutions are designed to save time, reduce errors, and help you
                    grow with confidence</p>
                {{-- <p class="text-white-50">Built with purpose. Trusted by professionals.</p> --}}
            </div>

            <!-- Services List - Minimalist Layout -->
            <div class="services-list">
                <div class="service-item">
                    <i class="fas fa-ticket-alt service-icon"></i>
                    <span>Ticket Invoice</span>
                </div>

                <div class="service-item">
                    <i class="fas fa-file-invoice-dollar service-icon"></i>
                    <span>Visa Invoice</span>
                </div>

                <div class="service-item">
                    <i class="fas fa-bell service-icon"></i>
                    <span>Due Reminder</span>
                </div>

                <div class="service-item">
                    <i class="fas fa-hand-holding-usd service-icon"></i>
                    <span>Receive</span>
                </div>

                <div class="service-item">
                    <i class="fas fa-chart-line service-icon"></i>
                    <span>Profit / Loss</span>
                </div>

                <div class="service-item">
                    <i class="fas fa-undo service-icon"></i>
                    <span>Refund</span>
                </div>

                <div class="service-item">
                    <i class="fas fa-credit-card service-icon"></i>
                    <span>Payment</span>
                </div>

                <div class="service-item">
                    <i class="fas fa-book service-icon"></i>
                    <span>General Ledger</span>
                </div>
            </div>
        </div>
    </section>

    <section class="product-section py-5" id="products">
        <div class="container">
            <!-- Header -->
            <div class="text-center mb-5">
                <h2 class="section-title2 mb-3">Product Belongs</h2>
                <div class="section-divider mx-auto"></div>
            </div>

            <!-- Product Grid -->
            <div class="row g-4">
                <!-- PERFECT -->
                <div class="col-md-6 col-lg-4">
                    <div class="image-card">
                        <div class="card-image"
                            style="background-image: url('https://via.placeholder.com/400x250?text=PERFECT');"></div>
                        <div class="card-body">
                            <h3>PERFECT</h3>
                            <p>Easy to Get Ready Manpower Files for your Agency</p>
                            <a href="#" class="btn-link">See more →</a>
                        </div>
                    </div>
                </div>

                <!-- MANDOWER -->
                <div class="col-md-6 col-lg-4">
                    <div class="image-card">
                        <div class="card-image"
                            style="background-image: url('https://via.placeholder.com/400x250?text=MANDOWER');"></div>
                        <div class="card-body">
                            <h3>MANDOWER</h3>
                            <p>Easy to Get Ready Manpower Files for your Agency</p>
                            <a href="#" class="btn-link">See more →</a>
                        </div>
                    </div>
                </div>

                <!-- foree -->
                <div class="col-md-6 col-lg-4">
                    <div class="image-card">
                        <div class="card-image"
                            style="background-image: url('https://via.placeholder.com/400x250?text=foree');"></div>
                        <div class="card-body">
                            <h3>foree</h3>
                            <p>Get Ready KSA 4 Page Embassy Files Automatic</p>
                            <a href="#" class="btn-link">See more →</a>
                        </div>
                    </div>
                </div>

                <!-- KSA Visa Form -->
                <div class="col-md-6 col-lg-4">
                    <div class="image-card">
                        <div class="card-image"
                            style="background-image: url('https://via.placeholder.com/400x250?text=KSA+Visa');"></div>
                        <div class="card-body">
                            <h3>KSA Visa Form</h3>
                            <p>BMET File Solution</p>
                            <a href="#" class="btn-link">See more →</a>
                        </div>
                    </div>
                </div>

                <!-- BMET File Solution -->
                <div class="col-md-6 col-lg-4">
                    <div class="image-card">
                        <div class="card-image"
                            style="background-image: url('https://via.placeholder.com/400x250?text=BMET');"></div>
                        <div class="card-body">
                            <h3>BMET File Solution</h3>
                            <p>Agency Website</p>
                            <a href="#" class="btn-link">See more →</a>
                        </div>
                    </div>
                </div>

                <!-- Agency Website -->
                <div class="col-md-6 col-lg-4">
                    <div class="image-card">
                        <div class="card-image"
                            style="background-image: url('https://via.placeholder.com/400x250?text=Agency');"></div>
                        <div class="card-body">
                            <h3>Agency Website</h3>
                            <p>Complete solution for your agency</p>
                            <a href="#" class="btn-link">See more →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-contact-section" id="about">
        <div class="">
            <div class="row align-items-center mx-4 g-2">

                <!-- Image Column -->
                <div class="col-lg-6 order-1 order-md-2">
                    <div class="">
                        <img src="{{ url('/image/about_us.png') }}" alt="About TripCount"
                            class="img-fluid about-image">
                    </div>
                </div>
                <!-- Text Content -->
                <div class="col-lg-6 order-2 order-md-1">
                    <div class="content-wrapper pe-lg-5">
                        <h2 class="section-title mb-4">Get In Touch Today</h2>
                        <p class="lead-text mb-4">Proactively deliver seamless core competencies with scalable.
                            Completely fabricate transparent paradigms.</p>

                        <div class="about-content">
                            <h3 class="about-title mb-3">About Us</h3>
                            <p class="about-text">TripCount is a leading business solution built exclusively for travel
                                agencies. We specialize in providing powerful accounting packages, responsive websites,
                                and web-based CRM applications—all tailored to simplify your operations and fuel your
                                growth.</p>
                            <p class="about-text">From managing invoices to building your agency's online presence, we
                                bring everything under one roof.</p>
                            <p class="about-text mb-4">Our mission is simple: to make your travel business smarter,
                                faster, and more profitable—without the complexity.</p>
                            <a href="#" class="cta-link">See more →</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <footer class="tripcount-footer" id="contact">
        <div class="container">
            <div class="row">
                <!-- Brand Info -->
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />

                        <p class="footer-tagline">Streamlining your travel accounting with ease and precision</p>
                    </div>
                </div>

                <!-- Useful Links -->
                <div class="col-md-4 col-lg-2 mb-4">
                    <h3 class="footer-heading">Useful Links</h3>
                    <ul class="footer-links">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Features</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">About Us</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-md-4 col-lg-3 mb-4">
                    <h3 class="footer-heading">Contact Us</h3>
                    <ul class="footer-contact">
                        <li><i class="fas fa-envelope"></i> contact.sallusoft@gmail.com</li>
                        <li><i class="fas fa-phone"></i> +88 01812215760</li>
                        <li><i class="fas fa-map-marker-alt"></i> [29], Jomidar Palace, Inner circular road, Motijheel,
                            Lift–7 Dhaka–100</li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="col-md-4 col-lg-3 mb-4">
                    <h3 class="footer-heading">Subscribe to our newsletter</h3>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Your email address" class="form-control mb-2">
                        <button type="submit" class="btn btn-primary">Get Started</button>
                    </form>
                    <p class="newsletter-note">Stay Updated with our latest news and offers.</p>
                </div>
            </div>

            <!-- Copyright -->
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <p class="copyright">©2025 Tripcount All right reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="policy-link">Privacy Policy</a>
                        <a href="#" class="policy-link">Terms & Conditions</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
