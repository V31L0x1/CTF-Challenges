FROM ubuntu:20.04

LABEL maintainer="CCOE CTF Team"
LABEL description="Spectral Gateway - Boot2Root Challenge for CTF"

# Avoid prompts during package installation
ENV DEBIAN_FRONTEND=noninteractive

# Set up working directory
WORKDIR /opt/challenge

# Copy setup script to the container
COPY setup.sh /opt/challenge/setup.sh

# Make the script executable
RUN chmod +x /opt/challenge/setup.sh

# Install packages and set up challenge
RUN apt-get update && \
    apt-get install -y \
    apache2 \
    openssh-server \
    ufw \
    sudo \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure SSH server
RUN mkdir -p /var/run/sshd
RUN echo 'PermitRootLogin no' >> /etc/ssh/sshd_config
RUN echo 'PasswordAuthentication yes' >> /etc/ssh/sshd_config

# Create the vulnerable user
RUN useradd -m -s /bin/bash specter
RUN echo "specter:Gh0stHunt3r!" | chpasswd

# Configure the web server
RUN rm -f /var/www/html/index.html

# Create the web content
COPY index.html /var/www/html/index.html

# Create the hidden directory with server logs
RUN mkdir -p /var/www/html/apparitions
COPY server_logs.txt /var/www/html/apparitions/server_logs.txt

# Create a robots.txt file to hint at the hidden directory
RUN echo "User-agent: *\nDisallow: /apparitions/\n# Note: Keep spectral investigations confidential" > /var/www/html/robots.txt

# Set permissions on web content
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Configure SUID bit on find command for privilege escalation
RUN chmod u+s /usr/bin/find

# Create flag file in root directory
RUN echo "flag{sp3ctral_pr1v1leg3_3scalat1on}" > /root/flag.txt
RUN chmod 600 /root/flag.txt

# Create a hint file in specter's home directory
COPY ghostly_hints.txt /home/specter/ghostly_hints.txt
RUN chown specter:specter /home/specter/ghostly_hints.txt
RUN chmod 644 /home/specter/ghostly_hints.txt

# Add a custom login message
COPY motd /etc/motd

# Expose needed ports
EXPOSE 22 80

# Start SSH and Apache services
CMD service ssh start && apachectl -D FOREGROUND 