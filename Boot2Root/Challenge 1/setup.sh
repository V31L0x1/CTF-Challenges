#!/bin/bash
# Setup script for Spectral Gateway boot2root challenge
# This script assumes it's running on a clean Ubuntu 20.04 LTS server

# Exit on any error
set -e

echo "[+] Starting Spectral Gateway challenge setup..."

# Update the system
echo "[+] Updating the system..."
apt-get update
apt-get upgrade -y

# Install required packages
echo "[+] Installing required packages..."
apt-get install -y apache2 openssh-server

# Configure SSH
echo "[+] Configuring SSH..."
sed -i 's/#PasswordAuthentication yes/PasswordAuthentication yes/' /etc/ssh/sshd_config
sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin no/' /etc/ssh/sshd_config
systemctl restart ssh

# Create the vulnerable user
echo "[+] Creating the vulnerable user 'specter'..."
useradd -m -s /bin/bash specter
echo "specter:Gh0stHunt3r!" | chpasswd

# Configure the web server
echo "[+] Setting up the web server..."
rm -f /var/www/html/index.html

# Create the main index page
cat > /var/www/html/index.html << 'EOF'
<!DOCTYPE html>
<html>
<head>
    <title>Paranormal Investigation Agency</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #ccc;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #111;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        h1 {
            color: #ff0000;
        }
        .ghost-img {
            text-align: center;
            margin: 20px 0;
        }
        .ghost-img pre {
            color: #aaa;
            font-size: 10px;
            line-height: 10px;
        }
        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            background-color: #111;
        }
    </style>
</head>
<body>
    <header>
        <h1>Paranormal Investigation Agency</h1>
        <p>Exploring the Beyond Since 1999</p>
    </header>
    
    <div class="container">
        <h2>Welcome to the Digital Gateway</h2>
        <p>This server hosts our investigations into digital apparitions and electronic ghost phenomena. Our specialized team investigates server hauntings, ghost data, and spectral network activity.</p>
        
        <div class="ghost-img">
            <pre>
           .-.
          ( o o )
           |   |
           '~~~'
        </pre>
        </div>
        
        <h3>Our Services</h3>
        <ul>
            <li>Investigation of haunted servers and networks</li>
            <li>Recovery of ghost data from corrupted drives</li>
            <li>Detection of paranormal activity in computer systems</li>
            <li>Removal of malicious digital spirits</li>
        </ul>
        
        <h3>Recent Investigations</h3>
        <p>All investigation reports are classified and stored in secure locations. Only authorized personnel with valid credentials may access sensitive material.</p>
        <p>If you've encountered digital apparitions, please contact our 24/7 hotline.</p>
    </div>
    
    <footer>
        <p>Paranormal Investigation Agency &copy; 2023 - "We see what others don't"</p>
        <!-- Developer note: Remember to move server logs to /apparitions directory -->
    </footer>
</body>
</html>
EOF

# Create the hidden directory with server logs
echo "[+] Creating hidden directory with server logs..."
mkdir -p /var/www/html/apparitions
cat > /var/www/html/apparitions/server_logs.txt << 'EOF'
[2023-05-01 01:10:15] Server started
[2023-05-01 01:15:30] Routine security check passed
[2023-05-01 02:30:45] Backup completed successfully
[2023-05-01 03:00:12] Suspicious activity detected from IP 192.168.1.105
[2023-05-01 03:10:18] Firewall blocked connection attempt from unknown source
[2023-05-01 03:15:22] User 'specter' failed login attempt with password 'incorrect_password'
[2023-05-01 03:15:45] User 'specter' successful login with password 'Gh0stHunt3r!'
[2023-05-01 03:20:33] User 'admin' failed login attempt - account locked
[2023-05-01 03:45:10] Unusual file system activity detected in /var/www/html
[2023-05-01 04:00:01] Scheduled maintenance started
[2023-05-01 04:15:27] Temperature anomaly detected in server room - 15°C drop in 5 minutes
[2023-05-01 04:20:19] Security camera 3 malfunctioning - visual artifacts present
[2023-05-01 04:30:55] Emergency systems check - all systems nominal
[2023-05-01 04:45:12] Audio recording system captured unexplained noise in server room
[2023-05-01 05:00:30] All systems restored to normal operation
EOF

# Create a robots.txt file to hint at the hidden directory
cat > /var/www/html/robots.txt << 'EOF'
User-agent: *
Disallow: /apparitions/
# Note: Keep spectral investigations confidential
EOF

# Set permissions on web content
echo "[+] Setting web content permissions..."
chown -R www-root:www-root /var/www/html
chmod -R 755 /var/www/html

# Configure SUID bit on find command for privilege escalation
echo "[+] Setting up privilege escalation vector..."
chmod u+s /usr/bin/find

# Create flag file in root directory
echo "[+] Creating flag file..."
echo "flag{sp3ctral_pr1v1leg3_3scalat1on}" > /root/flag.txt
chmod 600 /root/flag.txt

# Create a hint file in specter's home directory
cat > /home/specter/ghostly_hints.txt << 'EOF'
Dear Agent,

Welcome to your first day at the Paranormal Investigation Agency. As you know, 
we've been tracking unusual activity on this server for weeks. We suspect a 
digital entity has gained control of the root account.

Your mission is to gain higher access and confirm the nature of this entity.

Remember your training:
1. Always look for SUID binaries that can be exploited
2. Documentation for "find" command might be particularly helpful
3. The entity leaves traces of its presence in all system operations

Good luck, and be careful. We've lost contact with the last three agents who
attempted this investigation.

- Director Hayes
EOF

chown specter:specter /home/specter/ghostly_hints.txt
chmod 644 /home/specter/ghostly_hints.txt

# Add a custom login message
cat > /etc/motd << 'EOF'
 .--.           .---.        .-.
: .; :          : .; :      .' `.
:   .' .--.   .-: ._.' .--. `. .'
: :.`.' .; ; : :_; :  ' .; ; : :
:_;:_;`.__,_;:___.'   `.__,_;:_;

WELCOME TO THE SPECTRAL GATEWAY
IP Address: $(hostname -I | awk '{print $1}')

This server is property of the Paranormal Investigation Agency.
Unauthorized access is prohibited and will be reported to the authorities.

EOF

# Firewall setup - only allow SSH and HTTP
echo "[+] Configuring firewall..."
apt-get install -y ufw
ufw allow ssh
ufw allow http
ufw --force enable

echo "[+] Setup complete!"
echo "[+] The challenge is ready. Users should connect to: $(hostname -I | awk '{print $1}')"
echo "[+] Target user: specter"
echo "[+] SSH is enabled"
echo "[+] The flag is in /root/flag.txt" 