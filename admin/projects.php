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
$action = isset($_GET['action']) ? $_GET['action'] : '';
$projectId = isset($_GET['id']) ? $_GET['id'] : '';

// Sample project data (in a real implementation, this would come from a database)
$projects = [
    [
        'id' => 1,
        'title' => 'E-commerce Platform',
        'category' => 'web',
        'image' => 'project1.jpg',
        'description' => 'A fully responsive e-commerce website with shopping cart functionality and payment integration.',
        'technologies' => ['React', 'Node.js', 'MongoDB'],
        'project_url' => '#',
        'github_url' => '#'
    ],
    [
        'id' => 2,
        'title' => 'Travel App UI Design',
        'category' => 'design',
        'image' => 'project2.jpg',
        'description' => 'A clean and modern UI design for a travel booking application with user experience in mind.',
        'technologies' => ['Figma', 'Adobe XD', 'Prototyping'],
        'project_url' => '#',
        'github_url' => ''
    ],
    [
        'id' => 3,
        'title' => 'Analytics Dashboard',
        'category' => 'web',
        'image' => 'project3.jpg',
        'description' => 'An interactive dashboard for data visualization with real-time updates and user authentication.',
        'technologies' => ['JavaScript', 'Chart.js', 'Firebase'],
        'project_url' => '#',
        'github_url' => '#'
    ],
    [
        'id' => 4,
        'title' => 'Fitness Tracker App',
        'category' => 'app',
        'image' => 'project4.jpg',
        'description' => 'A mobile application that helps users track their fitness goals and daily activities.',
        'technologies' => ['React Native', 'Redux', 'Node.js'],
        'project_url' => '#',
        'github_url' => '#'
    ]
];

// Handle form submission for project operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_project']) || isset($_POST['update_project'])) {
        // In a real implementation, you'd save this to a database
        // For this demo, we'll simulate a success message
        $message = isset($_POST['add_project']) ? "Project added successfully!" : "Project updated successfully!";
        $messageType = "success";
        
        // Redirect to projects list
        header("Location: projects.php");
        exit;
    }
}

// Get project data for editing
$currentProject = [];
if ($action === 'edit' && !empty($projectId)) {
    foreach ($projects as $project) {
        if ($project['id'] == $projectId) {
            $currentProject = $project;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Manager | Shariar Arafat Portfolio</title>
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
                <a href="projects.php" class="active">Projects</a>
                <a href="media.php">Media</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php" style="color: #ef4444;">Logout</a>
            </nav>
        </header>
        
        <!-- Projects Content -->
        <div class="admin-content">
            <div class="admin-title">
                <h2><?php echo $action === 'new' ? 'Add New Project' : ($action === 'edit' ? 'Edit Project' : 'Projects Manager'); ?></h2>
                <p><?php echo $action === 'new' ? 'Add a new project to your portfolio' : ($action === 'edit' ? 'Edit an existing project' : 'Manage your portfolio projects'); ?></p>
            </div>
            
            <?php if(!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if($action === 'new' || $action === 'edit'): ?>
                <!-- Project Form -->
                <div class="admin-card">
                    <form action="projects.php" method="POST" class="admin-form">
                        <?php if($action === 'edit'): ?>
                            <input type="hidden" name="project_id" value="<?php echo $currentProject['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-section">
                            <h3>Project Details</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="project_title">Project Title</label>
                                    <input type="text" id="project_title" name="project_title" value="<?php echo $action === 'edit' ? $currentProject['title'] : ''; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="project_category">Category</label>
                                    <select id="project_category" name="project_category" required>
                                        <option value="">Select Category</option>
                                        <option value="web" <?php echo ($action === 'edit' && $currentProject['category'] === 'web') ? 'selected' : ''; ?>>Web Development</option>
                                        <option value="design" <?php echo ($action === 'edit' && $currentProject['category'] === 'design') ? 'selected' : ''; ?>>UI/UX Design</option>
                                        <option value="app" <?php echo ($action === 'edit' && $currentProject['category'] === 'app') ? 'selected' : ''; ?>>Mobile Apps</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="project_description">Description</label>
                                <textarea id="project_description" name="project_description" rows="4" required><?php echo $action === 'edit' ? $currentProject['description'] : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="project_technologies">Technologies (comma separated)</label>
                                <input type="text" id="project_technologies" name="project_technologies" value="<?php echo $action === 'edit' ? implode(', ', $currentProject['technologies']) : ''; ?>" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="project_url">Project URL</label>
                                    <input type="url" id="project_url" name="project_url" value="<?php echo $action === 'edit' ? $currentProject['project_url'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="github_url">GitHub URL (optional)</label>
                                    <input type="url" id="github_url" name="github_url" value="<?php echo $action === 'edit' ? $currentProject['github_url'] : ''; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="project_image">Project Image</label>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <?php if($action === 'edit' && !empty($currentProject['image'])): ?>
                                        <img src="../images/<?php echo $currentProject['image']; ?>" alt="Project Image" style="width: 120px; height: 80px; object-fit: cover; border-radius: 5px;">
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='media.php'">
                                        <?php echo $action === 'edit' ? '<i class="fas fa-exchange-alt"></i> Change Image' : '<i class="fas fa-upload"></i> Upload Image'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="projects.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="<?php echo $action === 'edit' ? 'update_project' : 'add_project'; ?>" class="btn btn-primary">
                                <?php echo $action === 'edit' ? '<i class="fas fa-save"></i> Update Project' : '<i class="fas fa-plus"></i> Add Project'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Projects List -->
                <div class="admin-card">
                    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
                        <a href="projects.php?action=new" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Project
                        </a>
                    </div>
                    
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Image</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Technologies</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($projects as $project): ?>
                                <tr>
                                    <td>
                                        <img src="../images/<?php echo $project['image']; ?>" alt="<?php echo $project['title']; ?>" style="width: 50px; height: 30px; object-fit: cover; border-radius: 3px;">
                                    </td>
                                    <td><?php echo $project['title']; ?></td>
                                    <td>
                                        <span style="text-transform: capitalize;">
                                            <?php echo $project['category']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php foreach($project['technologies'] as $tech): ?>
                                            <span style="display: inline-block; background-color: var(--primary-color); color: white; font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 3px; margin-right: 0.5rem; margin-bottom: 0.25rem;"><?php echo $tech; ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td class="table-actions">
                                        <a href="projects.php?action=edit&id=<?php echo $project['id']; ?>" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to delete this project?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 