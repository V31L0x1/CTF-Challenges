<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paranormal Investigation Agency</title>
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
        
        section {
            margin-bottom: 3rem;
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
        
        h3 {
            margin-bottom: 1rem;
            color: #b292ff;
        }
        
        p {
            margin-bottom: 1.5rem;
        }
        
        .ghost-img {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }
        
        .ghost-img pre {
            font-family: 'Courier New', Courier, monospace;
            color: #9d68fe;
            font-size: 12px;
            line-height: 12px;
            position: relative;
            display: inline-block;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
            100% {
                transform: translateY(0);
            }
        }
        
        .ghost-img::before, .ghost-img::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(123, 0, 255, 0.1);
            z-index: -1;
        }
        
        .ghost-img::before {
            top: 20%;
            left: 30%;
            animation: pulse 4s infinite;
        }
        
        .ghost-img::after {
            bottom: 20%;
            right: 30%;
            animation: pulse 5s infinite 1s;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.5);
                opacity: 0.2;
            }
            100% {
                transform: scale(1);
                opacity: 0.5;
            }
        }
        
        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .service-card {
            background-color: var(--secondary-bg);
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 3px solid var(--accent-color);
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(123, 0, 255, 0.2);
        }
        
        .service-card h3 {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }
        
        .service-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--accent-color);
        }
        
        .recent-cases {
            background-color: var(--secondary-bg);
            padding: 2rem;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        
        .recent-cases::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(123, 0, 255, 0.2) 0%, transparent 70%);
            z-index: 0;
        }
        
        .case {
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
            padding-left: 1rem;
            border-left: 2px solid var(--accent-color);
        }
        
        .case h3 {
            color: #b292ff;
        }
        
        .case-meta {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 0.5rem;
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
        
        .glitch-effect {
            position: relative;
            animation: glitch 3s infinite;
        }
        
        @keyframes glitch {
            0% {
                text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                            -0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
                            -0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
            }
            14% {
                text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                            -0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
                            -0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
            }
            15% {
                text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
                            0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
                            -0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
            }
            49% {
                text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
                            0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
                            -0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
            }
            50% {
                text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
                            0.05em 0 0 rgba(0, 255, 0, 0.75),
                            0 -0.05em 0 rgba(0, 0, 255, 0.75);
            }
            99% {
                text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
                            0.05em 0 0 rgba(0, 255, 0, 0.75),
                            0 -0.05em 0 rgba(0, 0, 255, 0.75);
            }
            100% {
                text-shadow: -0.025em 0 0 rgba(255, 0, 0, 0.75),
                            -0.025em -0.025em 0 rgba(0, 255, 0, 0.75),
                            -0.025em -0.05em 0 rgba(0, 0, 255, 0.75);
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content container">
            <h1 class="glitch-effect">Paranormal Investigation Agency</h1>
            <p class="tagline">Exploring the Beyond Since 1999</p>
        </div>
    </header>
    
    <nav class="nav">
        <div class="nav-container container">
            <a href="index.php" class="active">Home</a>
            <a href="reports.php">Reports</a>
            <a href="gallery.php">Evidence Gallery</a>
            <a href="viewer.php">File Viewer</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </nav>
    
    <main class="container">
        <section>
            <h2>Welcome to the Digital Paranormal Gateway</h2>
            <p>Our specialized agency hosts investigations into digital apparitions and electronic ghost phenomena. Our elite team investigates server hauntings, ghost data, and spectral network activity across the darkest corners of cyberspace.</p>
            
            <div class="ghost-img">
                <pre>
      .-.
     (o o)
     | O \
     |    \
     '~~~'
                </pre>
            </div>
        </section>
        
        <section>
            <h2>Our Specialized Services</h2>
            <div class="services">
                <div class="service-card">
                    <h3>Server Haunting Investigations</h3>
                    <p>We detect and document paranormal entities that inhabit server infrastructure, causing unexplained system behaviors and mysterious digital anomalies.</p>
                </div>
                
                <div class="service-card">
                    <h3>Ghost Data Recovery</h3>
                    <p>Our specialists can recover spectral data fragments from corrupted or "haunted" storage media that conventional recovery methods cannot access.</p>
                </div>
                
                <div class="service-card">
                    <h3>Network Exorcism</h3>
                    <p>When malicious digital spirits infect your systems, our team performs complete network cleansing and removal of all paranormal entities.</p>
                </div>
                
                <div class="service-card">
                    <h3>Cyber-Spectral Analysis</h3>
                    <p>Using proprietary tools, we can detect and analyze paranormal signatures in your digital infrastructure and identify potential vulnerabilities.</p>
                </div>
            </div>
        </section>
        
        <section>
            <h2>Recent Investigations</h2>
            <div class="recent-cases">
                <div class="case">
                    <h3>The Blackwood Data Center Incident</h3>
                    <div class="case-meta">October 14, 2023 | Agent Sarah Jensen</div>
                    <p>Our team investigated unusual phenomena at an abandoned server room where powered-down systems would mysteriously activate at precisely 3:14 AM each night. EMF readings indicated significant anomalies, and EVP sessions captured distinct electronic voices requesting system restoration.</p>
                </div>
                
                <div class="case">
                    <h3>Professor Blackwood's Quantum Ghost</h3>
                    <div class="case-meta">November 2, 2023 | Agent Marcus Powell</div>
                    <p>A computer science researcher reported his systems activating autonomously during night hours, with his quantum computing research files showing mysterious modifications. Our investigation revealed non-standard network protocols and binary files containing speech-like patterns when analyzed as audio.</p>
                </div>
                
                <p>All investigation reports are classified and stored in secure locations. Only authorized personnel with valid credentials may access sensitive material. If you've encountered digital apparitions, please contact our 24/7 hotline.</p>
            </div>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>Paranormal Investigation Agency &copy; 2023 - "We see what technology cannot hide"</p>
            <!-- Developer note: Remember to secure system access points -->
        </div>
    </footer>
</body>
</html> 