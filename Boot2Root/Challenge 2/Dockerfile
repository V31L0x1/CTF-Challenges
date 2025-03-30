FROM ubuntu:20.04

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive

# Update and install necessary packages
RUN apt-get update && \
    apt-get install -y \
    apache2 \
    php \
    openssh-server \
    sudo \
    vim \
    cron

# Configure SSH
RUN mkdir -p /var/run/sshd
RUN echo 'PermitRootLogin no' >> /etc/ssh/sshd_config
RUN echo 'PasswordAuthentication yes' >> /etc/ssh/sshd_config
RUN echo 'PubkeyAuthentication yes' >> /etc/ssh/sshd_config

# Configure Apache
RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set up web directory
WORKDIR /var/www/html
RUN rm -f index.html

# Copy website files to /www directory (will be moved to proper location by setup.sh)
COPY www/ /www/

# Create ghosthunter user
RUN useradd -m -s /bin/bash ghosthunter
RUN echo "ghosthunter:Sp00kyP4ssw0rd!" | chpasswd

# Setup SSH directory for ghosthunter
RUN mkdir -p /home/ghosthunter/.ssh
WORKDIR /home/ghosthunter/.ssh
RUN ssh-keygen -t rsa -b 2048 -f id_rsa -N ""
RUN cat id_rsa.pub > authorized_keys
RUN chown -R ghosthunter:ghosthunter /home/ghosthunter/.ssh
RUN chmod 700 /home/ghosthunter/.ssh
RUN chmod 600 /home/ghosthunter/.ssh/id_rsa
RUN chmod 644 /home/ghosthunter/.ssh/id_rsa.pub
RUN chmod 644 /home/ghosthunter/.ssh/authorized_keys

# Setup sudo permissions for tar command (backup note will be created by setup.sh)
RUN echo "ghosthunter ALL=(root) NOPASSWD: /bin/tar -cf /backups/backup.tar *" > /etc/sudoers.d/ghosthunter
RUN chmod 440 /etc/sudoers.d/ghosthunter

# Create backup directory
RUN mkdir -p /backups
RUN chmod 755 /backups

# Set up automated backups with cron
RUN echo "# Run backup every hour" > /etc/cron.d/haunted-backup
RUN echo "0 * * * * root cd /var/www/html && /bin/tar -cf /backups/backup.tar *" >> /etc/cron.d/haunted-backup
RUN chmod 644 /etc/cron.d/haunted-backup

# Create flag
RUN echo "flag{gh0st_hunt3r_LFI_t0_RCE}" > /root/flag.txt
RUN chmod 600 /root/flag.txt

# Create custom MOTD
RUN echo "Welcome to the Paranormal Investigation Agency server" > /etc/motd
RUN echo "This system is for authorized ghost hunters only" >> /etc/motd
RUN echo "All activities are monitored for security purposes" >> /etc/motd

# Copy setup and entrypoint scripts
COPY setup.sh /root/setup.sh
RUN chmod +x /root/setup.sh

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose ports
EXPOSE 22 80

# Set entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Container metadata
LABEL maintainer="Paranormal Investigation Agency"
LABEL version="1.0"
LABEL description="Ghost Hunter Boot2Root Challenge - An LFI vulnerable PHP site with a wildcard tar sudo privilege escalation path" 