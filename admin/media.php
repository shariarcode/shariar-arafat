<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Create directories if they don't exist
if (!file_exists('uploads/images')) {
    mkdir('uploads/images', 0777, true);
}
if (!file_exists('uploads/files')) {
    mkdir('uploads/files', 0777, true);
}

// Initialize variables
$message = '';
$messageType = '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        
        // Get file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Set allowed extensions
        $allowedImageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $allowedFileExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip'];
        
        // Determine file type and destination
        $isImage = in_array($fileExt, $allowedImageExts);
        $isAllowedFile = in_array($fileExt, $allowedFileExts);
        
        if ($isImage || $isAllowedFile) {
            // Generate unique filename
            $newFileName = uniqid('', true) . '.' . $fileExt;
            
            // Set destination path
            $destination = $isImage ? 'uploads/images/' . $newFileName : 'uploads/files/' . $newFileName;
            
            // Try to move the uploaded file
            if (move_uploaded_file($fileTmpName, $destination)) {
                $message = "File uploaded successfully!";
                $messageType = "success";
            } else {
                $message = "Failed to upload file.";
                $messageType = "error";
            }
        } else {
            $message = "Invalid file type. Allowed image types: " . implode(', ', $allowedImageExts) . ". Allowed file types: " . implode(', ', $allowedFileExts) . ".";
            $messageType = "error";
        }
    } else {
        $message = "Error: " . $_FILES['file']['error'];
        $messageType = "error";
    }
}

// Handle file deletion
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $fileToDelete = $_GET['delete'];
    $filePath = '';
    
    // Check if file exists in images directory
    if (file_exists('uploads/images/' . $fileToDelete)) {
        $filePath = 'uploads/images/' . $fileToDelete;
    } 
    // Check if file exists in files directory
    elseif (file_exists('uploads/files/' . $fileToDelete)) {
        $filePath = 'uploads/files/' . $fileToDelete;
    }
    
    if (!empty($filePath) && unlink($filePath)) {
        $message = "File deleted successfully!";
        $messageType = "success";
    } else {
        $message = "Failed to delete file.";
        $messageType = "error";
    }
}

// Get files from uploads directories
function getFiles($directory) {
    $files = array();
    if (file_exists($directory)) {
        foreach (scandir($directory) as $file) {
            if ($file !== '.' && $file !== '..') {
                $files[] = $file;
            }
        }
    }
    return $files;
}

$imageFiles = getFiles('uploads/images');
$docFiles = getFiles('uploads/files');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Manager | Shariar Arafat Portfolio</title>
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
                <a href="media.php" class="active">Media</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php" style="color: #ef4444;">Logout</a>
            </nav>
        </header>
        
        <!-- Media Content -->
        <div class="admin-content">
            <div class="admin-title">
                <h2>Media Manager</h2>
                <p>Upload and manage images and files for your portfolio</p>
            </div>
            
            <?php if(!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Upload Section -->
            <div class="admin-card">
                <h3 class="admin-card-title">Upload New Media</h3>
                
                <form action="media.php" method="POST" enctype="multipart/form-data">
                    <div class="file-upload-container" id="dropZone">
                        <div class="file-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="file-upload-text">
                            <p>Drag & drop files here or click to browse</p>
                            <p style="font-size: 0.8rem; color: var(--text-gray);">Supported formats: JPG, PNG, GIF, PDF, DOC, XLS, etc. (Max 10MB)</p>
                        </div>
                        <input type="file" name="file" id="fileInput" style="display: none;">
                    </div>
                    
                    <div id="filePreview" style="display: none; margin-bottom: 1.5rem;">
                        <h4 style="margin-bottom: 1rem;">Selected File:</h4>
                        <div id="previewContent" style="display: flex; align-items: center; gap: 1rem;"></div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="upload" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload File
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Media Library -->
            <div class="admin-card">
                <h3 class="admin-card-title">Media Library</h3>
                
                <!-- Tabs -->
                <div style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
                    <button id="imagesTab" class="tablink active" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; font-weight: 500; border-bottom: 3px solid transparent;">
                        Images (<?php echo count($imageFiles); ?>)
                    </button>
                    <button id="filesTab" class="tablink" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; font-weight: 500; border-bottom: 3px solid transparent;">
                        Files (<?php echo count($docFiles); ?>)
                    </button>
                </div>
                
                <!-- Images Tab -->
                <div id="imagesContent" class="tab-content">
                    <?php if(empty($imageFiles)): ?>
                        <p style="text-align: center; padding: 2rem; color: var(--text-gray);">No images uploaded yet.</p>
                    <?php else: ?>
                        <div class="file-preview">
                            <?php foreach($imageFiles as $image): ?>
                                <div class="file-item">
                                    <img src="uploads/images/<?php echo $image; ?>" alt="<?php echo $image; ?>">
                                    <div class="file-item-info">
                                        <?php echo substr($image, 0, 15) . (strlen($image) > 15 ? '...' : ''); ?>
                                    </div>
                                    <div class="file-item-actions">
                                        <a href="uploads/images/<?php echo $image; ?>" target="_blank" class="file-action-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="media.php?delete=<?php echo $image; ?>" class="file-action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this file?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Files Tab -->
                <div id="filesContent" class="tab-content" style="display: none;">
                    <?php if(empty($docFiles)): ?>
                        <p style="text-align: center; padding: 2rem; color: var(--text-gray);">No files uploaded yet.</p>
                    <?php else: ?>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($docFiles as $file): ?>
                                    <tr>
                                        <td><?php echo $file; ?></td>
                                        <td><?php echo strtoupper(pathinfo($file, PATHINFO_EXTENSION)); ?></td>
                                        <td><?php echo round(filesize('uploads/files/' . $file) / 1024, 2); ?> KB</td>
                                        <td class="table-actions">
                                            <a href="uploads/files/<?php echo $file; ?>" target="_blank" class="action-btn edit" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="media.php?delete=<?php echo $file; ?>" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to delete this file?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // File Upload Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('filePreview');
            const previewContent = document.getElementById('previewContent');
            
            // Click on drop zone to trigger file input
            dropZone.addEventListener('click', function() {
                fileInput.click();
            });
            
            // Handle drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                dropZone.style.borderColor = 'var(--primary-color)';
                dropZone.style.backgroundColor = 'rgba(79, 70, 229, 0.05)';
            }
            
            function unhighlight() {
                dropZone.style.borderColor = 'var(--border-color)';
                dropZone.style.backgroundColor = 'transparent';
            }
            
            // Handle files when dropped
            dropZone.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    fileInput.files = files;
                    showPreview(files[0]);
                }
            }
            
            // Handle file selection via input
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    showPreview(this.files[0]);
                }
            });
            
            // Show file preview
            function showPreview(file) {
                filePreview.style.display = 'block';
                previewContent.innerHTML = '';
                
                if (file.type.startsWith('image/')) {
                    // Image preview
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.maxHeight = '100px';
                    img.style.maxWidth = '100px';
                    img.style.borderRadius = '5px';
                    previewContent.appendChild(img);
                } else {
                    // File icon based on extension
                    const fileIcon = document.createElement('i');
                    fileIcon.className = getFileIcon(file.name);
                    fileIcon.style.fontSize = '48px';
                    fileIcon.style.color = 'var(--primary-color)';
                    previewContent.appendChild(fileIcon);
                }
                
                // File info
                const fileInfo = document.createElement('div');
                fileInfo.innerHTML = `
                    <p><strong>${file.name}</strong></p>
                    <p>${formatFileSize(file.size)}</p>
                `;
                previewContent.appendChild(fileInfo);
                
                // Remove button
                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.className = 'file-action-btn';
                removeBtn.style.marginLeft = 'auto';
                removeBtn.addEventListener('click', function() {
                    fileInput.value = '';
                    filePreview.style.display = 'none';
                });
                previewContent.appendChild(removeBtn);
            }
            
            // Get file icon based on extension
            function getFileIcon(filename) {
                const ext = filename.split('.').pop().toLowerCase();
                
                if (['pdf'].includes(ext)) {
                    return 'fas fa-file-pdf';
                } else if (['doc', 'docx'].includes(ext)) {
                    return 'fas fa-file-word';
                } else if (['xls', 'xlsx'].includes(ext)) {
                    return 'fas fa-file-excel';
                } else if (['ppt', 'pptx'].includes(ext)) {
                    return 'fas fa-file-powerpoint';
                } else if (['zip', 'rar', '7z'].includes(ext)) {
                    return 'fas fa-file-archive';
                } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    return 'fas fa-file-image';
                } else {
                    return 'fas fa-file';
                }
            }
            
            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
            
            // Tab switching
            const imagesTab = document.getElementById('imagesTab');
            const filesTab = document.getElementById('filesTab');
            const imagesContent = document.getElementById('imagesContent');
            const filesContent = document.getElementById('filesContent');
            
            imagesTab.addEventListener('click', function() {
                imagesTab.classList.add('active');
                filesTab.classList.remove('active');
                imagesContent.style.display = 'block';
                filesContent.style.display = 'none';
                
                imagesTab.style.borderBottom = '3px solid var(--primary-color)';
                filesTab.style.borderBottom = '3px solid transparent';
            });
            
            filesTab.addEventListener('click', function() {
                filesTab.classList.add('active');
                imagesTab.classList.remove('active');
                filesContent.style.display = 'block';
                imagesContent.style.display = 'none';
                
                filesTab.style.borderBottom = '3px solid var(--primary-color)';
                imagesTab.style.borderBottom = '3px solid transparent';
            });
            
            // Initialize tabs
            imagesTab.style.borderBottom = '3px solid var(--primary-color)';
        });
    </script>
</body>
</html> 