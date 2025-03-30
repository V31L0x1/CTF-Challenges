const express = require('express');
const bodyParser = require('body-parser');
const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');
const app = express();
const port = 3000;

// Create a flag internally - this is what the CTF player needs to access
const internalFlag = 'ctf{SSRF_PDF_1fr4m3_vuln3r4b1l1ty}';
fs.writeFileSync(path.join(__dirname, 'internal', 'flag.txt'), internalFlag);

// Create internal server with flag
const internalApp = express();
internalApp.get('/flag', (req, res) => {
  res.send(`<html><body><h1>Internal System</h1><p>Flag: ${internalFlag}</p></body></html>`);
});
internalApp.get('/', (req, res) => {
    res.send(`<html><body><h1>Maybe It's not here</h1></body></html>`);
  });
const internalServer = internalApp.listen(8081, '127.0.0.1', () => {
  console.log('Internal server running on port 8081');
});

// Set up middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

// Make sure internal directory exists
if (!fs.existsSync(path.join(__dirname, 'internal'))) {
  fs.mkdirSync(path.join(__dirname, 'internal'));
}

// Generate a random filename for the PDF
function generateRandomFilename() {
  return 'pdf_' + Math.random().toString(36).substring(2, 15) + '.pdf';
}

// Main route
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// PDF conversion endpoint with SSRF vulnerability
app.post('/convert', async (req, res) => {
  try {
    const htmlContent = req.body.html;
    
    // No sanitization of iframe tags - SSRF vulnerability!
    
    const browser = await puppeteer.launch({
      headless: true,
      args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    
    // Set content and allow it to load iframes from local network
    await page.setContent(htmlContent, { waitUntil: 'networkidle0' });
    
    const pdfFilename = generateRandomFilename();
    const pdfPath = path.join(__dirname, 'public', 'downloads', pdfFilename);
    
    // Ensure downloads directory exists
    if (!fs.existsSync(path.join(__dirname, 'public', 'downloads'))) {
      fs.mkdirSync(path.join(__dirname, 'public', 'downloads'), { recursive: true });
    }
    
    // Generate PDF
    await page.pdf({
      path: pdfPath,
      format: 'A4',
      printBackground: true
    });
    
    await browser.close();
    
    // Return the PDF download link
    res.json({ 
      success: true, 
      message: 'PDF generated successfully', 
      pdfUrl: `/downloads/${pdfFilename}` 
    });
  } catch (error) {
    console.error('Error generating PDF:', error);
    res.status(500).json({ success: false, message: 'Error generating PDF' });
  }
});

// Start the server
app.listen(port, () => {
  console.log(`HTML to PDF converter running on http://localhost:${port}`);
}); 