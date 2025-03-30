// Haunted theme JavaScript

// Create multiple ghosts when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Create random floating ghosts
    const ghostContainer = document.querySelector('.ghost-container');
    if (ghostContainer) {
        // Already has one ghost from HTML, add more
        for (let i = 0; i < 3; i++) {
            const ghost = document.createElement('div');
            ghost.className = 'ghost';
            
            // Randomize ghost position, size and animation
            const size = Math.floor(Math.random() * 30) + 40; // 40-70px
            ghost.style.width = size + 'px';
            ghost.style.height = (size * 1.2) + 'px';
            
            // Random delay for animation
            const delay = Math.random() * 15;
            ghost.style.animationDelay = delay + 's';
            
            // Random duration for animation
            const duration = Math.random() * 10 + 15; // 15-25s
            ghost.style.animationDuration = duration + 's';
            
            // Random start position
            const startX = Math.random() * 100;
            ghost.style.left = startX + '%';
            
            ghostContainer.appendChild(ghost);
        }
    }
    
    // Spooky cursor effect (subtle purple glow following cursor)
    document.addEventListener('mousemove', function(e) {
        const cursorGlow = document.createElement('div');
        cursorGlow.className = 'cursor-glow';
        cursorGlow.style.left = e.pageX + 'px';
        cursorGlow.style.top = e.pageY + 'px';
        
        document.body.appendChild(cursorGlow);
        
        // Remove the glow after animation completes
        setTimeout(() => {
            cursorGlow.remove();
        }, 1000);
    });
    
    // Add CSS for cursor glow
    const style = document.createElement('style');
    style.textContent = `
        .cursor-glow {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(95, 51, 255, 0.5) 0%, rgba(95, 51, 255, 0) 70%);
            pointer-events: none;
            transform: translate(-50%, -50%);
            z-index: 9999;
            opacity: 0.5;
            animation: cursorFade 1s forwards;
        }
        
        @keyframes cursorFade {
            0% { opacity: 0.5; width: 20px; height: 20px; }
            100% { opacity: 0; width: 40px; height: 40px; }
        }
    `;
    document.head.appendChild(style);
});

// Function to create spooky text animation (letter by letter typing)
function spookyType(element, text, speed = 50) {
    let i = 0;
    element.innerHTML = '';
    
    function type() {
        if (i < text.length) {
            element.innerHTML += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }
    
    type();
}

// Function to create a subtle shake animation for an element
function spookyShake(element, intensity = 5, duration = 500) {
    const originalPosition = {
        x: element.offsetLeft,
        y: element.offsetTop
    };
    
    const startTime = Date.now();
    
    function shake() {
        const elapsed = Date.now() - startTime;
        
        if (elapsed < duration) {
            const xOffset = (Math.random() * intensity * 2) - intensity;
            const yOffset = (Math.random() * intensity * 2) - intensity;
            
            element.style.position = 'relative';
            element.style.left = xOffset + 'px';
            element.style.top = yOffset + 'px';
            
            requestAnimationFrame(shake);
        } else {
            // Reset position
            element.style.left = '0px';
            element.style.top = '0px';
        }
    }
    
    shake();
}

// Easter egg - spooky message in console
console.log(
    '%c👻 Beware of lurking spirits in the code... 👻',
    'color: #5f33ff; font-size: 16px; font-weight: bold;'
);
console.log(
    '%cSome vulnerabilities can be exploited...',
    'color: #ff3366; font-size: 12px;'
);

// Haunted Forum JavaScript Functions

// Initialize ghost effect on page load
document.addEventListener('DOMContentLoaded', function() {
    initGhostEffect();
    initLoadingDots();
});

// Create multiple random ghosts in the ghost container
function initGhostEffect() {
    const ghostContainer = document.querySelector('.ghost-container');
    if (!ghostContainer) return;
    
    // Clear existing ghosts
    while (ghostContainer.firstChild) {
        ghostContainer.removeChild(ghostContainer.firstChild);
    }
    
    // Create multiple ghosts
    const numGhosts = 3 + Math.floor(Math.random() * 3); // 3-5 ghosts
    
    for (let i = 0; i < numGhosts; i++) {
        createGhost(ghostContainer);
    }
}

// Create a single ghost element with random properties
function createGhost(container) {
    const ghost = document.createElement('div');
    ghost.className = 'ghost';
    
    // Random size
    const size = 40 + Math.random() * 40; // 40-80px
    ghost.style.width = `${size}px`;
    ghost.style.height = `${size * 1.25}px`;
    
    // Random position
    ghost.style.left = `${Math.random() * 90}%`;
    ghost.style.top = `${Math.random() * 90}%`;
    
    // Random opacity
    ghost.style.opacity = `${0.03 + Math.random() * 0.07}`; // Very faint: 0.03-0.1
    
    // Random animation duration and delay
    const duration = 15 + Math.random() * 20; // 15-35s
    const delay = Math.random() * 10; // 0-10s delay
    ghost.style.animationDuration = `${duration}s`;
    ghost.style.animationDelay = `${delay}s`;
    
    container.appendChild(ghost);
    
    // Remove and recreate ghost after animation completes
    setTimeout(() => {
        if (container.contains(ghost)) {
            container.removeChild(ghost);
            createGhost(container);
        }
    }, (duration + delay) * 1000);
}

// Initialize the loading dots animation
function initLoadingDots() {
    const loadingElements = document.querySelectorAll('.spooky-loading .dots');
    
    loadingElements.forEach(dotElement => {
        animateDots(dotElement);
    });
}

// Animate the loading dots
function animateDots(element) {
    const phases = [".", "..", "..."];
    let currentPhase = 0;
    
    setInterval(() => {
        element.textContent = phases[currentPhase];
        currentPhase = (currentPhase + 1) % phases.length;
    }, 500);
}

// Add shake effect to element
function addShakeEffect(element) {
    element.classList.add('shake');
    setTimeout(() => {
        element.classList.remove('shake');
    }, 820); // Duration of shake animation
}

// Add spooky typing effect to element
function spookyType(element, text) {
    element.innerHTML = '';
    element.style.opacity = '1';
    
    let i = 0;
    const speed = 70; // typing speed in milliseconds
    
    function type() {
        if (i < text.length) {
            element.innerHTML += text.charAt(i);
            i++;
            setTimeout(type, speed + Math.random() * 50);
        }
    }
    
    type();
}

// Format date in a spooky way
function formatSpookyDate(date) {
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    
    return new Date(date).toLocaleDateString('en-US', options);
}

// Escape HTML to prevent XSS
function escapeHTML(str) {
    if (!str) return '';
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Random spooky messages for loading/error states
const spookyMessages = [
    "Summoning digital spirits...",
    "Consulting the virtual ouija board...",
    "Channeling supernatural data...",
    "Conjuring content from the void...",
    "Communing with server ghosts...",
    "Awakening dormant bytes...",
    "Beckoning from the digital beyond...",
    "Manifesting ethereal content..."
];

// Get a random spooky message
function getRandomSpookyMessage() {
    const index = Math.floor(Math.random() * spookyMessages.length);
    return spookyMessages[index];
}

// Add flicker effect to element
function addFlickerEffect(element) {
    const originalOpacity = element.style.opacity || '1';
    const flickerSequence = [
        { opacity: '0.3', duration: 100 },
        { opacity: '1', duration: 50 },
        { opacity: '0.5', duration: 70 },
        { opacity: '1', duration: 30 },
        { opacity: '0.7', duration: 50 },
        { opacity: originalOpacity, duration: 0 }
    ];
    
    let currentStep = 0;
    
    function executeFlicker() {
        if (currentStep >= flickerSequence.length) return;
        
        const step = flickerSequence[currentStep];
        element.style.opacity = step.opacity;
        
        currentStep++;
        setTimeout(executeFlicker, step.duration);
    }
    
    executeFlicker();
} 