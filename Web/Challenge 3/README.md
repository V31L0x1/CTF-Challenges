# Haunted Forum XSS Challenge

## Challenge Description

Welcome to the Haunted Forum, a spooky place for digital spirits! This forum has some security vulnerabilities that allow attackers to perform Cross-Site Scripting (XSS) attacks. Your mission is to exploit these vulnerabilities to steal the admin's cookie and gain access to the admin panel where the flag is located.

## Challenge Details

- **Challenge Name**: Haunted Forum
- **Category**: Web Security
- **Difficulty**: Hard
- **Flag Format**: flag{...}

## Setup Instructions

### Prerequisites

- Node.js (v14+)
- npm

### Installation

1. Clone this repository or extract the challenge files
2. Navigate to the challenge directory
3. Install dependencies:

```bash
npm install
```

4. Start the server:

```bash
npm start
```

5. Access the application at `http://localhost:3000`

## Docker Setup (Alternative)

You can also run the challenge in a Docker container:

```bash
docker build -t haunted-forum .
docker run -p 3000:3000 haunted-forum
```

## Challenge Scenario

You've discovered a forum called "Haunted Forum" where users can create posts and report inappropriate content to the admin. The admin has a special flag that you need to capture.

### Objectives:

1. Register an account on the Haunted Forum
2. Identify and exploit an XSS vulnerability in the forum posts
3. Use the XSS vulnerability to steal the admin's authentication cookie
4. Use the stolen cookie to access the admin panel
5. Retrieve the flag from the admin panel

## Hints

1. When users report posts, an admin bot visits the reported post to review it.
2. The content of posts is not properly sanitized before being displayed.
3. You'll need to set up a server to capture the admin's cookie when your XSS payload executes.

## Solution

A sample solution is provided in the `exploit.js` file, but it's recommended to try solving the challenge on your own first!

## Credits

Created for CCOE CTF Challenges by the CCOE CTF Team. 