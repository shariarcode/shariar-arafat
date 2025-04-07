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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Password change
    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // In a real app, you'd verify the current password against stored hash
        // and validate the new password strength
        
        if ($newPassword !== $confirmPassword) {
            $message = "New passwords do not match!";
            $messageType = "error";
        } else {
            // Simulate successful password change
            $message = "Password changed successfully!";
            $messageType = "success";
        }
    }
    
    // Website settings
    if (isset($_POST['update_settings'])) {
        $siteName = $_POST['site_name'];
        $siteTagline = $_POST['site_tagline'];
        $primaryColor = $_POST['primary_color'];
        $accentColor = $_POST['accent_color'];
        $googleAnalytics = $_POST['google_analytics'];
        
        // In a real app, you'd save these settings to a config file or database
        
        $message = "Website settings updated successfully!";
        $messageType = "success";
    }
    
    // Social media settings
    if (isset($_POST['update_social'])) {
        $linkedin = $_POST['linkedin'];
        $github = $_POST['github'];
        $twitter = $_POST['twitter'];
        $instagram = $_POST['instagram'];
        
        // In a real app, you'd save these settings to a config file or database
        
        $message = "Social media links updated successfully!";
        $messageType = "success";
    }
}

// Sample settings data
$settings = [
    'site_name' => 'Shariar Arafat',
    'site_tagline' => 'Web Developer & Designer',
    'primary_color' => '#3b82f6',
    'accent_color' => '#10b981',
    'google_analytics' => 'UA-XXXXXXXXX-X',
    'social' => [
        'linkedin' => 'https://linkedin.com/in/username',
        'github' => 'https://github.com/username',
        'twitter' => 'https://twitter.com/username',
        'instagram' => 'https://instagram.com/username'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Shariar Arafat Portfolio</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <a href="content.php">Content</a>
                <a href="projects.php">Projects</a>
                <a href="media.php">Media</a>
                <a href="settings.php" class="active">Settings</a>
                <a href="logout.php" style="color: #ef4444;">Logout</a>
            </nav>
        </header>
        
        <!-- Settings Content -->
        <div class="admin-content">
            <div class="admin-title">
                <h2>Settings</h2>
                <p>Manage your account settings and website configuration</p>
            </div>
            
            <?php if(!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="admin-tabs">
                <div class="tab-navigation">
                    <button class="tab-btn active" data-tab="account">Account</button>
                    <button class="tab-btn" data-tab="website">Website</button>
                    <button class="tab-btn" data-tab="social">Social Media</button>
                </div>
                
                <!-- Account Settings Tab -->
                <div class="tab-content active" id="account">
                    <div class="admin-card">
                        <form action="settings.php" method="POST" class="admin-form">
                            <div class="form-section">
                                <h3>Account Settings</h3>
                                <div class="form-group">
                                    <label for="admin_email">Email</label>
                                    <input type="email" id="admin_email" name="admin_email" value="admin@example.com" disabled>
                                    <small>Email cannot be changed. Contact system administrator for assistance.</small>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Change Password</h3>
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input type="password" id="new_password" name="new_password" required>
                                    <small>Use at least 8 characters with a mix of letters, numbers, and symbols</small>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" required>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="change_password" class="btn btn-primary">
                                        <i class="fas fa-key"></i> Change Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Website Settings Tab -->
                <div class="tab-content" id="website">
                    <div class="admin-card">
                        <form action="settings.php" method="POST" class="admin-form">
                            <div class="form-section">
                                <h3>General Settings</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="site_name">Site Name</label>
                                        <input type="text" id="site_name" name="site_name" value="<?php echo $settings['site_name']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="site_tagline">Tagline</label>
                                        <input type="text" id="site_tagline" name="site_tagline" value="<?php echo $settings['site_tagline']; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Appearance</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="primary_color">Primary Color</label>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <input type="color" id="primary_color" name="primary_color" value="<?php echo $settings['primary_color']; ?>" style="width: 50px; height: 30px;">
                                            <input type="text" value="<?php echo $settings['primary_color']; ?>" style="width: 100px;" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="accent_color">Accent Color</label>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <input type="color" id="accent_color" name="accent_color" value="<?php echo $settings['accent_color']; ?>" style="width: 50px; height: 30px;">
                                            <input type="text" value="<?php echo $settings['accent_color']; ?>" style="width: 100px;" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Analytics</h3>
                                <div class="form-group">
                                    <label for="google_analytics">Google Analytics Tracking ID</label>
                                    <input type="text" id="google_analytics" name="google_analytics" value="<?php echo $settings['google_analytics']; ?>" placeholder="UA-XXXXXXXXX-X or G-XXXXXXXXXX">
                                    <small>Leave blank if you don't use Google Analytics</small>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="update_settings" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Social Media Tab -->
                <div class="tab-content" id="social">
                    <div class="admin-card">
                        <form action="settings.php" method="POST" class="admin-form">
                            <div class="form-section">
                                <h3>Social Media Links</h3>
                                <p>Add your social media links to display them on your portfolio</p>
                                
                                <div class="form-group">
                                    <label for="linkedin">
                                        <i class="fab fa-linkedin"></i> LinkedIn
                                    </label>
                                    <input type="url" id="linkedin" name="linkedin" value="<?php echo $settings['social']['linkedin']; ?>" placeholder="https://linkedin.com/in/username">
                                </div>
                                
                                <div class="form-group">
                                    <label for="github">
                                        <i class="fab fa-github"></i> GitHub
                                    </label>
                                    <input type="url" id="github" name="github" value="<?php echo $settings['social']['github']; ?>" placeholder="https://github.com/username">
                                </div>
                                
                                <div class="form-group">
                                    <label for="twitter">
                                        <i class="fab fa-twitter"></i> Twitter
                                    </label>
                                    <input type="url" id="twitter" name="twitter" value="<?php echo $settings['social']['twitter']; ?>" placeholder="https://twitter.com/username">
                                </div>
                                
                                <div class="form-group">
                                    <label for="instagram">
                                        <i class="fab fa-instagram"></i> Instagram
                                    </label>
                                    <input type="url" id="instagram" name="instagram" value="<?php echo $settings['social']['instagram']; ?>" placeholder="https://instagram.com/username">
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="update_social" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Social Links
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Tab Navigation
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Add active class to clicked button
                button.classList.add('active');
                
                // Hide all tab content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Show corresponding tab content
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</body>
</html> 