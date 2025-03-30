<?php
// Get available reports
$reportsDir = __DIR__ . '/reports/';
$reportFiles = glob($reportsDir . '*.txt');
$reports = [];

foreach ($reportFiles as $file) {
    $filename = basename($file);
    $title = str_replace('_', ' ', pathinfo($filename, PATHINFO_FILENAME));
    $title = ucwords($title);
    $reports[$filename] = $title;
}

// Get selected report
$selectedReport = isset($_GET['report']) ? $_GET['report'] : '';
$reportContent = '';
$reportTitle = '';

if (!empty($selectedReport) && file_exists($reportsDir . $selectedReport)) {
    $reportContent = file_get_contents($reportsDir . $selectedReport);
    $reportTitle = $reports[$selectedReport] ?? 'Unknown Report';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paranormal Investigation Agency - Reports</title>
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
        
        .reports-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
        }
        
        @media (max-width: 800px) {
            .reports-layout {
                grid-template-columns: 1fr;
            }
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
        
        .report-list {
            background-color: var(--secondary-bg);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .report-list h3 {
            color: var(--accent-color);
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(123, 0, 255, 0.2);
            padding-bottom: 0.5rem;
        }
        
        .report-item {
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .report-item a {
            color: var(--text-color);
            text-decoration: none;
            display: block;
            padding: 0.8rem;
            border-radius: 5px;
            background-color: rgba(123, 0, 255, 0.05);
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .report-item a:hover, .report-item a.active {
            background-color: rgba(123, 0, 255, 0.1);
            border-left: 3px solid var(--accent-color);
            color: var(--accent-color);
            transform: translateX(5px);
        }
        
        .report-content {
            background-color: var(--secondary-bg);
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .report-content::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(123, 0, 255, 0.1) 0%, transparent 70%);
            z-index: 0;
        }
        
        .report-content h3 {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(123, 0, 255, 0.2);
            position: relative;
            z-index: 1;
        }
        
        .report-text {
            white-space: pre-wrap;
            font-family: 'Courier New', Courier, monospace;
            line-height: 1.5;
            background-color: rgba(15, 15, 19, 0.7);
            padding: 1.5rem;
            border-radius: 5px;
            border-left: 3px solid var(--accent-color);
            position: relative;
            z-index: 1;
        }
        
        .restricted-notice {
            text-align: center;
            padding: 3rem;
            color: #ff6b6b;
            font-weight: bold;
            font-size: 1.2rem;
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
            text-shadow: 0 0 10px rgba(123, 0, 255, 0.5);
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
            <a href="reports.php" class="active">Reports</a>
            <a href="gallery.php">Evidence Gallery</a>
            <a href="viewer.php">File Viewer</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </nav>
    
    <main class="container">
        <h2>Investigation Reports</h2>
        <p>Below are selected reports from our paranormal investigations. These documents are declassified for educational purposes only.</p>
        
        <div class="reports-layout">
            <div class="report-list">
                <h3>Available Reports</h3>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $filename => $title): ?>
                        <div class="report-item">
                            <a href="reports.php?report=<?php echo htmlspecialchars($filename); ?>" 
                               class="<?php echo ($selectedReport === $filename) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($title); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="report-item">
                        <p>No reports available</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="report-content">
                <?php if (!empty($reportContent)): ?>
                    <h3 class="glow-effect"><?php echo htmlspecialchars($reportTitle); ?></h3>
                    <div class="report-text">
                        <?php echo htmlspecialchars($reportContent); ?>
                    </div>
                <?php else: ?>
                    <div class="restricted-notice">
                        <p>SELECT A REPORT FROM THE LIST</p>
                        <p>LEVEL 1 CLEARANCE ONLY</p>
                    </div>
                <?php endif; ?>
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