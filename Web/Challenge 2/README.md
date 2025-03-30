# JWT Bypass Challenge

## Challenge Description

Welcome to the CyberSec Portal! This platform uses JWT (JSON Web Token) for authentication. Your mission is to bypass the authentication system and gain access to the admin page to find the flag.

**Difficulty**: Medium  
**Category**: Web, JWT  
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

1. The JWT authentication seems secure, but is the secret key strong enough?
2. Try looking at your JWT token in your profile - maybe there's a way to modify it?
3. There are tools available for brute-forcing JWT secrets.

## Solution (For CTF Organizers Only)

<details>
<summary>Click to reveal solution</summary>

This challenge features a weak JWT secret ("secret").

To solve the challenge:

1. Register an account and login to access your profile.
2. In your profile, you'll see your JWT token.
3. The token can be decoded at jwt.io, revealing three main components: header, payload, and signature.
4. The payload contains a claim "isAdmin" set to false for normal users.
5. The secret used to sign the JWT is weak and can be brute-forced using tools like `jwt-cracker` or `hashcat`.

```bash
# Example with jwt-cracker
jwt-cracker <your_token> abcdefghijklmnopqrstuvwxyz

# The secret will be found: "secret"
```

6. Now that you know the secret is "secret", you can:
   - Go to jwt.io
   - Paste your token
   - Modify the payload to change `"isAdmin": false` to `"isAdmin": true`
   - Use "secret" as the secret key to properly sign the token
   - Replace your token in localStorage with the new token (using browser dev tools)
   - Refresh the page and access the admin area to get the flag: `ctf{w34k_JWT_s3cr3t_l3d_to_1mp3rs0n4t10n}`

</details>

## Credits

Created for the CCOE CTF Challenge 