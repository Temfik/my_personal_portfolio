<?php
require_once 'log_visitor.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temesgen Fikadu Baysa - Regional IT Supervisor & Team Leader</title>
    <meta name="description" content="Portfolio of Temesgen Fikadu Baysa, an experienced IT Leader specializing in team management, system development, and digital transformation in the banking sector.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FDB813; /* A warmer, more engaging gold/yellow */
            --secondary-color: #000000;
            --text-color: #343a40;
            --light-gray: #f9f9f9;
            --dark-gray: #5a6268;
            --white: #ffffff;
            --border-color: #e9ecef;
            --transition: all 0.3s ease;
            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 8px 15px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #fff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--secondary-color);
        }

        h1 {
            font-size: 2.8rem;
        }

        h2 {
            font-weight: 700;
            font-size: 2.2rem;
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        h3 {
            font-size: 1.8rem;
        }

        h4 {
            font-size: 1.4rem;
        }

        p {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        a:hover {
            color: var(--primary-color);
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: var(--white);
            font-weight: 600;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            font-size: 1rem;
            border: 2px solid var(--primary-color);
        }

        .btn:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline:hover {
            color: var(--white);
            background-color: var(--primary-color);
        }

        /* Header & Navigation */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            box-shadow: var(--shadow-sm);
            z-index: 1000;
            transition: var(--transition);
            padding: 1rem 0;
        }

        header.scrolled {
            padding: 0.5rem 0;
            box-shadow: var(--shadow-md);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .logo span {
            color: var(--primary-color);
        }

        .nav-menu {
            display: flex;
            list-style: none;
        }

        .nav-item {
            margin-left: 2rem;
        }

        .nav-link {
            font-weight: 600;
            position: relative;
            transition: var(--transition);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: var(--transition);
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        .hamburger {
            display: none;
            cursor: pointer;
        }

        .hamburger span {
            display: block;
            width: 25px;
            height: 3px;
            background-color: var(--secondary-color);
            margin: 5px 0;
            transition: var(--transition);
        }

        /* Hero Section */
        #home {
            padding: 120px 5% 80px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, rgba(253, 184, 19, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content h1 {
            font-weight: 700;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease;
        }

        .hero-content h2 {
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            color: var(--dark-gray);
            animation: fadeInUp 1s ease 0.2s;
            animation-fill-mode: both;
        }

        .hero-content p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.4s;
            animation-fill-mode: both;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease 0.6s;
            animation-fill-mode: both;
        }

        .hero-image {
            text-align: center;
            animation: fadeIn 1s ease 0.8s;
            animation-fill-mode: both;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: var(--shadow-lg);
            border: 8px solid var(--white);
        }

        /* Section Styles */
        section {
            padding: 80px 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        /* About Section */
        #about {
            background-color: var(--light-gray);
        }

        .about-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: flex-start;
        }

        .about-content {
            margin-bottom: 2rem;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: var(--primary-color);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -9px;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: var(--primary-color);
            border: 3px solid #fff;
        }

        .timeline-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .timeline-date {
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        /* Leadership Section */
        .leadership-content {
            margin-bottom: 2rem;
        }

        .leadership-values {
            background-color: var(--light-gray);
            padding: 2rem;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .responsibilities {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .responsibility-card {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .responsibility-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .responsibility-card i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        /* Education Section */
        #education {
            background-color: var(--light-gray);
        }

        .education-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .education-card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .education-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background-color: var(--primary-color);
        }

        .education-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: var(--shadow-md);
        }

        .education-degree {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .education-institution {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .education-year {
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        /* Experience Section */
        .experience-container {
            display: grid;
            gap: 2rem;
        }

        .experience-card {
            background-color: var(--light-gray);
            padding: 2rem;
            border-radius: 15px;
            position: relative;
        }

        .experience-card h3 {
            margin-bottom: 0.5rem;
        }

        .experience-company {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .experience-period {
            color: var(--dark-gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .experience-details {
            list-style: none;
        }

        .experience-details li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 0.5rem;
        }

        .experience-details li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-weight: bold;
        }

        /* Skills Section */
        #skills {
            background-color: var(--light-gray);
        }

        .skills-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .skill-card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .skill-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .skill-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .skill-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Projects Section */
        .projects-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .project-card {
            background-color: var(--light-gray);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
        }

        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .project-image-container {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .project-image-slider {
            display: flex;
            transition: transform 0.5s ease;
            height: 100%;
        }

        .project-slide {
            min-width: 100%;
            height: 100%;
        }

        .project-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .project-image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            color: #fff;
            padding: 1rem;
            transform: translateY(100%);
            transition: var(--transition);
        }

        .project-card:hover .project-image-overlay {
            transform: translateY(0);
        }

        .image-counter {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .project-slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            z-index: 3;
            opacity: 0;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            line-height: 1;
        } 
        .project-card:hover .project-slider-nav { opacity: 1; }
        .project-slider-nav:hover { background-color: rgba(0, 0, 0, 0.7); }
        .project-slider-prev { left: 10px; }
        .project-slider-next { right: 10px; }

        
        .featured-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: var(--white);
            color: var(--secondary-color);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            z-index: 2;
        }

        .project-content {
            padding: 1.5rem;
        }

        .project-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        .project-tech {
            color: var(--dark-gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .project-description {
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .project-role {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .project-links {
            display: flex;
            gap: 1rem;
        }

        .project-link {
            color: var(--secondary-color);
            transition: var(--transition);
            font-size: 1.2rem;
        }

        .project-link:hover {
            color: var(--primary-color);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 15px;
            max-width: 1200px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 10;
        }

        .modal-header h3 {
            font-size: 1.5rem;
            color: var(--secondary-color);
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: var(--dark-gray);
            transition: var(--transition);
        }

        .close-modal:hover {
            color: var(--primary-color);
        }

        .modal-body {
            padding: 0;
        }

        .project-gallery {
            position: relative;
            height: 500px;
            background-color: #000;
        }

        .gallery-slider {
            display: flex;
            transition: transform 0.5s ease;
            height: 100%;
        }

        .gallery-slide {
            min-width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-slide img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            padding: 1rem;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.5rem;
        }

        .gallery-nav:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .gallery-prev {
            left: 10px;
        }

        .gallery-next {
            right: 10px;
        }

        .gallery-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: var(--transition);
        }

        .indicator.active {
            background-color: var(--primary-color);
        }

        .gallery-caption {
            position: absolute;
            bottom: 60px;
            left: 0;
            right: 0;
            text-align: center;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 1rem;
            font-size: 0.9rem;
        }

        .project-details {
            padding: 2rem;
        }

        .project-detail-section {
            margin-bottom: 2rem;
        }

        .project-detail-section h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .project-tech-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .tech-tag {
            background-color: var(--light-gray);
            color: var(--secondary-color);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Services Section */
        #services {
            background-color: var(--light-gray);
        }

        .services-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .service-card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .service-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .service-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .service-title {
            font-weight: 700;
            margin-bottom: 1rem;
        }

        /* Documents Section */
        #documents {
            background-color: var(--light-gray);
        }

        .document-categories {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .category-btn {
            padding: 8px 16px;
            background-color: #fff;
            color: var(--secondary-color);
            border: 2px solid var(--primary-color);
            border-radius: 30px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
        }

        .category-btn:hover, .category-btn.active {
            background-color: var(--secondary-color);
            color: var(--white);
            border-color: var(--secondary-color);
        }

        .documents-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .document-card {
            background-color: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }

        .document-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .document-icon {
            padding: 2rem;
            text-align: center;
            background-color: rgba(255, 215, 0, 0.1);
        }

        .document-icon i {
            font-size: 3rem;
            color: var(--primary-color);
        }

        .document-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .document-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .document-category {
            color: var(--dark-gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .document-description {
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .document-actions {
            display: flex;
            gap: 1rem;
        }

        .document-actions .btn {
            flex: 1;
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .document-actions .btn-outline {
            border: 2px solid var(--primary-color);
        }

        /* Contact Section */
        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background-color: var(--light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .contact-form {
            background-color: var(--light-gray);
            padding: 2.5rem;
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }

        /* Footer */
        footer {
            background-color: #111;
            color: #fff;
            padding: 2rem 5%;
            text-align: center;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px; 
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            transition: var(--transition);
        }

        .social-link:hover {
            background-color: var(--white);
            color: var(--secondary-color);
        }

        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .lightbox-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }

        .lightbox-content img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 40px;
            color: #fff;
            font-size: 40px;
            cursor: pointer;
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: var(--secondary-color);
            color: #fff;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(100px);
            opacity: 0;
            transition: var(--transition);
            z-index: 3000;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            background-color: #28a745;
        }

        .toast.error {
            background-color: #dc3545;
        }

        /* Loading Spinner */
        .spinner {
            display: none;
            width: 40px;
            height: 40px;
            margin: 0 auto;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Error Message */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .error-message h3 {
            margin-bottom: 10px;
        }

        .error-message button {
            margin-top: 15px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-container,
            .about-container,
            .contact-container {
                grid-template-columns: 1fr;
            }

            .hero-content {
                text-align: center;
            }

            .hero-image {
                order: -1;
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 768px) {
            body { font-size: 16px; }
            h1 {
                font-size: 2.2rem;
            }

            h2 {
                font-size: 1.8rem;
            }

            .nav-menu {
                position: fixed;
                left: -100%;
                top: 0;
                flex-direction: column;
                background-color: #fff;
                width: 100%;
                text-align: center;
                transition: 0.3s;
                box-shadow: 0 10px 27px rgba(0, 0, 0, 0.05);
                padding: 2rem 0;
                padding-top: 5rem;
            }

            .nav-menu.active {
                left: 0;
            }

            .nav-item {
                margin: 1rem 0;
            }

            .hamburger {
                display: block;
            }

            .hamburger.active span:nth-child(2) {
                opacity: 0;
            }

            .hamburger.active span:nth-child(1) {
                transform: translateY(8px) rotate(45deg);
            }

            .hamburger.active span:nth-child(3) {
                transform: translateY(-8px) rotate(-45deg);
            }

            .project-gallery {
                height: 300px;
            }

            .modal-content {
                width: 95%;
                max-height: 95vh;
            }

            .project-details {
                padding: 1rem;
            }

            .gallery-nav {
                padding: 0.5rem;
                font-size: 1rem;
            }

            .gallery-caption {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .project-image-container {
                height: 200px;
            }

            .hero-buttons {
                justify-content: center;
            }

            .project-gallery {
                height: 250px;
            }

            .gallery-caption {
                display: none;
            }
        }
    </style> 
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">Temesgen<span>.</span></div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="#home" class="nav-link active">Home</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
                <li class="nav-item"><a href="#leadership" class="nav-link">Leadership</a></li>
                <li class="nav-item"><a href="#education" class="nav-link">Education</a></li>
                <li class="nav-item"><a href="#experience" class="nav-link">Experience</a></li>
                <li class="nav-item"><a href="#skills" class="nav-link">Skills</a></li>
                <li class="nav-item"><a href="#projects" class="nav-link">Projects</a></li>
                <li class="nav-item"><a href="#services" class="nav-link">Services</a></li>
                <li class="nav-item"><a href="#documents" class="nav-link">Documents</a></li>
                <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Temesgen Fikadu Baysa</h1>
                <h2>Regional IT Supervisor & Team Leader</h2>
                <p>"Leading Technology, Building Teams, and Driving Digital Transformation in Banking."</p>
                <p>I'm Temesgen Fikadu Baysa, an experienced IT professional and Regional IT Supervisor at Awash Bank. With over 10 years of service, I oversee IT operations across 98 branches, lead a multi-disciplinary IT team, and represent regional IT operations at national level. I'm passionate about integrating technology, leadership, and teamwork to achieve organizational excellence.</p>
                <div class="hero-buttons">
                    <a href="#projects" class="btn">View My Work</a>
                    <a href="#documents" class="btn btn-outline">View Documents</a>
                    <a href="#contact" class="btn btn-outline">Contact Me</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="uploads/mastermemory.jpg" alt="Temesgen Fikadu Baysa">
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="section-title">
            <h2>About Me</h2>
        </div>
        <div class="about-container">
            <div class="about-content">
                <p>I began my journey with Awash Bank as a PC Operator, driven by a passion for technology and service. Over time, I grew through dedication and performance to become one of the bank's first Regional IT Supervisors.</p>
                <p>Today, I serve as the Regional IT Representative for Awash Bank in the Sidama Region, leading technology operations and coordinating IT activities for 98 branches. My role bridges the gap between the bank's headquarters and regional IT teams, ensuring policy alignment, security compliance, and operational efficiency.</p>
                <p>I also serve as an IT Consultant and Advisor for SACCOs, supporting microfinance institutions in digital transformation, system automation, and financial performance monitoring.</p>
            </div>
            <div class="leadership-journey">
                <h3>Leadership Journey</h3>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-title">PC Operator</div>
                        <div class="timeline-date">Starting Point</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-title">Personal Banker</div>
                        <div class="timeline-date">Career Growth</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-title">IT Officer</div>
                        <div class="timeline-date">Technical Transition</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-title">Senior IT Officer</div>
                        <div class="timeline-date">Advancement</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-title">Regional IT Supervisor</div>
                        <div class="timeline-date">2013 – Present: Over 10 years of continuous IT service in banking</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section id="leadership">
        <div class="section-title">
            <h2>Leadership & Team Leader Role</h2>
        </div>
        <div class="leadership-content">
            <p>As Regional IT Supervisor and Team Leader, I manage and mentor a diverse IT team responsible for supporting 98 branches within the Sidama Region. My leadership role involves strategic coordination, technical guidance, and performance oversight to ensure smooth IT operations and innovation.</p>
            <p>I serve as the Regional IT Representative to Awash Bank's head office — acting as the communication bridge for national IT projects, policy updates, and strategic implementations at the regional level.</p>
        </div>
        
        <h3>Key Leadership Responsibilities</h3>
        <div class="responsibilities">
            <div class="responsibility-card">
                <i class="fas fa-users"></i>
                <h4>Team Management</h4>
                <p>Lead, coordinate, and evaluate multiple IT officers and branch-level IT contacts.</p>
            </div>
            <div class="responsibility-card">
                <i class="fas fa-project-diagram"></i>
                <h4>Project Facilitation</h4>
                <p>Facilitate regional IT projects, infrastructure deployments, and system upgrades.</p>
            </div>
            <div class="responsibility-card">
                <i class="fas fa-chart-line"></i>
                <h4>Performance Tracking</h4>
                <p>Manage performance tracking and project supervision for all regional IT staff.</p>
            </div>
            <div class="responsibility-card">
                <i class="fas fa-exchange-alt"></i>
                <h4>Communication Bridge</h4>
                <p>Serve as a liaison between the Regional Office and Head Office IT departments.</p>
            </div>
            <div class="responsibility-card">
                <i class="fas fa-chalkboard-teacher"></i>
                <h4>Capacity Building</h4>
                <p>Conduct capacity building, mentoring, and coaching sessions for team members.</p>
            </div>
            <div class="responsibility-card">
                <i class="fas fa-lightbulb"></i>
                <h4>Innovation</h4>
                <p>Initiate new systems and digital solutions to improve efficiency and reporting.</p>
            </div>
        </div>
        
        <div class="leadership-values">
            <h3>Leadership Values</h3>
            <p>"My leadership philosophy is built on collaboration, accountability, and innovation. I believe in empowering my team to grow, aligning every action with the bank's mission, and fostering a culture of learning and excellence."</p>
        </div>
    </section>

    <!-- Education Section -->
    <section id="education">
        <div class="section-title">
            <h2>Education</h2>
        </div>
        <div class="education-container">
            <div class="education-card">
                <div class="education-degree">PhD in Leadership</div>
                <div class="education-institution">Grace International Leadership University</div>
                <div class="education-year">In Progress</div>
            </div>
            <div class="education-card">
                <div class="education-degree">MSc in Project Planning and Management</div>
                <div class="education-institution">Yom Institute of Economic and Development, Hawassa Campus</div>
                <div class="education-year">2024</div>
            </div>
            <div class="education-card">
                <div class="education-degree">BSc in Information Systems</div>
                <div class="education-institution">Wollo University, Kombolicha Campus</div>
                <div class="education-year">2013</div>
            </div>
            <div class="education-card">
                <div class="education-degree">CCNA Certified</div>
                <div class="education-institution">Routing, Switching, and Network Fundamentals</div>
                <div class="education-year">Professional Certification</div>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section id="experience">
        <div class="section-title">
            <h2>Experience</h2>
        </div>
        <div class="experience-container">
            <div class="experience-card">
                <h3>Regional IT Supervisor & Regional IT Representative</h3>
                <div class="experience-company">Awash Bank (Present)</div>
                <div class="experience-period">Current Position</div>
                <ul class="experience-details">
                    <li>Lead IT operations and performance across 98 branches.</li>
                    <li>Oversee network reliability, data management, and digital systems.</li>
                    <li>Supervise regional IT officers and coordinate with area managers and branch leaders.</li>
                    <li>Represent the region in IT decision-making and strategic planning.</li>
                    <li>Lead regional implementation of national IT policies and innovations.</li>
                    <li>Initiated and facilitated the Performance Tracking Solution System for all Awash Bank branches.</li>
                    <li>Manage the Sophos Antivirus Follow-Up Management System regionally.</li>
                </ul>
            </div>
            
            <div class="experience-card">
                <h3>Team Leader</h3>
                <div class="experience-company">Regional IT Department, Awash Bank</div>
                <div class="experience-period">Current Role</div>
                <ul class="experience-details">
                    <li>Manage multiple sub-teams (infrastructure, systems, support, and reporting).</li>
                    <li>Ensure collaboration, accountability, and project delivery across the 98-branch network.</li>
                    <li>Monitor daily tasks, maintenance schedules, and preventive activities.</li>
                </ul>
            </div>
            
            <div class="experience-card">
                <h3>IT Consultant</h3>
                <div class="experience-company">Gebeta SACCOs (Present)</div>
                <div class="experience-period">Current Position</div>
                <ul class="experience-details">
                    <li>Provide IT consultation for SACCOs and microfinance institutions.</li>
                    <li>Design and implement financial systems (withdrawal, deposit, member registration, reporting).</li>
                    <li>Conduct IT audits and capacity building for SACCO staff.</li>
                </ul>
            </div>
            
            <div class="experience-card">
                <h3>Previous Roles</h3>
                <div class="experience-company">Awash Bank</div>
                <div class="experience-period">Career Progression</div>
                <ul class="experience-details">
                    <li>Senior IT Officer / IT Officer / Personal Banker / PC Operator.</li>
                    <li>Provided branch-level IT support, system upgrades, and network services.</li>
                    <li>Developed local systems and assisted in digital transformation initiatives.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills">
        <div class="section-title">
            <h2>Skills & Expertise</h2>
        </div>
        <div class="skills-container">
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-users-cog"></i></div>
                <div class="skill-name">Leadership & Team Supervision</div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-network-wired"></i></div>
                <div class="skill-name">IT Infrastructure & Networking</div>
                <div class="skill-name">CCNA Certified</div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-code"></i></div>
                <div class="skill-name">System Development</div>
                <div class="skill-name">Node.js, PHP PDO, Laravel, MySQL, Bootstrap</div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-chart-bar"></i></div>
                <div class="skill-name">Performance Monitoring & Analytics</div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-university"></i></div>
                <div class="skill-name">Financial Systems</div>
                <div class="skill-name">Deposits, Withdrawals, Member Management</div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-tasks"></i></div>
                <div class="skill-name">Project Management & Digital Transformation</div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="skill-name">Network & Data Security</div>
                <div class="skill-name">Sophos Antivirus</div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="skill-name">Mentoring & Capacity Building</div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects">
        <div class="section-title">
            <h2>Projects / Portfolio</h2>
        </div>
        <div class="projects-container" id="projectsContainer">
            <div class="spinner" id="projectsSpinner"></div>
            <!-- Projects will be loaded here via JavaScript -->
        </div>
    </section>

    <!-- Services Section -->
    <section id="services">
        <div class="section-title">
            <h2>Services</h2>
        </div>
        <div class="services-container">
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-project-diagram"></i></div>
                <h3 class="service-title">IT Project Leadership & Team Management</h3>
                <p>Strategic coordination and leadership of IT projects and teams to ensure successful implementation and organizational growth.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-cogs"></i></div>
                <h3 class="service-title">System Design & Integration</h3>
                <p>Custom system development and seamless integration with existing infrastructure to enhance operational efficiency.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-coins"></i></div>
                <h3 class="service-title">Financial System Development for SACCOs</h3>
                <p>Specialized financial systems for microfinance institutions including deposits, withdrawals, and member management.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-chart-line"></i></div>
                <h3 class="service-title">Performance & Productivity Systems</h3>
                <p>Development of monitoring and analytics systems to track performance metrics and improve organizational productivity.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-shield-alt"></i></div>
                <h3 class="service-title">IT Infrastructure & Network Security</h3>
                <p>Comprehensive infrastructure setup, network configuration, and security implementation to protect organizational assets.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-rocket"></i></div>
                <h3 class="service-title">Digital Transformation Consulting</h3>
                <p>Strategic guidance for organizations undergoing digital transformation to maximize technology investments and achieve business goals.</p>
            </div>
        </div>
    </section>

    <!-- Documents Section -->
    <section id="documents">
        <div class="section-title">
            <h2>Professional Documents</h2>
        </div>
        <div class="document-categories">
            <button class="category-btn active" data-category="all">All Documents</button>
            <button class="category-btn" data-category="CV">CV</button>
            <button class="category-btn" data-category="Application Letter">Application Letter</button>
            <button class="category-btn" data-category="Education">Education</button>
            <button class="category-btn" data-category="Certificate">Certificates</button>
            <button class="category-btn" data-category="Experience">Experience</button>
        </div>
        <div class="spinner" id="documentsSpinner"></div>
        <div class="documents-container" id="documentsContainer">
            <!-- Documents will be loaded here via JavaScript -->
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="section-title">
            <h2>Contact</h2>
        </div>
        <div class="contact-container">
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h4>Email</h4>
                        <p>Temesgen001@gmail.com</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <h4>Phone</h4>
                        <p>+251921973022 / +251910738169</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4>Location</h4>
                        <p>Hawassa, Sidama Region, Ethiopia</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon"><i class="fab fa-linkedin"></i></div>
                    <div>
                        <h4>LinkedIn</h4>
                        <p>linkedin.com/in/temesgen-fikadu</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <h3>Get In Touch</h3>
                <form id="contactForm">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="form-control" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Temesgen Fikadu Baysa. All Rights Reserved.</p>
        <div class="social-links">
            <a href="https://www.linkedin.com/in/temesgen-fikadu-1b447677" target="_blank" class="social-link" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="https://t.me/temesgen001" target="_blank" class="social-link" title="Telegram"><i class="fab fa-telegram-plane"></i></a>
            <a href="https://wa.me/251921973022" target="_blank" class="social-link" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            <!-- Kept GitHub as it's relevant for a portfolio. You can provide the link if you have one. -->
            <a href="#" class="social-link" title="GitHub"><i class="fab fa-github"></i></a>
        </div>
    </footer>

    <!-- Project Detail Modal -->
    <div class="modal" id="projectModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalProjectTitle">Project Details</h3>
                <button class="close-modal" onclick="closeProjectModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="project-gallery" id="projectGallery">
                    <!-- Gallery will be loaded here -->
                </div>
                <div class="project-details" id="projectDetails">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Navigation Toggle
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');
        
        if (hamburger && navMenu) {
            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                navMenu.classList.toggle('active');
            });
        }
        
        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (hamburger && navMenu) {
                    hamburger.classList.remove('active');
                    navMenu.classList.remove('active');
                }
            });
        });
        
        // Active navigation link highlighting
        window.addEventListener('scroll', function() {
            let current = '';
            const header = document.querySelector('header');

            if (window.scrollY > 50) {
                if (header) {
                    header.classList.add('scrolled');
                }
            } else {
                if (header) {
                    header.classList.remove('scrolled');
                }
            }
            const sections = document.querySelectorAll('section');
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (window.scrollY >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });
            
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').slice(1) === current) {
                    link.classList.add('active');
                }
            });
        });
        
        // Toast notification function
        window.showToast = function(message, type = 'success') {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.textContent = message;
                toast.className = 'toast show ' + type;
                
                setTimeout(() => {
                    toast.className = 'toast';
                }, 3000);
            }
        };
        
        // Contact form submission
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value;
                
                // Create form data
                const formData = new FormData();
                formData.append('name', name);
                formData.append('email', email);
                formData.append('subject', subject);
                formData.append('message', message);
                
                // Send form data to server
                fetch('handle_contact.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        contactForm.reset();
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    showToast('An error occurred. Please try again.', 'error');
                    console.error('Error:', error);
                });
            });
        }
        
        // Documents section functionality
        const documentsContainer = document.getElementById('documentsContainer');
        const documentsSpinner = document.getElementById('documentsSpinner');
        const categoryButtons = document.querySelectorAll('.category-btn');
        let currentCategory = 'all';
        
        // Helper function to escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Function to get icon based on file type
        function getDocumentIcon(fileType) {
            if (!fileType) return 'fa-file-alt';
            
            const type = fileType.toLowerCase();
            if (type.includes('pdf')) {
                return 'fa-file-pdf';
            } else if (type.includes('word') || type.includes('doc')) {
                return 'fa-file-word';
            } else if (type.includes('excel') || type.includes('xls') || type.includes('sheet')) {
                return 'fa-file-excel';
            } else if (type.includes('powerpoint') || type.includes('ppt') || type.includes('presentation')) {
                return 'fa-file-powerpoint';
            } else if (type.includes('image') || type.includes('jpg') || type.includes('png') || type.includes('jpeg') || type.includes('gif')) {
                return 'fa-file-image';
            } else {
                return 'fa-file-alt';
            }
        }
        
        // Function to format file size
        function formatFileSize(bytes) {
            if (!bytes) return '';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }
        
        // Function to render documents
        function renderDocuments(documents) {
            if (!documentsContainer) return;
            
            documentsContainer.innerHTML = '';
            
            if (!documents || documents.length === 0) {
                documentsContainer.innerHTML = `
                    <div class="empty-state" style="text-align: center; padding: 2rem; color: var(--dark-gray); grid-column: 1 / -1;">
                        <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <h3>No Documents Found</h3>
                        <p>No documents available in this category.</p>
                    </div>
                `;
                return;
            }
            
            documents.forEach(doc => {
                const documentCard = document.createElement('div');
                documentCard.className = 'document-card';
                
                const iconClass = getDocumentIcon(doc.file_type);
                const uploadDate = doc.upload_date ? 
                    new Date(doc.upload_date).toLocaleDateString() : '';
                const fileSize = doc.file_size ? formatFileSize(doc.file_size) : '';
                
                documentCard.innerHTML = `
                    <div class="document-icon">
                        <i class="fas ${iconClass}"></i>
                    </div>
                    <div class="document-content">
                        <h3 class="document-title">${escapeHtml(doc.title)}</h3>
                        <p class="document-category">${escapeHtml(doc.category)}</p>
                        ${uploadDate ? `<p class="document-date" style="color: var(--dark-gray); font-size: 0.8rem; margin-bottom: 0.5rem;">Uploaded: ${uploadDate}</p>` : ''}
                        ${fileSize ? `<p class="document-size" style="color: var(--dark-gray); font-size: 0.8rem; margin-bottom: 0.5rem;">Size: ${fileSize}</p>` : ''}
                        <p class="document-description">${escapeHtml(doc.description || 'No description available.')}</p>
                        <div class="document-actions" style="margin-top: auto;">
                            <a href="${doc.file_path}" target="_blank" class="btn btn-outline">View</a>
                            <a href="${doc.file_path}" download class="btn">Download</a>
                        </div>
                    </div>
                `;
                
                documentsContainer.appendChild(documentCard);
            });
        }
        
        // Function to load documents
        function loadDocuments(category = 'all') {
            if (!documentsContainer || !documentsSpinner) return;
            
            documentsSpinner.style.display = 'block';
            documentsContainer.innerHTML = '';
            
            const url = category === 'all' ? 'get_documents.php' : `get_documents.php?category=${encodeURIComponent(category)}`;
            
            fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Handle both response formats
                let documents = [];
                if (data && typeof data === 'object') {
                    if (data.success === false) {
                        throw new Error(data.message || 'Failed to load documents');
                    }
                    documents = data.documents || data;
                }
                
                if (Array.isArray(documents)) {
                    renderDocuments(documents);
                } else {
                    throw new Error('Invalid response format from server');
                }
            })
            .catch(error => {
                console.error('Error loading documents:', error);
                if (documentsContainer) {
                    documentsContainer.innerHTML = `
                        <div class="error-message" style="grid-column: 1 / -1;">
                            <h3>Error Loading Documents</h3>
                            <p>${error.message}</p>
                            <p style="font-size: 0.9rem; margin-top: 10px;">
                                Please make sure the database is properly set up and documents table exists.
                            </p>
                            <button onclick="loadDocuments('${category}')" class="btn">Try Again</button>
                        </div>
                    `;
                }
            })
            .finally(() => {
                if (documentsSpinner) {
                    documentsSpinner.style.display = 'none';
                }
            });
        }
        
        // Category button click handlers
        if (categoryButtons.length > 0) {
            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    // Load documents for selected category
                    currentCategory = button.dataset.category;
                    loadDocuments(currentCategory);
                });
            });
        }
        
        // Make loadDocuments available globally for the retry button
        window.loadDocuments = loadDocuments;
        
        // Debug function to check document loading
        window.debugDocuments = function() {
            console.log('Debugging documents loading...');
            
            fetch('get_documents.php')
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed data:', data);
                } catch (e) {
                    console.error('JSON parse error:', e);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        };
        
        // Load documents when page loads
        loadDocuments('all');
        
        // Add animation on scroll
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.skill-card, .project-card, .service-card, .education-card, .experience-card, .document-card, .responsibility-card');
            
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementPosition < windowHeight - 100) {
                    element.classList.add('in-view');
                }
            });
        };
        
        // Initial setup for animation
        const animatedElements = document.querySelectorAll('.skill-card, .project-card, .service-card, .education-card, .experience-card, .document-card, .responsibility-card');
        animatedElements.forEach(el => el.classList.add('animate-on-scroll'));
        
        // Run animation on scroll
        window.addEventListener('scroll', animateOnScroll);
        
        // Initial check for elements already in view
        animateOnScroll();
    });

    // Add a new style block for the scroll animations
    const animationStyle = document.createElement('style');
    animationStyle.innerHTML = `
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .animate-on-scroll.in-view { opacity: 1; transform: translateY(0); }
    `;
    document.head.appendChild(animationStyle);

    // =================================================
    // Project Loading and Modal Functionality
    // =================================================
    document.addEventListener('DOMContentLoaded', function() {
        const projectsContainer = document.getElementById('projectsContainer');
        const projectsSpinner = document.getElementById('projectsSpinner');
        const projectModal = document.getElementById('projectModal');
        let allProjects = [];

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderProjects(projects) {
            if (!projectsContainer) return;
            projectsContainer.innerHTML = '';

            if (!projects || projects.length === 0) {
                projectsContainer.innerHTML = `<p style="text-align:center; color: var(--dark-gray); grid-column: 1 / -1;">No projects found.</p>`;
                return;
            }

            projects.forEach(project => {
                const projectCard = document.createElement('div');
                projectCard.className = 'project-card';
                projectCard.dataset.projectId = project.id;

                let sliderHtml = '';
                const images = project.images && project.images.length > 0 ? project.images : [{ image_path: project.featured_image || 'https://picsum.photos/400/250' }];

                images.forEach(image => {
                    sliderHtml += `
                        <div class="project-slide">
                            <img src="${escapeHtml(image.image_path)}" alt="${escapeHtml(project.title)}">
                        </div>
                    `;
                });

                projectCard.innerHTML = `
                    <div class="project-image-container">
                        <div class="project-image-slider">
                            ${sliderHtml}
                        </div>
                        ${images.length > 1 ? `
                            <button class="project-slider-nav project-slider-prev">&lt;</button>
                            <button class="project-slider-nav project-slider-next">&gt;</button>
                        ` : ''}
                        ${project.is_featured == 1 ? `<div class="featured-badge"><i class="fas fa-star"></i> Featured</div>` : ''}
                        ${images.length > 1 ? `<div class="image-counter">${images.length} Images</div>` : ''}
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">${escapeHtml(project.title)}</h3>
                        <p class="project-tech">${escapeHtml(project.technologies)}</p>
                        <p class="project-description">${escapeHtml(project.description)}</p>
                        <div class="project-links">
                            ${project.github_url ? `<a href="${escapeHtml(project.github_url)}" target="_blank" class="project-link" title="View on GitHub"><i class="fab fa-github"></i></a>` : ''}
                            ${project.project_url ? `<a href="${escapeHtml(project.project_url)}" target="_blank" class="project-link"><i class="fas fa-external-link-alt"></i></a>` : ''}
                        </div>
                    </div>
                `;
                projectsContainer.appendChild(projectCard);

                // Add slider functionality
                if (images.length > 1) {
                    const slider = projectCard.querySelector('.project-image-slider');
                    let currentIndex = 0;

                    projectCard.querySelector('.project-slider-next').addEventListener('click', (e) => {
                        e.stopPropagation(); // Prevent modal from opening
                        currentIndex = (currentIndex + 1) % images.length;
                        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
                    });

                    projectCard.querySelector('.project-slider-prev').addEventListener('click', (e) => {
                        e.stopPropagation(); // Prevent modal from opening
                        currentIndex = (currentIndex - 1 + images.length) % images.length;
                        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
                    });
                }

                // Open modal when clicking on the content area, not the nav buttons
                projectCard.querySelector('.project-content').addEventListener('click', () => openProjectModal(project.id));
                projectCard.querySelector('.project-image-container').addEventListener('click', (e) => {
                    if (!e.target.classList.contains('project-slider-nav')) {
                        openProjectModal(project.id);
                    }
                });
            });
        }

        function loadProjects() {
            if (!projectsContainer || !projectsSpinner) return;

            projectsSpinner.style.display = 'block';
            projectsContainer.innerHTML = '';

            fetch('get_projects.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        allProjects = data.projects;
                        renderProjects(allProjects);
                    } else {
                        throw new Error(data.message || 'Failed to load projects');
                    }
                })
                .catch(error => {
                    console.error('Error loading projects:', error);
                    if (projectsContainer) {
                        projectsContainer.innerHTML = `
                            <div class="error-message" style="grid-column: 1 / -1;">
                                <h3>Error Loading Projects</h3>
                                <p>${error.message}</p>
                                <button onclick="window.location.reload()" class="btn">Try Again</button>
                            </div>
                        `;
                    }
                })
                .finally(() => {
                    if (projectsSpinner) {
                        projectsSpinner.style.display = 'none';
                    }
                });
        }

        window.openProjectModal = function(projectId) {
            const project = allProjects.find(p => p.id == projectId);
            if (!project || !projectModal) return;

            // --- Populate Modal ---
            document.getElementById('modalProjectTitle').innerText = project.title;

            const galleryContainer = document.getElementById('projectGallery');
            const detailsContainer = document.getElementById('projectDetails');
            galleryContainer.innerHTML = ''; // Clear previous content
            detailsContainer.innerHTML = ''; // Clear previous content

            // --- Populate Gallery ---
            const images = project.images && project.images.length > 0 ? project.images : [{ image_path: project.featured_image || 'https://picsum.photos/800/500', image_description: 'Featured Image' }];
            
            let gallerySliderHtml = '<div class="gallery-slider">';
            let indicatorsHtml = '<div class="gallery-indicators">';
            
            images.forEach((image, index) => {
                gallerySliderHtml += `
                    <div class="gallery-slide" data-index="${index}">
                        <img src="${escapeHtml(image.image_path)}" alt="${escapeHtml(image.image_description || project.title)}">
                    </div>
                `;
                indicatorsHtml += `<div class="indicator" data-slide-to="${index}"></div>`;
            });
            
            gallerySliderHtml += '</div>';
            indicatorsHtml += '</div>';

            galleryContainer.innerHTML = `
                ${gallerySliderHtml}
                ${images.length > 1 ? `
                    <button class="gallery-nav gallery-prev">&lt;</button>
                    <button class="gallery-nav gallery-next">&gt;</button>
                    ${indicatorsHtml}
                ` : ''}
                <div class="gallery-caption"></div>
            `;

            // --- Populate Details ---
            const techTags = project.technologies.split(',').map(tech => `<span class="tech-tag">${escapeHtml(tech.trim())}</span>`).join('');

            detailsContainer.innerHTML = `
                <div class="project-detail-section">
                    <h4>Description</h4>
                    <p>${escapeHtml(project.description)}</p>
                </div>
                <div class="project-detail-section">
                    <h4>My Role</h4>
                    <p>${escapeHtml(project.role)}</p>
                </div>
                <div class="project-detail-section">
                    <h4>Technologies Used</h4>
                    <div class="project-tech-tags">${techTags}</div>
                </div>
                <div class="project-detail-section">
                    <h4>Links</h4>
                    <div class="project-links">
                        ${project.github_url ? `<a href="${escapeHtml(project.github_url)}" target="_blank" class="btn btn-outline"><i class="fab fa-github"></i> GitHub</a>` : ''}
                        ${project.project_url ? `<a href="${escapeHtml(project.project_url)}" target="_blank" class="btn"><i class="fas fa-external-link-alt"></i> Live Project</a>` : ''}
                    </div>
                </div>
            `;

            // --- Activate Modal and Gallery ---
            projectModal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling

            if (images.length > 1) {
                let currentIndex = 0;
                const slider = galleryContainer.querySelector('.gallery-slider');
                const slides = galleryContainer.querySelectorAll('.gallery-slide');
                const indicators = galleryContainer.querySelectorAll('.indicator');
                const caption = galleryContainer.querySelector('.gallery-caption');

                function updateGallery() {
                    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
                    indicators.forEach(ind => ind.classList.remove('active'));
                    indicators[currentIndex].classList.add('active');
                    caption.textContent = images[currentIndex].image_description || '';
                    caption.style.display = images[currentIndex].image_description ? 'block' : 'none';
                }

                galleryContainer.querySelector('.gallery-next').addEventListener('click', () => {
                    currentIndex = (currentIndex + 1) % images.length;
                    updateGallery();
                });

                galleryContainer.querySelector('.gallery-prev').addEventListener('click', () => {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    updateGallery();
                });

                indicators.forEach(indicator => {
                    indicator.addEventListener('click', () => {
                        currentIndex = parseInt(indicator.dataset.slideTo);
                        updateGallery();
                    });
                });

                updateGallery();
            } else {
                const caption = galleryContainer.querySelector('.gallery-caption');
                caption.textContent = images[0].image_description || '';
                caption.style.display = images[0].image_description ? 'block' : 'none';
            }
        }

        window.closeProjectModal = function() {
            if (projectModal) projectModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        loadProjects();
    });

    // Close modal on outside click or escape key
    window.addEventListener('click', function(event) {
        const projectModal = document.getElementById('projectModal');
        if (event.target === projectModal) {
            closeProjectModal();
        }
    });
    document.addEventListener('keydown', e => e.key === 'Escape' && closeProjectModal());
</script>
</body>
</html>