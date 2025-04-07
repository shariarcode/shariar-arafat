<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Sample data for the dashboard
$stats = [
    'projects' => 4,
    'total_media' => 12,
    'testimonials' => 3
];

// File counts (in a real implementation, these would be calculated)
$imageCount = 8;
$documentCount = 4;

// Recent projects (in a real implementation, these would come from a database)
$recentProjects = [
    [
        'id' => 4,
        'title' => 'Fitness Tracker App',
        'date' => '2023-11-15',
        'category' => 'app'
    ],
    [
        'id' => 3,
        'title' => 'Analytics Dashboard',
        'date' => '2023-10-22',
        'category' => 'web'
    ],
    [
        'id' => 2,
        'title' => 'Travel App UI Design',
        'date' => '2023-09-05',
        'category' => 'design'
    ]
];

// Recent activities (in a real implementation, these would be logged)
$recentActivities = [
    [
        'action' => 'Updated project',
        'item' => 'Fitness Tracker App',
        'date' => '2023-11-20 14:35:22',
        'icon' => 'fa-edit',
        'color' => 'var(--primary-color)'
    ],
    [
        'action' => 'Uploaded new image',
        'item' => 'dashboard-screenshot.jpg',
        'date' => '2023-11-18 10:12:45',
        'icon' => 'fa-image',
        'color' => 'var(--accent-color)'
    ],
    [
        'action' => 'Updated content',
        'item' => 'About section',
        'date' => '2023-11-17 16:40:18',
        'icon' => 'fa-paragraph',
        'color' => '#10b981'
    ],
    [
        'action' => 'Added new project',
        'item' => 'Fitness Tracker App',
        'date' => '2023-11-15 09:22:37',
        'icon' => 'fa-plus',
        'color' => '#6366f1'
    ],
    [
        'action' => 'Changed settings',
        'item' => 'Updated social media links',
        'date' => '2023-11-12 11:05:59',
        'icon' => 'fa-cog',
        'color' => '#8b5cf6'
    ]
];

// Format date function
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('M d, Y');
}

// Format datetime function
function formatDateTime($dateTimeString) {
    $dateTime = new DateTime($dateTimeString);
    $now = new DateTime();
    $interval = $now->diff($dateTime);
    
    if ($interval->days == 0) {
        if ($interval->h == 0) {
            if ($interval->i == 0) {
                return "Just now";
            }
            return $interval->i . " minute" . ($interval->i == 1 ? "" : "s") . " ago";
        }
        return $interval->h . " hour" . ($interval->h == 1 ? "" : "s") . " ago";
    } else if ($interval->days == 1) {
        return "Yesterday";
    } else if ($interval->days < 7) {
        return $interval->days . " days ago";
    } else {
        return $dateTime->format('M d, Y');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Shariar Arafat Portfolio</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="admin-logo">
                <h1>Portfolio Admin</h1>
            </div>
            <nav class="admin-menu">
                <a href="dashboard.php" class="active">Dashboard</a>
                <a href="content.php">Content</a>
                <a href="projects.php">Projects</a>
                <a href="media.php">Media</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php" style="color: #ef4444;">Logout</a>
            </nav>
        </header>
        
        <!-- Dashboard Content -->
        <div class="admin-content">
            <div class="admin-title">
                <h2>Dashboard</h2>
                <p>Welcome back! Here's an overview of your portfolio</p>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(59, 130, 246, 0.1); color: var(--primary-color);">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Projects</h3>
                        <p class="stat-number"><?php echo $stats['projects']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Media Files</h3>
                        <p class="stat-number"><?php echo $stats['total_media']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Testimonials</h3>
                        <p class="stat-number"><?php echo $stats['testimonials']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Page Views</h3>
                        <p class="stat-number">--</p>
                        <small>Connect analytics for data</small>
                    </div>
                </div>
            </div>
            
            <!-- Charts and Recent Activity -->
            <div class="dashboard-grid">
                <!-- Charts Section -->
                <div class="admin-card">
                    <div class="card-header">
                        <h3>Media Files Overview</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="mediaChart" height="200"></canvas>
                    </div>
                </div>
                
                <!-- Recent Projects -->
                <div class="admin-card">
                    <div class="card-header">
                        <h3>Recent Projects</h3>
                        <a href="projects.php" class="view-all">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="recent-projects-list">
                            <?php foreach($recentProjects as $project): ?>
                                <div class="recent-project-item">
                                    <div class="project-info">
                                        <h4><?php echo $project['title']; ?></h4>
                                        <div class="project-meta">
                                            <span class="project-date">
                                                <i class="far fa-calendar-alt"></i> 
                                                <?php echo formatDate($project['date']); ?>
                                            </span>
                                            <span class="project-category">
                                                <i class="fas fa-tag"></i> 
                                                <?php echo ucfirst($project['category']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <a href="projects.php?action=edit&id=<?php echo $project['id']; ?>" class="btn-icon">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="admin-card">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="projects.php?action=new" class="quick-action-btn">
                                <i class="fas fa-plus"></i>
                                <span>Add Project</span>
                            </a>
                            <a href="media.php" class="quick-action-btn">
                                <i class="fas fa-upload"></i>
                                <span>Upload Media</span>
                            </a>
                            <a href="content.php" class="quick-action-btn">
                                <i class="fas fa-edit"></i>
                                <span>Edit Content</span>
                            </a>
                            <a href="settings.php" class="quick-action-btn">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="admin-card">
                    <div class="card-header">
                        <h3>Recent Activity</h3>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            <?php foreach($recentActivities as $activity): ?>
                                <div class="activity-item">
                                    <div class="activity-icon" style="background-color: <?php echo $activity['color']; ?>">
                                        <i class="fas <?php echo $activity['icon']; ?>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p><strong><?php echo $activity['action']; ?></strong>: <?php echo $activity['item']; ?></p>
                                        <small><?php echo formatDateTime($activity['date']); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Media Files Chart
        const mediaChartCtx = document.getElementById('mediaChart').getContext('2d');
        const mediaChart = new Chart(mediaChartCtx, {
            type: 'doughnut',
            data: {
                labels: ['Images', 'Documents'],
                datasets: [{
                    data: [<?php echo $imageCount; ?>, <?php echo $documentCount; ?>],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(139, 92, 246, 0.8)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(139, 92, 246, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html> 