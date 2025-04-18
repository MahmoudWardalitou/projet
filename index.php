<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart IoT Dashboard</title>
  <!-- Font Awesome (CDN with local fallback) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" onerror="this.href='css/fontawesome.min.css'">
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Vanilla Tilt (CDN with local fallback) -->
  <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.2/dist/vanilla-tilt.min.js" onerror="this.src='js/vanilla-tilt.min.js'"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          animation: {
            'float': 'float 6s ease-in-out infinite',
            'gradient': 'gradient 15s ease infinite',
            'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
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
    .device-3d {
      transform-style: preserve-3d;
      transition: transform 0.5s ease;
    }
    .device-illustration:hover .device-3d {
      transform: rotateX(10deg) rotateY(-10deg) scale(1.05);
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
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 bg-animate-gradient flex flex-col items-center justify-center p-4 overflow-hidden">
  <!-- Background Effects -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute top-10 left-20 w-48 h-48 bg-white/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-20 w-64 h-64 bg-purple-300/20 rounded-full blur-3xl animate-float animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/4 w-96 h-96 bg-pink-300/10 rounded-full blur-3xl animate-float animation-delay-4000"></div>
  </div>

  <!-- Main Content -->
  <div class="glass-card p-8 md:p-12 max-w-5xl w-full z-10" data-tilt data-tilt-max="15" data-tilt-speed="400" data-tilt-perspective="1000">
    <div class="flex flex-col md:flex-row items-center gap-12">
      <!-- Device Illustration -->
      <div class="device-illustration flex-1">
        <div class="device-3d">
          <div class="relative w-64 h-64 mx-auto">
            <div class="absolute inset-0 bg-white/20 rounded-3xl shadow-2xl"></div>
            <div class="absolute inset-3 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-inner flex items-center justify-center">
              <div class="text-white text-5xl font-bold animate-pulse-slow">IoT</div>
            </div>
            <div class="absolute -bottom-6 left-6 right-6 h-6 bg-gray-900/50 rounded-b-xl blur-md"></div>
          </div>
        </div>
      </div>

      <!-- Text and CTA -->
      <div class="flex-1 text-center md:text-left">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 text-white dark:text-white leading-tight">
          <span class="inline-block animate-pulse">🌡️</span>
          Smart IoT Monitoring
        </h1>
        <p class="text-xl text-white/90 mb-8 dark:text-white/80">
          Real-time insights with cutting-edge IoT technology for seamless monitoring and control.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
          <a href="dashboard.php" class="btn-neumorphic px-8 py-3 rounded-full font-bold text-lg text-blue-600 dark:text-white shadow-lg">
            <i class="fas fa-tachometer-alt mr-2"></i> Explore Dashboard
          </a>
          <a href="features.php" class="px-8 py-3 bg-transparent border-2 border-white text-white rounded-full font-bold text-lg hover:bg-white/20 transition-all">
            <i class="fas fa-info-circle mr-2"></i> Discover Features
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Theme Toggle -->
  <div class="absolute bottom-4 right-4">
    <button id="themeToggle" class="theme-toggle w-14 h-14 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/30">
      <i class="fas fa-moon text-2xl"></i>
    </button>
  </div>

  <script>
    // Detect backdrop-filter support
    if (!CSS.supports('backdrop-filter', 'blur(12px)')) {
      document.body.classList.add('no-backdrop-filter');
    }

    // Theme Toggle
    const themeToggle = document.getElementById('themeToggle');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    const currentTheme = localStorage.getItem('theme') || (prefersDarkScheme.matches ? 'dark' : 'light');

    if (currentTheme === 'dark') {
      document.documentElement.classList.add('dark');
      themeToggle.innerHTML = '<i class="fas fa-sun text-2xl"></i>';
    }

    themeToggle.addEventListener('click', () => {
      document.documentElement.classList.toggle('dark');
      const newTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
      localStorage.setItem('theme', newTheme);
      themeToggle.innerHTML = `<i class="fas fa-${newTheme === 'dark' ? 'sun' : 'moon'} text-2xl"></i>`;
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
  </script>
</body>
</html>