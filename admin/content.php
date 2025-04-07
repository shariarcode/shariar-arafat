<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Initialize variables
$message = '';
$messageType = '';

// Handle form submission for content updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_hero'])) {
    // In a real implementation, you'd save this to a database
    // For this demo, we'll simulate a success message
    $message = "Hero section updated successfully!";
    $messageType = "success";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_about'])) {
    $message = "About section updated successfully!";
    $messageType = "success";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_services'])) {
    $message = "Services section updated successfully!";
    $messageType = "success";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_testimonials'])) {
    $message = "Testimonials section updated successfully!";
    $messageType = "success";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_contact'])) {
    $message = "Contact section updated successfully!";
    $messageType = "success";
}

// Add handler for education section
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_education'])) {
    $message = "Education section updated successfully!";
    $messageType = "success";
}

// Parse HTML file to extract current content
function getHtmlContent($file, $sectionId) {
    $content = file_get_contents($file);
    if ($content === false) {
        return false;
    }
    
    // This is a simple extraction and would need to be more robust in a real implementation
    $pattern = '/<section id="' . $sectionId . '".*?>(.*?)<\/section>/s';
    if (preg_match($pattern, $content, $matches)) {
        return $matches[0];
    }
    
    return false;
}

// Get current content (in a real implementation, this would come from a database)
$heroContent = getHtmlContent('../index.html', 'hero');
$aboutContent = getHtmlContent('../index.html', 'about');
$servicesContent = getHtmlContent('../index.html', 'services');
$testimonialsContent = getHtmlContent('../index.html', 'testimonials');
$contactContent = getHtmlContent('../index.html', 'contact');
$educationContent = getHtmlContent('../index.html', 'education');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management | Shariar Arafat Portfolio</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Include TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        // Initialize TinyMCE
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '.tinymce-editor',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic forecolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                content_style: 'body { font-family:Inter,Arial,sans-serif; font-size:16px }'
            });
        });
    </script>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="admin-logo">
                <h1>Portfolio Admin</h1>
            </div>
            <nav class="admin-menu">
                <a href="dashboard.php">Dashboard</a>
                <a href="content.php" class="active">Content</a>
                <a href="projects.php">Projects</a>
                <a href="media.php">Media</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php" style="color: #ef4444;">Logout</a>
            </nav>
        </header>
        
        <!-- Content Management -->
        <div class="admin-content">
            <div class="admin-title">
                <h2>Content Management</h2>
                <p>Edit the content of your portfolio website</p>
            </div>
            
            <?php if(!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Content Sections Tabs -->
            <div class="admin-card">
                <div style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
                    <button id="heroTab" class="tablink active" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; font-weight: 500; border-bottom: 3px solid var(--primary-color);">
                        Hero
                    </button>
                    <button id="aboutTab" class="tablink" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; font-weight: 500; border-bottom: 3px solid transparent;">
                        About
                    </button>
                    <button id="educationTab" class="tablink" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; font-weight: 500; border-bottom: 3px solid transparent;">
                        Education
                    </button>
                    <button id="servicesTab" class="tablink" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; font-weight: 500; border-bottom: 3px solid transparent;">
                        Services
                    </button>
                    <button id="testimonialsTab" class="tablink" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; font-weight: 500; border-bottom: 3px solid transparent;">
                        Testimonials
                    </button>
                    <button id="contactTab" class="tablink" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; font-weight: 500; border-bottom: 3px solid transparent;">
                        Contact
                    </button>
                </div>
                
                <!-- Hero Section Tab Content -->
                <div id="heroContent" class="tab-content">
                    <form action="content.php" method="POST" class="admin-form">
                        <div class="form-section">
                            <h3>Hero Section</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hero_title">Main Title</label>
                                    <input type="text" id="hero_title" name="hero_title" value="Hi, I'm Shariar Arafat">
                                </div>
                                <div class="form-group">
                                    <label for="hero_subtitle">Subtitle</label>
                                    <input type="text" id="hero_subtitle" name="hero_subtitle" value="Student & Web Developer">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="hero_description">Description</label>
                                <textarea id="hero_description" name="hero_description" rows="3">I create beautiful, functional websites that help businesses grow.</textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hero_btn_primary">Primary Button Text</label>
                                    <input type="text" id="hero_btn_primary" name="hero_btn_primary" value="View My Work">
                                </div>
                                <div class="form-group">
                                    <label for="hero_btn_secondary">Secondary Button Text</label>
                                    <input type="text" id="hero_btn_secondary" name="hero_btn_secondary" value="Contact Me">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="hero_image">Hero Image</label>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <img src="../images/profile-photo.jpg" alt="Hero Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='media.php'">
                                        <i class="fas fa-exchange-alt"></i> Change Image
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="update_hero" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- About Section Tab Content -->
                <div id="aboutContent" class="tab-content" style="display: none;">
                    <form action="content.php" method="POST" class="admin-form">
                        <div class="form-section">
                            <h3>About Section</h3>
                            <div class="form-group">
                                <label for="about_title">Section Title</label>
                                <input type="text" id="about_title" name="about_title" value="About Me">
                            </div>
                            <div class="form-section">
                                <h4>Education & Background</h4>
                                <div class="form-group">
                                    <textarea class="tinymce-editor" name="about_education">I am a passionate student pursuing a degree in Computer Science with a focus on web development and UI/UX design. My journey in tech began when I built my first website at 16, and I've been hooked ever since.</textarea>
                                </div>
                            </div>
                            <div class="form-section">
                                <h4>Skills</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Front-end Skills</label>
                                        <textarea class="tinymce-editor" name="skills_frontend">
                                            <ul>
                                                <li>HTML5</li>
                                                <li>CSS3/SASS</li>
                                                <li>JavaScript</li>
                                                <li>React.js</li>
                                                <li>Responsive Design</li>
                                            </ul>
                                        </textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Back-end Skills</label>
                                        <textarea class="tinymce-editor" name="skills_backend">
                                            <ul>
                                                <li>Node.js</li>
                                                <li>Express</li>
                                                <li>MongoDB</li>
                                                <li>API Development</li>
                                            </ul>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-section">
                                <h4>Interests</h4>
                                <div class="form-group">
                                    <textarea class="tinymce-editor" name="about_interests">When I'm not coding, you can find me exploring new hiking trails, reading science fiction novels, or experimenting with photography. I believe in continuous learning and regularly participate in online courses and coding challenges.</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="about_resume">Resume PDF</label>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <input type="text" id="about_resume" name="about_resume" value="resume.pdf" readonly>
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='media.php'">
                                        <i class="fas fa-upload"></i> Upload New
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="update_about" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Education Section Tab Content -->
                <div id="educationContent" class="tab-content" style="display: none;">
                    <form action="content.php" method="POST" class="admin-form">
                        <div class="form-section">
                            <h3>Education Section</h3>
                            <div class="form-group">
                                <label for="education_title">Section Title</label>
                                <input type="text" id="education_title" name="education_title" value="My Education">
                            </div>
                            <div class="form-group">
                                <label for="education_description">Section Description</label>
                                <textarea id="education_description" name="education_description" rows="3">My academic journey and educational qualifications.</textarea>
                            </div>
                            
                            <div class="form-section">
                                <h4>Education Entries</h4>
                                <p>Add your educational background in chronological order (newest first).</p>
                                
                                <!-- Education Entry 1 -->
                                <div style="padding: 1rem; background-color: var(--bg-gray); border-radius: var(--border-radius); margin-bottom: 1.5rem;">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="education1_degree">Degree/Certificate</label>
                                            <input type="text" id="education1_degree" name="education1_degree" value="Bachelor of Science in Computer Science">
                                        </div>
                                        <div class="form-group">
                                            <label for="education1_year">Years</label>
                                            <input type="text" id="education1_year" name="education1_year" value="2020 - Present">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="education1_institution">Institution</label>
                                        <input type="text" id="education1_institution" name="education1_institution" value="University of Technology">
                                    </div>
                                    <div class="form-group">
                                        <label for="education1_description">Description</label>
                                        <textarea class="tinymce-editor" id="education1_description" name="education1_description">
                                            <p>Currently pursuing a Bachelor's degree in Computer Science with specialization in Web Development and Software Engineering. Maintaining a GPA of 3.8/4.0.</p>
                                            <p>Relevant coursework includes:</p>
                                            <ul>
                                                <li>Data Structures and Algorithms</li>
                                                <li>Web Application Development</li>
                                                <li>Database Management Systems</li>
                                                <li>User Interface Design</li>
                                            </ul>
                                        </textarea>
                                    </div>
                                </div>
                                
                                <!-- Education Entry 2 -->
                                <div style="padding: 1rem; background-color: var(--bg-gray); border-radius: var(--border-radius); margin-bottom: 1.5rem;">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="education2_degree">Degree/Certificate</label>
                                            <input type="text" id="education2_degree" name="education2_degree" value="High School Diploma">
                                        </div>
                                        <div class="form-group">
                                            <label for="education2_year">Years</label>
                                            <input type="text" id="education2_year" name="education2_year" value="2016 - 2020">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="education2_institution">Institution</label>
                                        <input type="text" id="education2_institution" name="education2_institution" value="Central High School">
                                    </div>
                                    <div class="form-group">
                                        <label for="education2_description">Description</label>
                                        <textarea class="tinymce-editor" id="education2_description" name="education2_description">
                                            <p>Graduated with honors with a focus on Mathematics and Computer Science. Participated in various programming competitions and tech clubs.</p>
                                        </textarea>
                                    </div>
                                </div>
                                
                                <!-- Add More Button -->
                                <div style="text-align: center; margin-top: 1rem;">
                                    <button type="button" class="btn btn-secondary">
                                        <i class="fas fa-plus"></i> Add Another Education Entry
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="update_education" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Services Section Tab Content -->
                <div id="servicesContent" class="tab-content" style="display: none;">
                    <form action="content.php" method="POST" class="admin-form">
                        <div class="form-section">
                            <h3>Services Section</h3>
                            <div class="form-group">
                                <label for="services_title">Section Title</label>
                                <input type="text" id="services_title" name="services_title" value="My Services">
                            </div>
                            
                            <!-- Service Items -->
                            <div class="form-section">
                                <h4>Service 1</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="service1_title">Title</label>
                                        <input type="text" id="service1_title" name="service1_title" value="Web Development">
                                    </div>
                                    <div class="form-group">
                                        <label for="service1_icon">Icon</label>
                                        <select id="service1_icon" name="service1_icon">
                                            <option value="fas fa-code" selected>Code</option>
                                            <option value="fas fa-paint-brush">Paint Brush</option>
                                            <option value="fas fa-mobile-alt">Mobile</option>
                                            <option value="fas fa-search">Search</option>
                                            <option value="fas fa-cogs">Cogs</option>
                                            <option value="fas fa-server">Server</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="service1_description">Description</label>
                                    <textarea id="service1_description" name="service1_description" rows="3">Custom-built websites with clean code, optimized for performance and user experience.</textarea>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h4>Service 2</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="service2_title">Title</label>
                                        <input type="text" id="service2_title" name="service2_title" value="Responsive Design">
                                    </div>
                                    <div class="form-group">
                                        <label for="service2_icon">Icon</label>
                                        <select id="service2_icon" name="service2_icon">
                                            <option value="fas fa-code">Code</option>
                                            <option value="fas fa-paint-brush">Paint Brush</option>
                                            <option value="fas fa-mobile-alt" selected>Mobile</option>
                                            <option value="fas fa-search">Search</option>
                                            <option value="fas fa-cogs">Cogs</option>
                                            <option value="fas fa-server">Server</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="service2_description">Description</label>
                                    <textarea id="service2_description" name="service2_description" rows="3">Mobile-first designs that work flawlessly across all devices and screen sizes.</textarea>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h4>Service 3</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="service3_title">Title</label>
                                        <input type="text" id="service3_title" name="service3_title" value="UI/UX Design">
                                    </div>
                                    <div class="form-group">
                                        <label for="service3_icon">Icon</label>
                                        <select id="service3_icon" name="service3_icon">
                                            <option value="fas fa-code">Code</option>
                                            <option value="fas fa-paint-brush" selected>Paint Brush</option>
                                            <option value="fas fa-mobile-alt">Mobile</option>
                                            <option value="fas fa-search">Search</option>
                                            <option value="fas fa-cogs">Cogs</option>
                                            <option value="fas fa-server">Server</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="service3_description">Description</label>
                                    <textarea id="service3_description" name="service3_description" rows="3">User-centered design that enhances usability and creates memorable experiences.</textarea>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h4>Service 4</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="service4_title">Title</label>
                                        <input type="text" id="service4_title" name="service4_title" value="SEO Optimization">
                                    </div>
                                    <div class="form-group">
                                        <label for="service4_icon">Icon</label>
                                        <select id="service4_icon" name="service4_icon">
                                            <option value="fas fa-code">Code</option>
                                            <option value="fas fa-paint-brush">Paint Brush</option>
                                            <option value="fas fa-mobile-alt">Mobile</option>
                                            <option value="fas fa-search" selected>Search</option>
                                            <option value="fas fa-cogs">Cogs</option>
                                            <option value="fas fa-server">Server</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="service4_description">Description</label>
                                    <textarea id="service4_description" name="service4_description" rows="3">Search engine optimization to improve visibility and drive more organic traffic.</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="update_services" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Testimonials Section Tab Content -->
                <div id="testimonialsContent" class="tab-content" style="display: none;">
                    <form action="content.php" method="POST" class="admin-form">
                        <div class="form-section">
                            <h3>Testimonials Section</h3>
                            <div class="form-group">
                                <label for="testimonials_title">Section Title</label>
                                <input type="text" id="testimonials_title" name="testimonials_title" value="Testimonials">
                            </div>
                            
                            <!-- Testimonial Items -->
                            <div class="form-section">
                                <h4>Testimonial 1</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="testimonial1_name">Name</label>
                                        <input type="text" id="testimonial1_name" name="testimonial1_name" value="Sarah Johnson">
                                    </div>
                                    <div class="form-group">
                                        <label for="testimonial1_position">Position</label>
                                        <input type="text" id="testimonial1_position" name="testimonial1_position" value="CEO, TechStart">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="testimonial1_text">Testimonial Text</label>
                                    <textarea id="testimonial1_text" name="testimonial1_text" rows="3">"Shariar created an exceptional website for my business. His attention to detail and understanding of my needs resulted in a site that perfectly represents my brand."</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Testimonial Image</label>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <img src="../images/testimonial1.jpg" alt="Testimonial 1" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                                        <button type="button" class="btn btn-secondary" onclick="window.location.href='media.php'">
                                            <i class="fas fa-exchange-alt"></i> Change Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h4>Testimonial 2</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="testimonial2_name">Name</label>
                                        <input type="text" id="testimonial2_name" name="testimonial2_name" value="Michael Davis">
                                    </div>
                                    <div class="form-group">
                                        <label for="testimonial2_position">Position</label>
                                        <input type="text" id="testimonial2_position" name="testimonial2_position" value="Marketing Director, GrowthLabs">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="testimonial2_text">Testimonial Text</label>
                                    <textarea id="testimonial2_text" name="testimonial2_text" rows="3">"Working with Shariar was a pleasure. He delivered the project on time and was always available to address any concerns. The final product exceeded my expectations."</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Testimonial Image</label>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <img src="../images/testimonial2.jpg" alt="Testimonial 2" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                                        <button type="button" class="btn btn-secondary" onclick="window.location.href='media.php'">
                                            <i class="fas fa-exchange-alt"></i> Change Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h4>Testimonial 3</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="testimonial3_name">Name</label>
                                        <input type="text" id="testimonial3_name" name="testimonial3_name" value="Emily Rodriguez">
                                    </div>
                                    <div class="form-group">
                                        <label for="testimonial3_position">Position</label>
                                        <input type="text" id="testimonial3_position" name="testimonial3_position" value="Product Manager, AppInnovate">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="testimonial3_text">Testimonial Text</label>
                                    <textarea id="testimonial3_text" name="testimonial3_text" rows="3">"I'm extremely impressed with the mobile application Shariar developed for us. It's intuitive, fast, and our customers love it. His technical skills are top-notch."</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Testimonial Image</label>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <img src="../images/testimonial3.jpg" alt="Testimonial 3" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                                        <button type="button" class="btn btn-secondary" onclick="window.location.href='media.php'">
                                            <i class="fas fa-exchange-alt"></i> Change Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="update_testimonials" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Contact Section Tab Content -->
                <div id="contactContent" class="tab-content" style="display: none;">
                    <form action="content.php" method="POST" class="admin-form">
                        <div class="form-section">
                            <h3>Contact Section</h3>
                            <div class="form-group">
                                <label for="contact_title">Section Title</label>
                                <input type="text" id="contact_title" name="contact_title" value="Contact Me">
                            </div>
                            <div class="form-group">
                                <label for="contact_intro">Introduction Text</label>
                                <textarea id="contact_intro" name="contact_intro" rows="3">Feel free to reach out if you're looking to collaborate, have a project in mind, or just want to connect.</textarea>
                            </div>
                            
                            <div class="form-section">
                                <h4>Contact Information</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="contact_email">Email</label>
                                        <input type="email" id="contact_email" name="contact_email" value="shariar.arafat@example.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_phone">Phone</label>
                                        <input type="text" id="contact_phone" name="contact_phone" value="+1 (123) 456-7890">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="contact_location">Location</label>
                                    <input type="text" id="contact_location" name="contact_location" value="New York, NY">
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h4>Social Media Links</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="social_linkedin">LinkedIn</label>
                                        <input type="url" id="social_linkedin" name="social_linkedin" value="#">
                                    </div>
                                    <div class="form-group">
                                        <label for="social_github">GitHub</label>
                                        <input type="url" id="social_github" name="social_github" value="#">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="social_twitter">Twitter</label>
                                        <input type="url" id="social_twitter" name="social_twitter" value="#">
                                    </div>
                                    <div class="form-group">
                                        <label for="social_instagram">Instagram</label>
                                        <input type="url" id="social_instagram" name="social_instagram" value="#">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="update_contact" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Tab Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = ['hero', 'about', 'education', 'services', 'testimonials', 'contact'];
            
            tabs.forEach(tab => {
                document.getElementById(tab + 'Tab').addEventListener('click', function() {
                    // Hide all tab contents
                    tabs.forEach(t => {
                        document.getElementById(t + 'Content').style.display = 'none';
                        document.getElementById(t + 'Tab').style.borderBottom = '3px solid transparent';
                        document.getElementById(t + 'Tab').classList.remove('active');
                    });
                    
                    // Show the selected tab content
                    document.getElementById(tab + 'Content').style.display = 'block';
                    document.getElementById(tab + 'Tab').style.borderBottom = '3px solid var(--primary-color)';
                    document.getElementById(tab + 'Tab').classList.add('active');
                });
            });
        });
    </script>
</body>
</html> 