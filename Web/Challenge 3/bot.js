const puppeteer = require('puppeteer');
const jwt = require('jsonwebtoken');

// Secret key for JWT (same as in server.js)
const JWT_SECRET = process.env.JWT_SECRET || 'spooky_secret_key_for_haunted_forum';

// Timeout for the bot (milliseconds)
const BOT_TIMEOUT = 10000;

// Function to create and run a bot instance
async function createBotInstance(postId) {
    console.log(`Starting admin bot to visit post ID: ${postId}`);
    
    try {
        // Launch browser in headless mode
        const browser = await puppeteer.launch({
            headless: 'new',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--disable-gpu'
            ],
            ignoreHTTPSErrors: true
        });
        
        // Create a new page
        const page = await browser.newPage();
        
        // Create an admin token
        const adminToken = jwt.sign({ userId: 1 }, JWT_SECRET, { expiresIn: '5m' });
        
        // Set viewport size
        await page.setViewport({ width: 1280, height: 800 });
        
        // Set the admin cookie
        await page.setCookie({
            name: 'auth_token',
            value: adminToken,
            domain: 'localhost',
            path: '/',
            httpOnly: false,
            secure: false
        });
        
        console.log(`Admin bot visiting: http://localhost:3000/post.html?id=${postId}`);
        
        // Visit the post page
        await page.goto(`http://localhost:3000/post.html?id=${postId}`, {
            waitUntil: 'networkidle2',
            timeout: BOT_TIMEOUT
        });
        
        // Wait for a few seconds to allow any JavaScript to execute
        await page.waitForTimeout(5000);
        
        // Close the browser
        await browser.close();
        console.log(`Admin bot finished reviewing post ID: ${postId}`);
    } catch (error) {
        console.error(`Error during admin bot review:`, error);
    }
}

module.exports = { createBotInstance }; 