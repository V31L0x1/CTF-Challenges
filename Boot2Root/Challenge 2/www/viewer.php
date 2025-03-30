<?php
// Simple File Viewer - Allows viewing files on the server
// WARNING: This has a Local File Inclusion vulnerability!

$title = "File Viewer";
$file = isset($_GET['file']) ? $_GET['file'] : 'welcome.txt';

// Read file contents
$contents = @file_get_contents($file);
$error = "";

if ($contents === false) {
    $error = "Error: Could not read the file '$file'";
    $contents = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paranormal Investigation Agency - <?php echo htmlspecialchars($title); ?></title>
    <style>
        :root {
            --primary-bg: #0f0f13;
            --secondary-bg: #1a1a24;
            --header-bg: #141420;
            --accent-color: #7b00ff;
            --text-color: #e0e0e0;
            --link-color: #9d68fe;
            --shadow-color: rgba(123, 0, 255, 0.2);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .container {
            width: 85%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 0;
        }
        
        header {
            background-color: var(--header-bg);
            padding: 1.5rem 0;
            box-shadow: 0 4px 15px var(--shadow-color);
            position: relative;
            overflow: hidden;
        }
        
        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(123, 0, 255, 0.1)" stroke-width="1" /></svg>');
            opacity: 0.5;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }
        
        h1 {
            color: var(--accent-color);
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 10px rgba(123, 0, 255, 0.5);
        }
        
        .tagline {
            font-style: italic;
            font-size: 1.1rem;
            color: #b8b8b8;
        }
        
        .nav {
            background-color: var(--secondary-bg);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .nav-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .nav a {
            color: var(--text-color);
            text-decoration: none;
            padding: 0.5rem 1.5rem;
            margin: 0 0.2rem;
            border-radius: 3px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav a:hover {
            color: var(--accent-color);
        }
        
        .nav a:hover::after {
            width: 80%;
        }
        
        .nav a.active {
            color: var(--accent-color);
        }
        
        .nav a.active::after {
            width: 80%;
        }
        
        main {
            padding: 2rem 0;
        }
        
        h2 {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, var(--accent-color), transparent);
        }
        
        .viewer-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 800px) {
            .viewer-layout {
                grid-template-columns: 1fr;
            }
        }
        
        .file-list {
            background-color: var(--secondary-bg);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .file-list::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(123, 0, 255, 0.1) 0%, transparent 70%);
            z-index: 0;
        }
        
        .file-list h3 {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(123, 0, 255, 0.2);
            position: relative;
            z-index: 1;
        }
        
        .file-item {
            margin-bottom: 0.8rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .file-item a {
            color: var(--text-color);
            text-decoration: none;
            display: block;
            padding: 0.8rem;
            border-radius: 5px;
            background-color: rgba(123, 0, 255, 0.05);
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .file-item a:hover {
            background-color: rgba(123, 0, 255, 0.1);
            border-left: 3px solid var(--accent-color);
            color: var(--accent-color);
            transform: translateX(5px);
        }
        
        .file-viewer {
            background-color: var(--secondary-bg);
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .file-viewer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><circle cx="5" cy="5" r="1" fill="rgba(123, 0, 255, 0.05)"/></svg>');
            opacity: 0.5;
            z-index: 0;
        }
        
        .file-header {
            position: relative;
            z-index: 1;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(123, 0, 255, 0.2);
        }
        
        .file-header h3 {
            color: var(--accent-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .file-path {
            font-family: 'Courier New', monospace;
            background-color: rgba(15, 15, 19, 0.7);
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            margin-left: 1rem;
            font-size: 0.9rem;
            color: var(--link-color);
            border-left: 2px solid var(--accent-color);
        }
        
        .error {
            color: #ff6b6b;
            font-weight: bold;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: rgba(255, 107, 107, 0.1);
            border-radius: 4px;
            border-left: 3px solid #ff6b6b;
        }
        
        .file-content {
            position: relative;
            z-index: 1;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            background-color: rgba(15, 15, 19, 0.7);
            padding: 1.5rem;
            border-radius: 5px;
            border-left: 3px solid var(--accent-color);
            line-height: 1.5;
            overflow-x: auto;
            max-height: 600px;
            overflow-y: auto;
        }
        
        .empty-message {
            text-align: center;
            padding: 2rem;
            color: #888;
            font-style: italic;
        }
        
        .security-note {
            margin-top: 2rem;
            padding: 1rem;
            background-color: rgba(15, 15, 19, 0.5);
            border-radius: 5px;
            border-left: 3px solid #ff6b6b;
            font-size: 0.9rem;
            color: #888;
        }
        
        footer {
            background-color: var(--header-bg);
            padding: 2rem 0;
            text-align: center;
            margin-top: 3rem;
            position: relative;
        }
        
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, transparent, var(--accent-color), transparent);
        }
        
        .glow-effect {
            animation: glow 2s infinite alternate;
        }
        
        @keyframes glow {
            from {
                text-shadow: 0 0 5px rgba(123, 0, 255, 0.5);
            }
            to {
                text-shadow: 0 0 15px rgba(123, 0, 255, 0.8);
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content container">
            <h1>Paranormal Investigation Agency</h1>
            <p class="tagline">Exploring the Beyond Since 1999</p>
        </div>
    </header>
    
    <nav class="nav">
        <div class="nav-container container">
            <a href="index.php">Home</a>
            <a href="reports.php">Reports</a>
            <a href="gallery.php">Evidence Gallery</a>
            <a href="viewer.php" class="active">File Viewer</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </nav>
    
    <main class="container">
        <h2>Secure File Viewer</h2>
        <p>This tool allows investigators to view evidence files and documentation stored on the server. For security reasons, only authorized personnel should use this feature.</p>
        
        <div class="viewer-layout">
            <div class="file-list">
                <h3>Available Files</h3>
                <div class="file-item">
                    <a href="viewer.php?file=welcome.txt">Welcome Message</a>
                </div>
                <div class="file-item">
                    <a href="viewer.php?file=reports/ghost_sighting_1.txt">Ghost Sighting #1</a>
                </div>
                <div class="file-item">
                    <a href="viewer.php?file=reports/ghost_sighting_2.txt">Ghost Sighting #2</a>
                </div>
                <div class="file-item">
                    <a href="viewer.php?file=reports/equipment_logs.txt">Equipment Logs</a>
                </div>
                <div class="file-item">
                    <a href="viewer.php?file=reports/evp_analysis.txt">EVP Analysis</a>
                </div>
                <div class="file-item">
                    <a href="viewer.php?file=/etc/passwd">System Users</a>
                </div>
            </div>
            
            <div class="file-viewer">
                <div class="file-header">
                    <h3>
                        <span class="glow-effect">Viewing File:</span>
                        <span class="file-path"><?php echo htmlspecialchars($file); ?></span>
                    </h3>
                    <?php if (!empty($error)): ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="file-content">
                    <?php if (!empty($contents)): ?>
                        <?php echo htmlspecialchars($contents); ?>
                    <?php else: ?>
                        <div class="empty-message">File is empty or could not be read.</div>
                    <?php endif; ?>
                </div>
                
                <div class="security-note">
                    <p>Note: For security purposes, all file access is logged. Only access files you have clearance to view.</p>
                    <p>If you encounter any issues, please contact the IT department at extension 5432.</p>
                </div>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>Paranormal Investigation Agency &copy; 2023 - "We see what technology cannot hide"</p>
        </div>
    </footer>
</body>
</html> 