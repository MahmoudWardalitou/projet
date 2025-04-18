<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Discover Features - Smart IoT Dashboard</title>
  <!-- Font Awesome (CDN with local fallback) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" onerror="this.href='css/fontawesome.min.css'">
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Vanilla Tilt (CDN with local fallback) -->
  <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.2/dist/vanilla-tilt.min.js" onerror="this.src='js/vanilla-tilt.min.js'"></script>
  <!-- Particles.js for ambient animations -->
  <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          animation: {
            'float': 'float 6s ease-in-out infinite',
            'gradient': 'gradient 15s ease infinite',
            'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            'glow': 'glow 2s ease-in-out infinite alternate',
            'spin-slow': 'spin 8s linear infinite',
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-20px)' },
            },
            gradient: {
              '0%, 100%': { 'background-position': '0% 50%' },
              '50%': { 'background-position': '100% 50%' },
            },
            pulse: {
              '0%, 100%': { opacity: 1 },
              '50%': { opacity: 0.5 },
            },
            glow: {
              '0%': { boxShadow: '0 0 5px rgba(255, 255, 255, 0.5), 0 0 10px rgba(255, 255, 255, 0.3), 0 0 15px rgba(255, 255, 255, 0.2)' },
              '100%': { boxShadow: '0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.5), 0 0 30px rgba(255, 255, 255, 0.3)' },
            },
          },
        },
      },
    };
  </script>
  <style>
    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    }
    .dark .glass-card {
      background: rgba(0, 0, 0, 0.3);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .feature-icon {
      transition: all 0.5s ease;
    }
    .feature-icon:hover {
      transform: scale(1.1) translateY(-5px);
    }
    .btn-neumorphic {
      background: linear-gradient(145deg, #ffffff, #e6e6e6);
      box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1), -5px -5px 10px rgba(255, 255, 255, 0.5);
      transition: all 0.3s ease;
    }
    .btn-neumorphic:hover {
      transform: translateY(-2px);
      box-shadow: 8px 8px 15px rgba(0, 0, 0, 0.2), -8px -8px 15px rgba(255, 255, 255, 0.7);
    }
    .dark .btn-neumorphic {
      background: linear-gradient(145deg, #2d2d2d, #1a1a1a);
      box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.4), -5px -5px 10px rgba(255, 255, 255, 0.1);
    }
    .theme-toggle {
      transition: all 0.5s ease;
    }
    .theme-toggle:hover {
      transform: rotate(360deg);
    }
    [data-tilt] {
      perspective: 1000px;
    }
    /* Fallback for browsers without backdrop-filter */
    .no-backdrop-filter .glass-card {
      background: rgba(255, 255, 255, 0.3);
    }
    .no-backdrop-filter .dark .glass-card {
      background: rgba(0, 0, 0, 0.5);
    }
    .feature-card {
      transition: all 0.4s ease;
    }
    .feature-card:hover {
      transform: translateY(-10px);
    }
    .particle-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      pointer-events: none;
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 bg-animate-gradient flex flex-col items-center p-4 overflow-y-auto">
  <!-- Particles Background -->
  <div id="particles-js" class="particle-container fixed inset-0"></div>
  
  <!-- Background Effects -->
  <div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-10 left-20 w-48 h-48 bg-white/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-20 w-64 h-64 bg-purple-300/20 rounded-full blur-3xl animate-float animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/4 w-96 h-96 bg-pink-300/10 rounded-full blur-3xl animate-float animation-delay-4000"></div>
  </div>

  <!-- Main Content -->
  <div class="glass-card p-8 md:p-12 max-w-5xl w-full z-10 my-8" data-tilt data-tilt-max="15" data-tilt-speed="400" data-tilt-perspective="1000">
    <div class="flex flex-col items-center gap-8">
      <!-- Title and Description -->
      <div class="text-center max-w-3xl">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 text-white dark:text-white leading-tight">
          <span class="inline-block animate-pulse">🚀</span>
          Discover the Future of Smart IoT
        </h1>
        <p class="text-xl text-white/90 mb-8 dark:text-white/80">
          Experience the next generation of intelligent monitoring and control systems. Our Smart IoT Dashboard combines cutting-edge technology with intuitive design to provide you with real-time insights and seamless automation capabilities.
        </p>
      </div>

      <!-- Feature Icons -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
        <!-- Real-time Monitoring -->
        <div class="feature-card glass-card p-6 flex flex-col items-center text-center" data-tilt data-tilt-max="10" data-tilt-speed="300">
          <div class="feature-icon w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mb-4 animate-glow">
            <i class="fas fa-chart-line text-3xl text-white"></i>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Real-time Monitoring</h3>
          <p class="text-white/80">Track your IoT devices with precision using our advanced real-time monitoring system.</p>
        </div>

        <!-- Smart Control -->
        <div class="feature-card glass-card p-6 flex flex-col items-center text-center" data-tilt data-tilt-max="10" data-tilt-speed="300">
          <div class="feature-icon w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center mb-4 animate-glow">
            <i class="fas fa-sliders-h text-3xl text-white"></i>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Smart Control</h3>
          <p class="text-white/80">Manage your devices effortlessly with our intuitive control interface and automation tools.</p>
        </div>

        <!-- Intelligent Alerts -->
        <div class="feature-card glass-card p-6 flex flex-col items-center text-center" data-tilt data-tilt-max="10" data-tilt-speed="300">
          <div class="feature-icon w-20 h-20 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center mb-4 animate-glow">
            <i class="fas fa-bell text-3xl text-white"></i>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Intelligent Alerts</h3>
          <p class="text-white/80">Stay informed with smart notifications that alert you to important events and anomalies.</p>
        </div>
      </div>

      <!-- CTA Button -->
      <div class="mt-8">
        <a href="dashboard.php" class="btn-neumorphic px-10 py-4 rounded-full font-bold text-xl text-blue-600 dark:text-white shadow-lg inline-flex items-center">
          <i class="fas fa-rocket mr-3"></i> Let's Explore
        </a>
      </div>
    </div>
  </div>

  <!-- Theme Toggle -->
  <div class="fixed bottom-4 right-4 z-20">
    <button id="themeToggle" class="theme-toggle w-14 h-14 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/30">
      <i class="fas fa-moon text-2xl"></i>
    </button>
  </div>

  <!-- Back to Home -->
  <div class="fixed top-4 left-4 z-20">
    <a href="index.php" class="px-4 py-2 rounded-full bg-white/20 backdrop-blur-md text-white hover:bg-white/30 transition-all flex items-center">
      <i class="fas fa-arrow-left mr-2"></i> Back to Home
    </a>
  </div>

  <script>
    // Detect backdrop-filter support
    if (!CSS.supports('backdrop-filter', 'blur(12px)')) {
      document.body.classList.add('no-backdrop-filter');
    }

    // Theme Toggle with sound effect
    const themeToggle = document.getElementById('themeToggle');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    const currentTheme = localStorage.getItem('theme') || (prefersDarkScheme.matches ? 'dark' : 'light');
    const toggleSound = new Audio('data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU');

    if (currentTheme === 'dark') {
      document.documentElement.classList.add('dark');
      themeToggle.innerHTML = '<i class="fas fa-sun text-2xl"></i>';
    }

    themeToggle.addEventListener('click', () => {
      document.documentElement.classList.toggle('dark');
      const newTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
      localStorage.setItem('theme', newTheme);
      themeToggle.innerHTML = `<i class="fas fa-${newTheme === 'dark' ? 'sun' : 'moon'} text-2xl"></i>`;
      
      // Play sound effect (very subtle click)
      toggleSound.currentTime = 0;
      toggleSound.play().catch(e => console.log('Sound play failed:', e));
    });

    // Initialize Vanilla Tilt with Error Handling
    try {
      if (typeof VanillaTilt !== 'undefined') {
        VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
          max: 15,
          speed: 400,
          glare: true,
          "max-glare": 0.3,
        });
      } else {
        console.warn('VanillaTilt not loaded. 3D tilt effect disabled.');
      }
    } catch (error) {
      console.error('Error initializing VanillaTilt:', error);
    }

    // Initialize Particles.js
    try {
      if (typeof particlesJS !== 'undefined') {
        particlesJS('particles-js', {
          particles: {
            number: {
              value: 80,
              density: {
                enable: true,
                value_area: 800
              }
            },
            color: {
              value: '#ffffff'
            },
            shape: {
              type: 'circle',
              stroke: {
                width: 0,
                color: '#000000'
              },
              polygon: {
                nb_sides: 5
              }
            },
            opacity: {
              value: 0.2,
              random: true,
              anim: {
                enable: true,
                speed: 1,
                opacity_min: 0.1,
                sync: false
              }
            },
            size: {
              value: 3,
              random: true,
              anim: {
                enable: true,
                speed: 2,
                size_min: 0.1,
                sync: false
              }
            },
            line_linked: {
              enable: true,
              distance: 150,
              color: '#ffffff',
              opacity: 0.1,
              width: 1
            },
            move: {
              enable: true,
              speed: 1,
              direction: 'none',
              random: true,
              straight: false,
              out_mode: 'out',
              bounce: false,
              attract: {
                enable: false,
                rotateX: 600,
                rotateY: 1200
              }
            }
          },
          interactivity: {
            detect_on: 'canvas',
            events: {
              onhover: {
                enable: true,
                mode: 'grab'
              },
              onclick: {
                enable: true,
                mode: 'push'
              },
              resize: true
            },
            modes: {
              grab: {
                distance: 140,
                line_linked: {
                  opacity: 0.3
                }
              },
              push: {
                particles_nb: 4
              }
            }
          },
          retina_detect: true
        });
      } else {
        console.warn('Particles.js not loaded. Particle effects disabled.');
      }
    } catch (error) {
      console.error('Error initializing Particles.js:', error);
    }
  </script>
</body>
</html> 