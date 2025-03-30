const express = require('express');
const bodyParser = require('body-parser');
const jwt = require('jsonwebtoken');
const bcrypt = require('bcryptjs');
const sqlite3 = require('sqlite3').verbose();
const path = require('path');
const app = express();
const port = 3000;

// JWT Secret (weak and bruteforceable)
const JWT_SECRET = 'my_very_long_and_safe_secret_key';

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

// Initialize database
const db = new sqlite3.Database('./database.sqlite', (err) => {
  if (err) {
    console.error('Error opening database', err);
  } else {
    console.log('Connected to SQLite database');
    createTables();
  }
});

// Create tables and admin user
function createTables() {
  db.serialize(() => {
    // Create users table
    db.run(`CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      username TEXT UNIQUE,
      password TEXT,
      isAdmin INTEGER DEFAULT 0
    )`, (err) => {
      if (err) {
        console.error('Error creating users table', err);
      } else {
        console.log('Users table created or already exists');
        
        // Check if admin user exists, create if not
        db.get("SELECT * FROM users WHERE username = 'admin'", (err, row) => {
          if (err) {
            console.error('Error checking for admin user', err);
          } else if (!row) {
            // Generate a complex password for admin
            const adminPassword = generateSecurePassword(20);
            
            // Hash the admin password
            bcrypt.hash(adminPassword, 10, (err, hash) => {
              if (err) {
                console.error('Error hashing admin password', err);
              } else {
                // Insert admin user
                db.run("INSERT INTO users (username, password, isAdmin) VALUES ('admin', ?, 1)", [hash], (err) => {
                  if (err) {
                    console.error('Error creating admin user', err);
                  } else {
                    console.log('Admin user created with password:', adminPassword);
                    
                    // Save flag to flag.txt
                    const fs = require('fs');
                    const flag = 'ctf{w34k_JWT_s3cr3t_l3d_to_1mp3rs0n4t10n}';
                    if (!fs.existsSync('./flag.txt')) {
                      fs.writeFileSync('./flag.txt', flag);
                      console.log('Flag saved to flag.txt');
                    }
                  }
                });
              }
            });
          }
        });
      }
    });
  });
}

// Generate secure password
function generateSecurePassword(length) {
  const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+{}|:<>?';
  let password = '';
  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * charset.length);
    password += charset[randomIndex];
  }
  return password;
}

// Verify JWT middleware
function authenticateToken(req, res, next) {
  const authHeader = req.headers['authorization'];
  const token = authHeader && authHeader.split(' ')[1];
  
  if (!token) return res.status(401).json({ message: 'Authentication token required' });
  
  jwt.verify(token, JWT_SECRET, (err, user) => {
    if (err) return res.status(403).json({ message: 'Invalid or expired token' });
    
    req.user = user;
    next();
  });
}

// Check if user is admin
function isAdmin(req, res, next) {
  if (!req.user.isAdmin) {
    return res.status(403).json({ message: 'Access denied: Admin privileges required' });
  }
  next();
}

// Route to serve the main page
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Route to register a new user
app.post('/api/register', (req, res) => {
  const { username, password } = req.body;
  
  if (!username || !password) {
    return res.status(400).json({ message: 'Username and password are required' });
  }
  
  // Check if username already exists
  db.get("SELECT * FROM users WHERE username = ?", [username], (err, row) => {
    if (err) {
      console.error(err);
      return res.status(500).json({ message: 'Database error' });
    }
    
    if (row) {
      return res.status(409).json({ message: 'Username already exists' });
    }
    
    // Hash the password
    bcrypt.hash(password, 10, (err, hash) => {
      if (err) {
        console.error(err);
        return res.status(500).json({ message: 'Error hashing password' });
      }
      
      // Insert new user
      db.run("INSERT INTO users (username, password, isAdmin) VALUES (?, ?, 0)", [username, hash], (err) => {
        if (err) {
          console.error(err);
          return res.status(500).json({ message: 'Error creating user' });
        }
        
        return res.status(201).json({ message: 'User registered successfully' });
      });
    });
  });
});

// Route to login
app.post('/api/login', (req, res) => {
  const { username, password } = req.body;
  
  if (!username || !password) {
    return res.status(400).json({ message: 'Username and password are required' });
  }
  
  // Find user
  db.get("SELECT * FROM users WHERE username = ?", [username], (err, user) => {
    if (err) {
      console.error(err);
      return res.status(500).json({ message: 'Database error' });
    }
    
    if (!user) {
      return res.status(401).json({ message: 'Invalid username or password' });
    }
    
    // Compare password
    bcrypt.compare(password, user.password, (err, match) => {
      if (err) {
        console.error(err);
        return res.status(500).json({ message: 'Error comparing passwords' });
      }
      
      if (!match) {
        return res.status(401).json({ message: 'Invalid username or password' });
      }
      
      // Generate JWT token
      const token = jwt.sign(
        { id: user.id, username: user.username, isAdmin: user.isAdmin === 1 },
        JWT_SECRET,
        { expiresIn: '1h' }
      );
      
      return res.json({ message: 'Login successful', token, isAdmin: user.isAdmin === 1 });
    });
  });
});

// Protected route for user profile
app.get('/api/profile', authenticateToken, (req, res) => {
  res.json({ message: 'Profile accessed successfully', user: req.user });
});

// Protected route for admin page
app.get('/api/admin', authenticateToken, isAdmin, (req, res) => {
  const fs = require('fs');
  const flag = fs.readFileSync('./flag.txt', 'utf8');
  
  res.json({ 
    message: 'Admin area accessed successfully',
    flag: flag
  });
});

// Start the server
app.listen(port, () => {
  console.log(`Server running on http://localhost:${port}`);
}); 