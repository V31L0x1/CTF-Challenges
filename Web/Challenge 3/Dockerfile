FROM node:18-slim

# Install dependencies for Puppeteer
RUN apt-get update \
    && apt-get install -y wget gnupg ca-certificates procps fontconfig libxss1 \
    libglib2.0-0 libgtk-3-0 libxtst6 libnss3 libasound2 libgbm1 libx11-xcb1 \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app

# Set environment variables for Puppeteer
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/google-chrome-stable

# Install Chrome
RUN wget -q -O /tmp/chrome.deb https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb \
    && apt-get update \
    && apt-get install -y /tmp/chrome.deb \
    && rm -rf /var/lib/apt/lists/* /tmp/chrome.deb

# Copy package.json
COPY package.json ./

# Install dependencies 
RUN npm install --no-fund --no-audit

# Copy application files
COPY . .

# Create directories if they don't exist
RUN mkdir -p public/css public/js

# Set environment variables
ENV PORT=3000
ENV NODE_ENV=production
ENV FLAG=flag{xss_c4n_l34d_t0_4dm1n_t4k30v3r}

# Expose port
EXPOSE 3000

# Start the application
CMD ["node", "server.js"] 