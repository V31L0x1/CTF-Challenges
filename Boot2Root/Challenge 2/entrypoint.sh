#!/bin/bash
# Entrypoint script for the Ghost Hunter Challenge

# Start SSH service
echo "[+] Starting SSH service..."
service ssh start
if [ $? -ne 0 ]; then
    echo "[-] Failed to start SSH service"
    exit 1
fi

# Run the setup script
echo "[+] Running setup script..."
/root/setup.sh
if [ $? -ne 0 ]; then
    echo "[-] Setup script failed"
    exit 1
fi

# Ensure the SSH key directory exists
echo "[+] Ensuring SSH key directory exists..."
mkdir -p /home/ghosthunter/.ssh
if [ ! -f "/home/ghosthunter/.ssh/id_rsa" ]; then
    echo "[+] Creating SSH key for ghosthunter..."
    ssh-keygen -t rsa -f /home/ghosthunter/.ssh/id_rsa -N ""
    cat /home/ghosthunter/.ssh/id_rsa.pub > /home/ghosthunter/.ssh/authorized_keys
    chown -R ghosthunter:ghosthunter /home/ghosthunter/.ssh
    chmod 700 /home/ghosthunter/.ssh
    chmod 600 /home/ghosthunter/.ssh/id_rsa
    chmod 644 /home/ghosthunter/.ssh/id_rsa.pub
    chmod 644 /home/ghosthunter/.ssh/authorized_keys
fi

# Make a readable copy of the SSH key for LFI
echo "[+] Setting up SSH key for discovery..."
cp /home/ghosthunter/.ssh/id_rsa /root/ghosthunter_id_rsa
chmod 644 /root/ghosthunter_id_rsa

# Fix Apache permissions on log directories
echo "[+] Fixing Apache log permissions..."
chown -R ghosthunter:ghosthunter /var/log/apache2
chmod -R 755 /var/log/apache2

# Remove default index.html if it exists
echo "[+] Removing default index.html if it exists..."
if [ -f "/var/www/html/index.html" ]; then
    rm /var/www/html/index.html
fi

# Start cron service
echo "[+] Starting cron service..."
service cron start
if [ $? -ne 0 ]; then
    echo "[-] Failed to start cron service"
    exit 1
fi

# Check if the LFI vulnerability is in place
if [ ! -f "/var/www/html/viewer.php" ]; then
    echo "[-] ERROR: LFI vulnerability file not found!"
    exit 1
fi

echo "[+] Setup complete!"
echo "[+] Paranormal Investigation Agency website is ready."
echo "[+] Target user: ghosthunter (SSH key auth required)"
echo "[+] LFI vulnerability at http://localhost/viewer.php?file=/etc/passwd"
echo "[+] The flag is in /root/flag.txt"

# Start Apache in the foreground to keep the container running
echo "[+] Starting Apache in the foreground..."
apache2ctl -D FOREGROUND 