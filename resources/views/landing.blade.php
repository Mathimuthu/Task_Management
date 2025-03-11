<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Roriri HRMS</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: iLanding
  * Template URL: https://bootstrapmade.com/ilanding-bootstrap-landing-page-template/
  * Updated: Nov 12 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="#home-section" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="/images/logororiri.png" alt="">
        <h1 class="sitename">Roriri HRMS</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
        <ul>
          <li><a href="#home-section" onclick="showMainSections()" class="active">Home</a></li>
          <li><a href="#features-section" onclick="showMainSections()">Key Features</a></li>
          <li><a href="#services-section" onclick="showMainSections()">Future Enhancements</a></li>
          <li><a href="#faq" onclick="showMainSections()">FAQ</a></li>
          <li><a href="#footer" onclick="showMainSections()">About</a></li>
          <!-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li> -->
          <!-- <li><a href="#contact">Contact</a></li> -->
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="{{ route('login') }}">Get Started</a>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="home-section" class="hero section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
              <div class="company-badge mb-4">
                <i class="bi bi-gear-fill me-2"></i>
                Working for your success
              </div>
              <h1 class="mb-4">
               Task management <br>
                System <br>
                <!-- <span class="accent-text">Vestibulum Ante</span> -->
              </h1>
              <p class="mb-4 mb-md-5">
              The Task Management System is a web-based application built with Laravel. It helps users create, assign, track, and manage tasks efficiently. The system uses DataTables for data representation and provides role-based access control.
              </p>
              <div class="hero-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary me-0 me-sm-2 mx-1">Get Started</a>
                <a href="https://youtu.be/WV3xOWBYrGU?si=GLoNh3Ur5o7Dn_m8" class="btn btn-link mt-2 mt-sm-0 glightbox">
                  <i class="bi bi-play-circle me-1"></i>
                  Play Video
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
              <img src="assets/img/illustration-1.webp" alt="Hero Image" class="img-fluid">
              <!-- <div class="customers-badge">
                <div class="customer-avatars">
                  <img src="assets/img/avatar-1.webp" alt="Customer 1" class="avatar">
                  <img src="assets/img/avatar-2.webp" alt="Customer 2" class="avatar">
                  <img src="assets/img/avatar-3.webp" alt="Customer 3" class="avatar">
                  <img src="assets/img/avatar-4.webp" alt="Customer 4" class="avatar">
                  <img src="assets/img/avatar-5.webp" alt="Customer 5" class="avatar">
                  <span class="avatar more">12+</span>
                </div>
                <p class="mb-0 mt-2">12,000+ lorem ipsum dolor sit amet consectetur adipiscing elit</p>
              </div> -->
            </div>
          </div>
        </div>
        <div class="row stats-row gy-4 mt-5" data-aos="fade-up" data-aos-delay="500">
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class=""> 🛠️</i>
              </div>
              <div class="stat-content">
                <h4>Admin</h4>
                <p class="mb-0">Full access, can manage all tasks and users.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="">📜</i>
              </div>
              <div class="stat-content">
                <h4>Hr</h4>
                <p class="mb-0">Can view and manage all users.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="">📋</i>
              </div>
              <div class="stat-content">
                <h4>Manager</h4>
                <p class="mb-0">Can create and assign tasks to employees.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="">👤</i>
              </div>
              <div class="stat-content">
                <h4>Employee</h4>
                <p class="mb-0">Can view and update tasks assigned to them.</p>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section> 
    <!-- Features 2 Section -->
    <section id="features-section" class="features-2 section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Features</h2>
      </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">

          <div class="col-lg-4">

            <div class="feature-item text-end mb-5" data-aos="fade-right" data-aos-delay="200">
              <div class="d-flex align-items-center justify-content-end gap-4">
                <div class="feature-content">
                  <h3>Task Creation</h3>
                  <p>Managers can create tasks and assign them to employees.</p>
                </div>
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-display"></i>
                </div>
              </div>
            </div>

            <div class="feature-item text-end mb-5" data-aos="fade-right" data-aos-delay="300">
              <div class="d-flex align-items-center justify-content-end gap-4">
                <div class="feature-content">
                  <h3>Task Tracking</h3>
                  <p> Employees and managers can track task status.</p>
                </div>
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-feather"></i>
                </div>
              </div>
            </div>

            <div class="feature-item text-end" data-aos="fade-right" data-aos-delay="400">
              <div class="d-flex align-items-center justify-content-end gap-4">
                <div class="feature-content">
                  <h3>User Management</h3>
                  <p> Admins and HR can manage users.</p>
                </div>
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-eye"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="phone-mockup text-center">
              <img src="assets/img/task-management-app-interface_23-2148681556.avif" alt="Phone Mockup" class="img-fluid">
            </div>
          </div>

          <div class="col-lg-4">

            <div class="feature-item mb-5" data-aos="fade-left" data-aos-delay="200">
              <div class="d-flex align-items-center gap-4">
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-code-square"></i>
                </div>
                <div class="feature-content">
                  <h3>Role-Based Access</h3>
                  <p> Users can only access information relevant to their role.</p>
                </div>
              </div>
            </div>

            <div class="feature-item mb-5" data-aos="fade-left" data-aos-delay="300">
              <div class="d-flex align-items-center gap-4">
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-phone"></i>
                </div>
                <div class="feature-content">
                  <h3>Department-Based Employee Management</h3>
                  <p> Employees belong to the same department as the logged-in user.</p>
                </div>
              </div>
            </div>

            <div class="feature-item" data-aos="fade-left" data-aos-delay="400">
              <div class="d-flex align-items-center gap-4">
                <div class="feature-icon flex-shrink-0">
                  <i class="bi bi-browser-chrome"></i>
                </div>
                <div class="feature-content">
                  <h3>DataTables Integration</h3>
                  <p>Enhanced data presentation and filtering</p>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </section>
    <!-- /Features 2 Section -->
    <!-- future enhancement Section -->
    <section id="services-section" class="services section light-background">

      <div class="container section-title" data-aos="fade-up">
        <h2>Future Enhancements</h2>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4">

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-card d-flex">
              <div class="icon flex-shrink-0">
                <i class="bi bi-activity"></i>
              </div>
              <div>
                <h3>Multi-employee task assignments</h3>
                <p> Enable assigning tasks to multiple employees, improving collaboration and workload distribution.</p>
                <!-- <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a> -->
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-card d-flex">
              <div class="icon flex-shrink-0">
                <i class="bi bi-diagram-3"></i>
              </div>
              <div>
                <h3>Task priority levels</h3>
                <p>Introduce priority options like High, Medium, and Low to help users focus on urgent tasks first.</p>
                <!-- <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a> -->
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-card d-flex">
              <div class="icon flex-shrink-0">
                <i class="bi bi-easel"></i>
              </div>
              <div>
                <h3>Notifications & reminders</h3>
                <p>Implement real-time alerts via email or in-app notifications for task updates, deadlines, and assignments.</p>
                <!-- <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a> -->
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-card d-flex">
              <div class="icon flex-shrink-0">
                <i class="bi bi-clipboard-data"></i>
              </div>
              <div>
                <h3>Reporting & analytics</h3>
                <p>Provide detailed reports and visual insights on task progress, employee productivity, and overall system performance.</p>
                <!-- <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a> -->
              </div>
            </div>
          </div>

        </div>

      </div>

    </section>
    <!-- /Services Section -->
    <!-- Faq Section -->
    <section class="faq-9 faq section light-background" id="faq">

      <div class="container">
        <div class="row">

          <div class="col-lg-5" data-aos="fade-up">
            <h2 class="faq-title">Have a question? Check out the FAQ</h2>
            <p class="faq-description">This FAQ section provides answers to common questions about using the task management system. It covers essential features like task creation, assignment, tracking, notifications, priority levels, and reporting.</p>
            <div class="faq-arrow d-none d-lg-block" data-aos="fade-up" data-aos-delay="200">
              <svg class="faq-arrow" width="200" height="211" viewBox="0 0 200 211" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M198.804 194.488C189.279 189.596 179.529 185.52 169.407 182.07L169.384 182.049C169.227 181.994 169.07 181.939 168.912 181.884C166.669 181.139 165.906 184.546 167.669 185.615C174.053 189.473 182.761 191.837 189.146 195.695C156.603 195.912 119.781 196.591 91.266 179.049C62.5221 161.368 48.1094 130.695 56.934 98.891C84.5539 98.7247 112.556 84.0176 129.508 62.667C136.396 53.9724 146.193 35.1448 129.773 30.2717C114.292 25.6624 93.7109 41.8875 83.1971 51.3147C70.1109 63.039 59.63 78.433 54.2039 95.0087C52.1221 94.9842 50.0776 94.8683 48.0703 94.6608C30.1803 92.8027 11.2197 83.6338 5.44902 65.1074C-1.88449 41.5699 14.4994 19.0183 27.9202 1.56641C28.6411 0.625793 27.2862 -0.561638 26.5419 0.358501C13.4588 16.4098 -0.221091 34.5242 0.896608 56.5659C1.8218 74.6941 14.221 87.9401 30.4121 94.2058C37.7076 97.0203 45.3454 98.5003 53.0334 98.8449C47.8679 117.532 49.2961 137.487 60.7729 155.283C87.7615 197.081 139.616 201.147 184.786 201.155L174.332 206.827C172.119 208.033 174.345 211.287 176.537 210.105C182.06 207.125 187.582 204.122 193.084 201.144C193.346 201.147 195.161 199.887 195.423 199.868C197.08 198.548 193.084 201.144 195.528 199.81C196.688 199.192 197.846 198.552 199.006 197.935C200.397 197.167 200.007 195.087 198.804 194.488ZM60.8213 88.0427C67.6894 72.648 78.8538 59.1566 92.1207 49.0388C98.8475 43.9065 106.334 39.2953 114.188 36.1439C117.295 34.8947 120.798 33.6609 124.168 33.635C134.365 33.5511 136.354 42.9911 132.638 51.031C120.47 77.4222 86.8639 93.9837 58.0983 94.9666C58.8971 92.6666 59.783 90.3603 60.8213 88.0427Z" fill="currentColor"></path>
              </svg>
            </div>
          </div>

          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="300">
            <div class="faq-container">

              <div class="faq-item faq-active">
                <h3>How do I create a new task?</h3>
                <div class="faq-content">
                  <p>Navigate to the "Tasks" section, click "Add Task," fill in the required details (title, description, assigned employee(s), priority, due date), and submit.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Can I assign a task to multiple employees?</h3>
                <div class="faq-content">
                  <p>Yes, tasks Can be assigned to a Multiple employee task assingments</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>How do I track the status of a task?</h3>
                <div class="faq-content">
                  <p>Each task has a status (Pending, In Progress, Completed, or Cancelled). You can track it from the task list or the detailed task view.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>How will I receive task notifications?</h3>
                <div class="faq-content">
                  <p>Future enhancements will include email and in-app notifications for task assignments, updates, and upcoming deadlines.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Can I set priority levels for tasks?</h3>
                <div class="faq-content">
                  <p>Yes you can set as priority levels (High, Medium, Low) to help manage task urgency effectively.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>How can I generate reports on tasks?</h3>
                <div class="faq-content">
                  <p>A reporting and analytics module will be added to provide insights on task progress, employee performance, and workload distribution.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- /Faq Section -->
    <section id="terms-section" style="display: none;" class="features-2 section light-background">
        <div class="container section-title mt-5" data-aos="fade-up"  data-aos-delay="400">
            <h2>Terms And Conditions</h2>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <p>These terms and conditions outline the rules and regulations for using our website and the services provided by RoririSoft.com, a web app and ERP development company. By accessing or using our website and services, you agree to these terms and conditions. If you do not agree with any part of these terms, please refrain from using our website or services.</p>
          <h4 style="color:#0d83fd">1. Use of Website</h4>
          <p>You agree to use our website and services solely for lawful purposes and in compliance with these terms and conditions.</p>
          <h4 style="color:#0d83fd">2. Intellectual Property</h4>
          <p>👉  All content, trademarks, and intellectual property displayed on our website are owned by RoririSoft.com or its licensors.<br>
          👉 You are not permitted to use, reproduce, modify, or distribute any content from our website without prior written consent.
          <h4 style="color:#0d83fd">3.Privacy Policy </h4>
          <p>Your use of our website and services is subject to our Privacy Policy, which details how we collect, use, and protect your personal information.</p>
          <h4 style="color:#0d83fd">4.Third-Party Links </h4>
          <p>Our website may include links to third-party websites or services not controlled or owned by RoririSoft.com<br>
            We are not responsible for the content, privacy practices, or terms of use of any third-party websites or services.
          </p>
          <h4 style="color:#0d83fd">5.Disclaimer of Warranties </h4>
          <p>👉 While we strive to provide accurate and up-to-date information, RoririSoft.com makes no guarantees about the completeness, accuracy, reliability, or availability of the information, products, or services on our website.<br>
          👉 Your use of our website and services is at your own risk. We disclaim all warranties, including but not limited to implied warranties of merchantability, fitness for a particular purpose, and non-infringement.
          </p>
          <h4 style="color:#0d83fd">6.Limitation of Liability </h4>
          <p>RoririSoft.com shall not be liable for any direct, indirect, incidental, special, or consequential damages resulting from your use of our website or services.</p>
          <h4 style="color:#0d83fd">7.Governing Law </h4>
          <p>These terms and conditions are governed by and construed in accordance with the laws of India, without regard to its conflict of law provisions.</p>
          <h4 style="color:#0d83fd">8.Changes to Terms </h4>
          <p>We reserve the right to update or modify these terms and conditions at any time without prior notice. Your continued use of our website and services constitutes acceptance of the revised terms</p>
          <h4 style="color:#0d83fd">9.Contact Us</h4>
          <p>If you have any questions or concerns regarding these terms and conditions, please contact us at info@roririsoft.com.<br><br>
            By using our website and services, you acknowledge that you have read, understood, and agree to be bound by these terms and conditions. If you do not agree with any part of these terms, please discontinue your use of our website and services.
          </p>
        </div>
    </section>
    <section id="privacy-policy" class="features-2 section light-background" style="display: none;">
      <div class="container section-title mt-5" data-aos="fade-up"  data-aos-delay="400">
        <h2>Privacy & Policy</h2>
      </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <p>At RoririSoft.com, your privacy is our priority. As a web app and ERP development company, we are dedicated to safeguarding the personal information you entrust to us. This Privacy Policy explains how we collect, use, and protect your data when you visit our website or utilize our services.</p>
          <h4 style="color:#0d83fd">1. Information We Collect</h4>
          <p><b>Personal Information:</b><br>
            We may collect personal details such as your name, email address, phone number, and company information when you interact with our website or services.<br>
            <b>Usage Data:</b><br>
            We also gather data about your interactions with our website, including your IP address, browser type, device details, and the pages you visit.
          </p>
          <h4 style="color:#0d83fd">2. How We Use Your Information</h4>
          <p>👉 To deliver and maintain our services effectively.<br>
          👉 To communicate updates about our products and services.<br>
          👉 To enhance our website and services based on user feedback and behavior.<br>
          👉 To analyze trends, monitor website performance, and prevent fraud or abuse.
          </p>
          <h4 style="color:#0d83fd">3.Data Security </h4>
          <p>We employ robust security measures to protect your information from unauthorized access, alteration, disclosure, or destruction.</p>
          <h4 style="color:#0d83fd">4.Third-Party Services </h4>
          <p>We may engage third-party services, such as analytics providers and payment processors, to support our operations. These third parties access your information only as needed to perform their services and are required to ensure its confidentiality and security.</p>
          <h4 style="color:#0d83fd">5.Cookies </h4>
          <p>We use cookies and similar technologies to enhance your browsing experience, analyze website traffic, and personalize content. You can manage or disable cookies through your browser settings.</p>
          <h4 style="color:#0d83fd">6.Your Rights </h4>
          <p><b>Personal Information:</b><br>
          👉  Access the personal information we hold about you.<br>
          👉  Request updates or corrections to your data.<br>
          👉  Request the deletion of your personal information.<br>
          👉  For any questions or requests regarding your data, please contact us using the information provided below.
          </p>
          <h4 style="color:#0d83fd">7.Changes to this Policy </h4>
          <p>We may update this Privacy Policy periodically to reflect changes in our practices or legal requirements. Significant updates will be communicated by posting the revised policy on our website.</p>
          <h4 style="color:#0d83fd">8.Contact Us</h4>
          <p>For any inquiries, concerns, or requests related to this Privacy Policy, please contact us at: Email: privacy@roririsoft.com<br>
            By using our website or services, you consent to the terms outlined in this Privacy Policy.
          </p>
        </div>
    </section>
  </main>

  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-3 col-md-6 footer-about">
          <a href="" class="logo d-flex align-items-center">
          <img src="/images/logororiri.png" alt="">
            <span class="sitename">Roriri HRMS</span>
          </a>
          <div class="footer-contact">
            <p class="mt-3"><strong>Phone:</strong> <span>+91 73389 41579</span></p>
            <p><strong>Email:</strong> <span>contact@roririsoft.com</span></p>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 footer-about">
        <h4>Head Office</h4>
          <div class="footer-contact">
            <p>RORIRI IT PARK, KALAKAD, Tirunelveli,</p>
            <p>Tamilnadu, India - 627502</p>
            <div class="social-links d-flex mt-4">
              <a href="https://www.youtube.com/@roriri_soft"><i class="bi bi-youtube"></i></a>
              <a href="https://www.facebook.com/RoririSoftwareSolutionsPvtLtd/"><i class="bi bi-facebook"></i></a>
              <a href="https://www.instagram.com/roriri_soft/?igsh=MTJmMHMxNm5pOTIy#"><i class="bi bi-instagram"></i></a>
              <a href="https://in.linkedin.com/company/roriri-software-solutions-pvt-ltd"><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="#home-section" onclick="showMainSections()" class="active">Home</a></li>
            <li><a href="#features-section" onclick="showMainSections()">Key Features</a></li>
            <li><a href="#enhancement-section" onclick="showMainSections()">Future Enhancements</a></li>
        </ul>
        </div>
        <div class="col-lg-2 col-md-3 footer-links">
        <h4></h4>
          <ul>
          <li>
          <li><a href="javascript:void(0);" onclick="showOnly('terms-section')">Terms of Service</a></li>
          <li><a href="javascript:void(0);" onclick="showOnly('privacy-policy')">Privacy & Policy</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">2025 Roriri</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    function showMainSections() {
        // Show all main sections
        document.getElementById("home-section").style.display = "block";
        document.getElementById("features-section").style.display = "block";
        document.getElementById("services-section").style.display = "block";
        document.getElementById("faq").style.display = "block";

        // Hide Terms and Privacy sections
        document.getElementById("terms-section").style.display = "none";
        document.getElementById("privacy-policy").style.display = "none";
    }

    function showOnly(sectionId) {
        // Hide all main sections
        document.getElementById("home-section").style.display = "none";
        document.getElementById("features-section").style.display = "none";
        document.getElementById("services-section").style.display = "none";
        document.getElementById("faq").style.display = "none";

        // Show only the selected section (Terms or Privacy)
        document.getElementById("terms-section").style.display = "none";
        document.getElementById("privacy-policy").style.display = "none";
        
        document.getElementById(sectionId).style.display = "block";
    }
</script>


</body>

</html>