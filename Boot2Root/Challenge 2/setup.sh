#!/bin/bash
# Setup script for Ghost Hunter challenge
set -e

echo "[+] Setting up environment..."

# Create the ghosthunter user
echo "[+] Creating ghosthunter user..."
if ! id ghosthunter &>/dev/null; then
    useradd -m -s /bin/bash ghosthunter
    echo "ghosthunter:ghosthunter" | chpasswd
fi

# Set up SSH key authentication for ghosthunter
echo "[+] Setting up SSH key authentication..."
mkdir -p /home/ghosthunter/.ssh
if [ ! -f "/home/ghosthunter/.ssh/id_rsa" ]; then
    ssh-keygen -t rsa -f /home/ghosthunter/.ssh/id_rsa -N ""
    cat /home/ghosthunter/.ssh/id_rsa.pub > /home/ghosthunter/.ssh/authorized_keys
    chown -R ghosthunter:ghosthunter /home/ghosthunter/.ssh
    chmod 700 /home/ghosthunter/.ssh
    chmod 600 /home/ghosthunter/.ssh/id_rsa
    chmod 644 /home/ghosthunter/.ssh/authorized_keys
fi

# Set up sudo for tar with wildcard for backup (exploitable)
echo "[+] Setting up sudo for backup..."
echo "ghosthunter ALL=(root) NOPASSWD: /bin/tar -cf /backups/backup.tar *" > /etc/sudoers.d/ghosthunter
chmod 440 /etc/sudoers.d/ghosthunter

# Create backups directory
echo "[+] Creating backup directory..."
mkdir -p /backups
chmod 755 /backups

# Create flag
echo "[+] Creating flag..."
echo "flag{gh0st_hunt3r_LFI_t0_RCE}" > /root/flag.txt
chmod 600 /root/flag.txt

# Configure Apache to run as ghosthunter instead of www-data
echo "[+] Configuring Apache to run as ghosthunter user..."
sed -i 's/^export APACHE_RUN_USER=www-data$/export APACHE_RUN_USER=ghosthunter/' /etc/apache2/envvars
sed -i 's/^export APACHE_RUN_GROUP=www-data$/export APACHE_RUN_GROUP=ghosthunter/' /etc/apache2/envvars

# Copy website files to the web root
echo "[+] Setting up website..."
if [ -d "/var/www/html" ]; then
    # Remove default index.html if it exists
    rm -f /var/www/html/index.html
    
    # Copy all PHP files from /www to /var/www/html
    cp -r /www/* /var/www/html/
    
    # Create reports directory if it doesn't exist
    mkdir -p /var/www/html/reports
    
    # Set proper permissions
    chown -R ghosthunter:ghosthunter /var/www/html
    chmod -R 755 /var/www/html
fi

# Create backup note in ghosthunter's home directory
echo "[+] Creating backup note..."
echo "Remember to run daily backups with: sudo tar -cf /backups/backup.tar *" > /home/ghosthunter/backup_note.txt
chown ghosthunter:ghosthunter /home/ghosthunter/backup_note.txt

echo "[+] Setup completed successfully!"
exit 0 