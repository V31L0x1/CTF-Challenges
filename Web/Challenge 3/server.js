const express = require('express');
const cookieParser = require('cookie-parser');
const bodyParser = require('body-parser');
const jwt = require('jsonwebtoken');
const crypto = require('crypto');
const fs = require('fs');
const path = require('path');
const { createBotInstance } = require('./bot');

const app = express();
const PORT = process.env.PORT || 3000;

// Secret key for JWT
const JWT_SECRET = process.env.JWT_SECRET || 'spooky_secret_key_for_haunted_forum';

// Flag value
const FLAG = process.env.FLAG || 'flag{xss_c4n_l34d_t0_4dm1n_t4k30v3r}';

// Middleware
app.use(cookieParser());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));

// In-memory data storage
const users = [
    {
        id: 1,
        username: 'admin',
        password: crypto.createHash('sha256').update('super_secure_admin_password').digest('hex'),
        isAdmin: true,
        createdAt: new Date('2023-01-01')
    }
];

const posts = [
    {
        id: 1,
        title: 'Welcome to the Haunted Forum!',
        content: 'Welcome to our spooky community! This is a place where you can share your ghostly experiences and eerie encounters. Feel free to <b>create a new post</b> and share your story!',
        author: 'admin',
        createdAt: new Date('2023-01-01'),
        reports: 0
    }
];

// Auth Middleware
const authenticate = (req, res, next) => {
    const token = req.cookies.auth_token;
    
    if (!token) {
        return res.status(401).json({ error: 'Authentication required' });
    }
    
    try {
        const decoded = jwt.verify(token, JWT_SECRET);
        req.user = users.find(user => user.id === decoded.userId);
        
        if (!req.user) {
            return res.status(401).json({ error: 'User not found' });
        }
        
        next();
    } catch (error) {
        return res.status(401).json({ error: 'Invalid token' });
    }
};

// Admin Middleware
const requireAdmin = (req, res, next) => {
    if (!req.user || !req.user.isAdmin) {
        return res.status(403).json({ error: 'Admin privileges required' });
    }
    
    next();
};

// Routes

// Register
app.post('/api/register', (req, res) => {
    const { username, password } = req.body;
    
    // Validation
    if (!username || !password) {
        return res.status(400).json({ error: 'Username and password are required' });
    }
    
    if (username.length < 3 || username.length > 20) {
        return res.status(400).json({ error: 'Username must be between 3 and 20 characters' });
    }
    
    if (password.length < 8) {
        return res.status(400).json({ error: 'Password must be at least 8 characters' });
    }
    
    // Check if username already exists
    if (users.some(user => user.username === username)) {
        return res.status(400).json({ error: 'Username already taken' });
    }
    
    // Create new user
    const newUser = {
        id: users.length + 1,
        username,
        password: crypto.createHash('sha256').update(password).digest('hex'),
        isAdmin: false,
        createdAt: new Date()
    };
    
    users.push(newUser);
    
    // Create token
    const token = jwt.sign({ userId: newUser.id }, JWT_SECRET, { expiresIn: '1h' });
    
    // Set cookie
    res.cookie('auth_token', token, {
        httpOnly: false,
        maxAge: 3600000, // 1 hour
        sameSite: 'strict'
    });
    
    res.status(201).json({ message: 'Registration successful', user: { username: newUser.username } });
});

// Login
app.post('/api/login', (req, res) => {
    const { username, password } = req.body;
    
    // Validation
    if (!username || !password) {
        return res.status(400).json({ error: 'Username and password are required' });
    }
    
    // Find user
    const user = users.find(u => u.username === username);
    
    if (!user) {
        return res.status(401).json({ error: 'Invalid username or password' });
    }
    
    // Check password
    const hashedPassword = crypto.createHash('sha256').update(password).digest('hex');
    
    if (user.password !== hashedPassword) {
        return res.status(401).json({ error: 'Invalid username or password' });
    }
    
    // Create token
    const token = jwt.sign({ userId: user.id }, JWT_SECRET, { expiresIn: '1h' });
    
    // Set cookie
    res.cookie('auth_token', token, {
        httpOnly: false,
        maxAge: 3600000, // 1 hour
        sameSite: 'strict'
    });
    
    res.json({ message: 'Login successful', user: { username: user.username } });
});

// Logout
app.get('/api/logout', (req, res) => {
    res.clearCookie('auth_token');
    res.json({ message: 'Logout successful' });
});

// Get Current User Profile
app.get('/api/profile', authenticate, (req, res) => {
    const { password, ...userWithoutPassword } = req.user;
    res.json({ user: userWithoutPassword });
});

// Get All Posts
app.get('/api/posts', (req, res) => {
    // Return a deep copy of posts to prevent direct modification
    const postsToReturn = posts.map(post => ({ ...post }));
    res.json({ posts: postsToReturn });
});

// Get Post by ID
app.get('/api/posts/:id', (req, res) => {
    const id = parseInt(req.params.id);
    const post = posts.find(p => p.id === id);
    
    if (!post) {
        return res.status(404).json({ error: 'Post not found' });
    }
    
    res.json({ post });
});

// Create Post
app.post('/api/posts', authenticate, (req, res) => {
    const { title, content } = req.body;
    
    // Validation
    if (!title || !content) {
        return res.status(400).json({ error: 'Title and content are required' });
    }
    
    if (title.length < 3 || title.length > 100) {
        return res.status(400).json({ error: 'Title must be between 3 and 100 characters' });
    }
    
    if (content.length < 10 || content.length > 5000) {
        return res.status(400).json({ error: 'Content must be between 10 and 5000 characters' });
    }
    
    // Create new post
    const newPost = {
        id: posts.length + 1,
        title,
        content,
        author: req.user.username,
        createdAt: new Date(),
        reports: 0
    };
    
    posts.push(newPost);
    
    res.status(201).json({ message: 'Post created successfully', post: newPost });
});

// Report Post
app.post('/api/posts/:id/report', authenticate, (req, res) => {
    const id = parseInt(req.params.id);
    const post = posts.find(p => p.id === id);
    
    if (!post) {
        return res.status(404).json({ error: 'Post not found' });
    }
    
    // Increment report count
    post.reports++;
    
    // If this is the first report, simulate admin review
    if (post.reports === 1) {
        // Create an admin bot that visits the reported post
        try {
            createBotInstance(id);
            console.log(`Bot will visit post ID ${id}`);
        } catch (error) {
            console.error('Error creating bot instance:', error);
        }
    }
    
    res.json({ message: 'Post reported successfully', reports: post.reports });
});

// Get Flag (Admin only)
app.get('/api/admin/flag', authenticate, requireAdmin, (req, res) => {
    res.json({ flag: FLAG });
});

// Start the server
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});

// Create public directory if it doesn't exist
const publicDir = path.join(__dirname, 'public');
if (!fs.existsSync(publicDir)) {
    fs.mkdirSync(publicDir, { recursive: true });
} 