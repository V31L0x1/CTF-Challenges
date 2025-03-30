# Spectral Gateway - Setup Instructions

This document provides instructions for setting up the Spectral Gateway boot2root challenge for a CTF event. The challenge can be deployed either as a Docker container or as a virtual machine.

## Challenge Overview

Spectral Gateway is an easy-level boot2root challenge themed around paranormal investigation. Participants need to:

1. Discover hidden directories on a web server
2. Extract login credentials from server logs
3. Use SSH to log in to the server
4. Leverage a SUID binary (find) for privilege escalation
5. Retrieve the flag from the root directory

## Deployment Options

### Option 1: Docker Deployment (Recommended)

Prerequisites:
- Docker and Docker Compose installed on the host system

Steps:
1. Clone or download this challenge directory
2. Navigate to the directory in a terminal
3. Run `docker-compose up -d` to build and start the container
4. The challenge will be available at:
   - Web server: http://localhost:80
   - SSH: ssh specter@localhost -p 2222

### Option 2: Virtual Machine Deployment

Prerequisites:
- A hypervisor like VirtualBox, VMware, or Hyper-V
- Ubuntu Server 20.04 LTS ISO

Steps:
1. Create a new Ubuntu Server 20.04 VM with:
   - At least 1GB RAM
   - 10GB disk space
   - Bridged network adapter
2. Install Ubuntu Server with minimal options
3. Copy all the files from this directory to the VM
4. Execute the setup script: `sudo bash setup.sh`
5. The VM's IP address will be displayed after setup

## Challenge Testing

After deployment, verify the challenge is working correctly:

1. Visit the web server in a browser
2. Use gobuster or similar to find the /apparitions directory
3. Check that the server logs contain the credentials
4. Test SSH login with the credentials
5. Confirm the privilege escalation works
6. Verify the flag is accessible in /root/flag.txt

## Challenge Maintenance

- The Docker container will restart automatically unless explicitly stopped
- For VM deployments, ensure the system is configured to start services on boot
- Consider setting up a cron job to reset the challenge daily for CTF events

## Flag

The default flag is: `flag{sp3ctral_pr1v1leg3_3scalat1on}`

You can change this by editing:
- For Docker deployment: the CTF_FLAG environment variable in docker-compose.yml
- For VM deployment: the flag value in setup.sh

## Security Note

This challenge intentionally contains vulnerabilities for educational purposes. Do not deploy on production systems or networks with sensitive information. 