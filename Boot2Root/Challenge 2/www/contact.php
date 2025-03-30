<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paranormal Investigation Agency - Contact</title>
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
        
        .contact-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 800px) {
            .contact-layout {
                grid-template-columns: 1fr;
            }
        }
        
        .contact-info {
            background-color: var(--secondary-bg);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .contact-info::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(123, 0, 255, 0.1) 0%, transparent 70%);
            z-index: 0;
        }
        
        .contact-info h3 {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .contact-detail {
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .contact-label {
            font-weight: bold;
            color: var(--link-color);
            margin-bottom: 0.3rem;
        }
        
        .contact-value {
            padding-left: 1rem;
            border-left: 2px solid var(--accent-color);
        }
        
        .contact-form {
            background-color: var(--secondary-bg);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .contact-form h3 {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--link-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(123, 0, 255, 0.2);
            background-color: rgba(15, 15, 19, 0.7);
            color: var(--text-color);
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(123, 0, 255, 0.2);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        
        .btn:hover {
            background-color: #9d68fe;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(123, 0, 255, 0.3);
        }
        
        .note {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #888;
            padding-left: 1rem;
            border-left: 2px solid var(--accent-color);
        }
        
        .secure-note {
            text-align: center;
            padding: 1rem;
            background-color: rgba(15, 15, 19, 0.7);
            border-radius: 4px;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .secure-note::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(123, 0, 255, 0.1)" stroke-width="1" /></svg>');
            opacity: 0.2;
            z-index: 0;
        }
        
        .secure-note p {
            position: relative;
            z-index: 1;
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
        
        .hidden-message {
            color: transparent;
            user-select: none;
            font-size: 0;
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
            <a href="viewer.php">File Viewer</a>
            <a href="contact.php" class="active">Contact Us</a>
        </div>
    </nav>
    
    <main class="container">
        <h2>Contact the Paranormal Investigation Agency</h2>
        <p>If you've experienced digital hauntings or suspect paranormal activity in your electronic systems, our team of specialists is ready to assist. Please use the secure channels below to report incidents or request our services.</p>
        
        <div class="contact-layout">
            <div class="contact-info">
                <h3>Agency Contact Information</h3>
                
                <div class="contact-detail">
                    <div class="contact-label">Digital Emergencies Hotline:</div>
                    <div class="contact-value">1-800-GHOST-DATA (Available 24/7)</div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-label">Standard Inquiries:</div>
                    <div class="contact-value">contact@paranormal-agency.org</div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-label">Server Hauntings Division:</div>
                    <div class="contact-value">server-ghosts@paranormal-agency.org</div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-label">Network Spectral Analysis Team:</div>
                    <div class="contact-value">network@paranormal-agency.org</div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-label">Headquarters Location:</div>
                    <div class="contact-value">
                        1432 Ethereal Drive<br>
                        Cryptic Tower, Floor 13<br>
                        Shadowvale, NV 89701
                    </div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-label">Secure Communications:</div>
                    <div class="contact-value">
                        For sensitive cases, please use our encrypted communications portal. 
                        Access requires Level 2 clearance or higher.
                    </div>
                </div>
                
                <div class="note">
                    For technical support with our systems, please contact our IT department
                    at support@paranormal-agency.org or extension 5432.
                </div>
            </div>
            
            <div class="contact-form">
                <h3>Report an Incident</h3>
                <form action="#" method="post">
                    <div class="form-group">
                        <label for="name">Your Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="incident-type">Incident Type:</label>
                        <select id="incident-type" name="incident-type" class="form-control" required>
                            <option value="">Select Incident Type</option>
                            <option value="server-haunting">Server Haunting</option>
                            <option value="data-corruption">Unexplained Data Corruption</option>
                            <option value="system-possession">System Possession</option>
                            <option value="network-apparition">Network Apparition</option>
                            <option value="electronic-voice">Electronic Voice Phenomena</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Incident Description:</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="security-level">Security Clearance Level:</label>
                        <select id="security-level" name="security-level" class="form-control" required>
                            <option value="public">Public</option>
                            <option value="level-1">Level 1 - Basic</option>
                            <option value="level-2">Level 2 - Confidential</option>
                            <option value="level-3">Level 3 - Restricted</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn">Submit Report</button>
                    </div>
                </form>
                
                <div class="note">
                    All reports are encrypted and stored in our secure database. 
                    Our team will respond to your inquiry within 24-48 hours.
                </div>
            </div>
        </div>
        
        <div class="secure-note">
            <p>All communications with the Paranormal Investigation Agency are monitored for security purposes.</p>
            <p>Please ensure you are using a secure, non-haunted device when submitting sensitive information.</p>
            <div class="hidden-message">SSH access key for ghosthunter: check /home/ghosthunter/.ssh/id_rsa</div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>Paranormal Investigation Agency &copy; 2023 - "We see what technology cannot hide"</p>
        </div>
    </footer>
</body>
</html> 