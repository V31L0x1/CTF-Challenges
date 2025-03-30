<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paranormal Investigation Agency - Evidence Gallery</title>
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
        
        .gallery-intro {
            margin-bottom: 2rem;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .gallery-item {
            background-color: var(--secondary-bg);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px var(--shadow-color);
        }
        
        .gallery-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(123, 0, 255, 0.1) 0%, transparent 70%);
            z-index: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover::before {
            opacity: 1;
        }
        
        .ascii-art {
            padding: 2rem;
            font-family: 'Courier New', monospace;
            color: var(--accent-color);
            text-align: center;
            line-height: 1.2;
            font-size: 10px;
            position: relative;
            z-index: 1;
            white-space: pre;
            overflow: hidden;
        }
        
        .gallery-caption {
            padding: 1.5rem;
            background-color: rgba(15, 15, 19, 0.8);
            border-top: 1px solid rgba(123, 0, 255, 0.2);
        }
        
        .gallery-caption h3 {
            color: var(--accent-color);
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .gallery-caption p {
            font-size: 0.9rem;
            color: #b8b8b8;
        }
        
        .gallery-caption .metadata {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #777;
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
        
        .flicker-effect {
            animation: flicker 3s infinite alternate;
        }
        
        @keyframes flicker {
            0%, 19.999%, 22%, 62.999%, 64%, 64.999%, 70%, 100% {
                opacity: 1;
            }
            20%, 21.999%, 63%, 63.999%, 65%, 69.999% {
                opacity: 0.5;
            }
        }
        
        .static-effect {
            position: relative;
        }
        
        .static-effect::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><filter id="noiseFilter"><feTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/><feColorMatrix type="matrix" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 0.5 0"/></filter><rect width="100%" height="100%" filter="url(%23noiseFilter)"/></svg>');
            opacity: 0.05;
            pointer-events: none;
            z-index: 1;
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
            <a href="gallery.php" class="active">Evidence Gallery</a>
            <a href="viewer.php">File Viewer</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </nav>
    
    <main class="container">
        <div class="gallery-intro">
            <h2>Digital Evidence Gallery</h2>
            <p>Below is a collection of digital spectral manifestations our team has captured during various investigations. These images represent some of the most compelling evidence of digital entities inhabiting networked systems and electronic devices.</p>
            <p class="flicker-effect">Warning: Some images may cause electronic interference with sensitive equipment.</p>
        </div>
        
        <div class="gallery-grid">
            <div class="gallery-item static-effect">
                <div class="ascii-art">
          .--.
         |o_o |
         |:_/ |
        //   \ \
       (|     | )
      /'\_   _/`\
      \___)=(___/
                </div>
                <div class="gallery-caption">
                    <h3>Server Room Manifestation</h3>
                    <p>Entity appeared during a server restart sequence. System logs recorded unexplained data transfers at time of capture.</p>
                    <p class="metadata">Location: Blackwood Data Center | Date: 10/14/2023</p>
                </div>
            </div>
            
            <div class="gallery-item static-effect">
                <div class="ascii-art">
           .-.
          (o.o)
           |=|
          /   \
         |     |
         |     |
         |     |
         |_._._|
</div>
                <div class="gallery-caption">
                    <h3>Corrupted Data Spirit</h3>
                    <p>This entity manifested during recovery of corrupted database files. The ghost appeared to be attempting to reconstruct the damaged data.</p>
                    <p class="metadata">Location: Financial Institution Archives | Date: 11/02/2023</p>
                </div>
            </div>
            
            <div class="gallery-item static-effect">
                <div class="ascii-art">
    .-.
   (   )
    |~|      .===.
    |~|     /.--.\
    |~|    //    \\
    |~|   //      \\
    |~|  ||        ||
   /~~~\.||        ||
  /     \|'-.__.__.'|
 /|       |  '-.__.'|
(_|       |_________|
  \       |(/))(/)|
   \     /|" "" "|
    \   / |  ""  |
     \ /  | "  " |
      V  /|      |\
         `-'    `-'
</div>
                <div class="gallery-caption">
                    <h3>Haunted Mainframe</h3>
                    <p>This apparition appears regularly in an obsolete mainframe system that processes financial data. Despite being scheduled for decommissioning, the system repeatedly restarts itself.</p>
                    <p class="metadata">Location: Banking Headquarters | Date: 10/31/2023</p>
                </div>
            </div>
            
            <div class="gallery-item static-effect">
                <div class="ascii-art">
               __
   .,-;-;-,. /'_\
  _/_/_/_|_\_\) /
'-<_><_><_><_>=/\
  `/_/====/_/-'\_\
   ""     ""    ""
</div>
                <div class="gallery-caption">
                    <h3>Network Phantom</h3>
                    <p>This entity travels through network infrastructure, manifesting in different locations along the same subnet. Appears during periods of high data transfer.</p>
                    <p class="metadata">Location: University Network | Date: 11/15/2023</p>
                </div>
            </div>
            
            <div class="gallery-item static-effect">
                <div class="ascii-art">
            _,.
        ,''   '.
       /       \
   _.-| 0     0 |
  ( _.'\ \___/ /'.
   '._   .___. ___)
   (   `'-   -'    )
    \  '._   _.'  /
     '.    ```    .'
      `-._____.-'
</div>
                <div class="gallery-caption">
                    <h3>Binary Residue Spirit</h3>
                    <p>This entity forms from residual code fragments in systems where critical processes have terminated unexpectedly. It appears to be formed from memory dumps.</p>
                    <p class="metadata">Location: Research Lab Systems | Date: 09/23/2023</p>
                </div>
            </div>
            
            <div class="gallery-item static-effect">
                <div class="ascii-art">
      ___
    .'   '.
   /       \
  |         |
  |  O   O  |
  |  \___/  |
  '.       .'
   |`-...-'|
   |       |
   |       |
   '.___.'
    (___)
     |_|
</div>
                <div class="gallery-caption">
                    <h3>Quantum State Anomaly</h3>
                    <p>Captured during Professor Blackwood's quantum computing experiments. This entity appears to exist in multiple states simultaneously, suggesting a connection to quantum decoherence.</p>
                    <p class="metadata">Location: Quantum Research Facility | Date: 11/05/2023</p>
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