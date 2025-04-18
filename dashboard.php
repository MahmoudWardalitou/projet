<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="fr" class="h-full" x-data="dashboardData()">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart IoT Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.2/dist/vanilla-tilt.min.js"></script>
  <script src="script.js" defer></script>
  <style>
    .glass-card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      border-radius: 16px;
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
    .sidebar {
      transition: width 0.3s ease, transform 0.3s ease;
    }
    .sidebar-collapsed {
      width: 4rem;
      transform: translateX(0);
    }
    .sidebar-expanded {
      width: 16rem;
    }
    .main-content {
      transition: margin-left 0.3s ease;
    }
    .sidebar-link {
      transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .sidebar-link:hover {
      transform: translateX(5px);
      background-color: rgba(255, 255, 255, 0.1);
    }
    .progress-ring {
      transform: rotate(-90deg);
    }
    .progress-ring__circle {
      transition: stroke-dashoffset 0.5s ease;
    }
    .modal {
      transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .modal-hidden {
      opacity: 0;
      transform: scale(0.95);
      pointer-events: none;
    }
    .slider {
      -webkit-appearance: none;
      height: 8px;
      border-radius: 4px;
      background: rgba(255, 255, 255, 0.2);
      outline: none;
      transition: background 0.3s ease;
    }
    .slider::-webkit-slider-thumb {
      -webkit-appearance: none;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #3b82f6;
      cursor: pointer;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      transition: transform 0.2s ease;
    }
    .slider::-webkit-slider-thumb:hover {
      transform: scale(1.2);
    }
    .dark .slider {
      background: rgba(255, 255, 255, 0.1);
    }
    .dark .slider::-webkit-slider-thumb {
      background: #93c5fd;
    }
    .chart-tooltip {
      background: rgba(255, 255, 255, 0.9) !important;
      backdrop-filter: blur(5px) !important;
      border-radius: 8px !important;
      border: 1px solid rgba(0, 0, 0, 0.1) !important;
      padding: 12px !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }
    .dark .chart-tooltip {
      background: rgba(15, 23, 42, 0.9) !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      color: white !important;
    }
    .notification {
      animation: slideIn 0.5s ease forwards;
    }
    @keyframes slideIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    .animate-pulse-slow {
      animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex">
  <!-- Sidebar Navigation -->
  <div class="sidebar sidebar-expanded bg-white dark:bg-gray-800 shadow-2xl flex flex-col h-screen fixed z-30"
       :class="sidebarOpen ? 'sidebar-expanded' : 'sidebar-collapsed'">
    <div class="p-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
      <h1 class="text-2xl font-bold text-blue-600 dark:text-blue-400 whitespace-nowrap" x-show="sidebarOpen">
        Smart IoT
      </h1>
      <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
        <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
      </button>
    </div>
    <nav class="flex-1 overflow-y-auto p-2">
      <ul class="space-y-2">
        <li>
          <a href="dashboard.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-tachometer-alt text-lg w-6"></i>
            <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="sensors.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-thermometer-half text-lg w-6"></i>
            <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Sensors</span>
          </a>
        </li>
        <li>
          <a href="analytics.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-chart-line text-lg w-6"></i>
            <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Analytics</span>
          </a>
        </li>
        <li>
          <a href="history.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-history text-lg w-6"></i>
            <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">History</span>
          </a>
        </li>
        <li>
          <a href="settings.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-cog text-lg w-6"></i>
            <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Settings</span>
          </a>
        </li>
      </ul>
    </nav>
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
      <div class="flex items-center">
        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
          <i class="fas fa-user"></i>
        </div>
        <div class="ml-3" x-show="sidebarOpen">
          <p class="text-sm font-medium">Admin</p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Online</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content flex-1 ml-16 md:ml-64 transition-all duration-300 overflow-y-auto h-screen">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
          <h1 class="text-4xl font-extrabold text-gray-800 dark:text-white flex items-center gap-3">
            <i class="fas fa-fire text-orange-500 animate-pulse-slow"></i>
            Smart IoT Dashboard
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2">Real-time sensor monitoring and control</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
          <div class="flex items-center glass-card px-4 py-2">
            <span class="mr-2 text-gray-600 dark:text-gray-300">Status:</span>
            <span x-text="ledOn ? 'ACTIVE' : 'INACTIVE'"
                  :class="ledOn ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-400'"
                  class="px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1 animate-pulse-slow">
              <i x-text="ledOn ? '✓' : '✗'" class="text-xs"></i>
            </span>
          </div>
          <button @click="toggleLed(); ledOn = !ledOn"
                  class="glass-card px-4 py-2 rounded-lg flex items-center gap-2 transition-transform hover:scale-105"
                  :class="ledOn ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white'">
            <i :class="ledOn ? 'fas fa-power-off' : 'fas fa-toggle-off'" class="text-lg"></i>
            <span x-text="ledOn ? 'Turn Off' : 'Turn On'"></span>
          </button>
          <button @click="toggleTheme" class="p-2 rounded-lg glass-card hover:bg-gray-200 dark:hover:bg-gray-700 transition-transform hover:scale-105">
            <i class="fas fa-moon dark:fa-sun text-lg"></i>
          </button>
        </div>
      </header>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Current Temperature Card -->
        <div class="glass-card p-6 relative overflow-hidden group" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 opacity-20 group-hover:opacity-40 transition-opacity"></div>
          <div class="relative z-10">
            <p class="text-gray-600 dark:text-gray-300 flex items-center gap-2">
              <i class="fas fa-thermometer-half text-blue-500"></i>
              CURRENT TEMPERATURE
            </p>
            <div class="flex items-end gap-2 mt-3">
              <span id="currentTemp" class="text-6xl font-extrabold text-gray-800 dark:text-white temperature-display">--</span>
              <span class="text-3xl text-gray-500 dark:text-gray-400 mb-1">°C</span>
            </div>
            <div class="mt-6">
              <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mb-1">
                <span id="tempMin">10°C</span>
                <span id="tempMax">50°C</span>
              </div>
              <div class="progress-bar h-3 rounded-full overflow-hidden">
                <div id="tempProgress" class="progress-value h-full rounded-full bg-gradient-to-r from-blue-400 to-blue-600 transition-all duration-500" style="width: 0%"></div>
              </div>
            </div>
            <div id="tempMessage" class="mt-3 text-sm font-medium flex items-center gap-2">
              <i class="fas fa-info-circle text-blue-500"></i>
              <span>Awaiting data...</span>
            </div>
          </div>
        </div>

        <!-- Temperature Card -->
        <div class="glass-card p-6 relative bg-gray-900/95 dark:bg-gray-900/95" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div class="absolute top-4 right-4 opacity-20">
            <i class="fas fa-chart-line text-6xl"></i>
          </div>
          <div class="relative z-10">
            <p class="text-gray-400 flex items-center gap-2 mb-4">
              <i class="fas fa-thermometer-half text-purple-500"></i>
              TEMPERATURE
            </p>
            <div class="flex items-baseline gap-2 mb-6">
              <span id="currentTemp" class="text-4xl font-bold text-white">--</span>
              <span class="text-xl text-white">°C</span>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div class="bg-gray-800/50 rounded-lg p-3">
                <p class="text-blue-400 text-sm mb-2">Minimum</p>
                <p class="text-white text-xl font-bold mb-1">
                  <span id="min24hTemp">--</span>°C
                </p>
                <p class="text-blue-400 text-sm">
                  <i class="fas fa-arrow-down"></i>
                  <span id="minTempPercent">0</span>%
                </p>
              </div>
              <div class="bg-gray-800/50 rounded-lg p-3">
                <p class="text-red-400 text-sm mb-2">Maximum</p>
                <p class="text-white text-xl font-bold mb-1">
                  <span id="max24hTemp">--</span>°C
                </p>
                <p class="text-red-400 text-sm">
                  <i class="fas fa-arrow-up"></i>
                  <span id="maxTempPercent">0</span>%
                </p>
              </div>
            </div>
            <div id="trendVariation" class="bg-gray-800/50 rounded-lg p-3">
              <div class="flex items-center justify-center gap-2 text-blue-400">
                <i id="trendArrow" class="fas fa-arrow-right"></i>
                <span id="trendPercent">0</span>% vs previous period
              </div>
            </div>
          </div>
        </div>

        <!-- Device Status Card -->
        <div class="glass-card p-6 flex flex-col justify-between" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div>
            <p class="text-gray-600 dark:text-gray-300 flex items-center gap-2">
              <i class="fas fa-microchip text-amber-500"></i>
              DEVICE STATUS
            </p>
            <div class="mt-4 space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-gray-700 dark:text-gray-300">Connectivity</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 animate-pulse-slow">
                  <i class="fas fa-check-circle mr-1"></i> Active
                </span>
              </div>
              <div>
                <div class="flex items-center justify-between mb-1">
                  <span class="text-gray-700 dark:text-gray-300">Memory</span>
                  <span class="text-sm font-medium">64%</span>
                </div>
                <svg class="progress-ring w-16 h-16 mx-auto" viewBox="0 0 36 36">
                  <path class="progress-ring__circle-bg" stroke="#e2e8f0" stroke-width="4" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                  <path class="progress-ring__circle" stroke="#3b82f6" stroke-width="4" fill="none" stroke-dasharray="100, 100" stroke-dashoffset="36" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                </svg>
              </div>
              <div>
                <div class="flex items-center justify-between mb-1">
                  <span class="text-gray-700 dark:text-gray-300">CPU</span>
                  <span class="text-sm font-medium">32%</span>
                </div>
                <svg class="progress-ring w-16 h-16 mx-auto" viewBox="0 0 36 36">
                  <path class="progress-ring__circle-bg" stroke="#e2e8f0" stroke-width="4" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                  <path class="progress-ring__circle" stroke="#10b981" stroke-width="4" fill="none" stroke-dasharray="100, 100" stroke-dashoffset="68" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                </svg>
              </div>
            </div>
          </div>
          <button @click="openModal('settings')" class="mt-4 w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-2 transition-transform hover:scale-105">
            <i class="fas fa-cog"></i>
            <span>Configure</span>
          </button>
        </div>

        <!-- Last Update Card -->
        <div class="glass-card p-6 flex items-center justify-between" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div>
            <p class="text-gray-600 dark:text-gray-300 flex items-center gap-2">
              <i class="fas fa-clock text-violet-500"></i>
              LAST UPDATE
            </p>
            <p class="text-5xl font-extrabold text-gray-800 dark:text-white mt-2">
              <span id="lastUpdate">--:--</span>
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              <span id="lastUpdateAgo">just now</span>
            </p>
          </div>
          <button onclick="fetchAllData()" class="p-3 rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 hover:rotate-180 transition-transform">
            <i class="fas fa-sync-alt text-2xl"></i>
          </button>
        </div>
      </div>

      <!-- Main Chart -->
      <div class="glass-card p-6 mb-8" data-tilt data-tilt-max="5" data-tilt-speed="400">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-chart-area text-blue-500"></i>
            Temperature History
          </h2>
          <div class="flex flex-wrap gap-2">
            <button @click="updateChartRange('24h')" class="px-4 py-2 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-transform hover:scale-105">
              24 Hours
            </button>
            <button @click="updateChartRange('7d')" class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-transform hover:scale-105">
              7 Days
            </button>
            <button @click="updateChartRange('30d')" class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-transform hover:scale-105">
              30 Days
            </button>
          </div>
        </div>
        <div class="relative h-96 bg-white/50 dark:bg-gray-800/50 rounded-xl p-4">
          <canvas id="tempChart"></canvas>
          <div id="chartLoading" class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-gray-800/50 hidden">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
          </div>
        </div>
        <!-- Range Slider -->
        <div class="mt-4">
          <label class="text-sm text-gray-600 dark:text-gray-300">Adjust Range (Hours)</label>
          <input type="range" min="1" max="168" value="24" class="slider w-full mt-2" @input="updateChartRange($event.target.value + 'h')">
        </div>
      </div>

      <!-- Recent Data Table -->
      <div class="glass-card overflow-hidden mb-8" data-tilt data-tilt-max="5" data-tilt-speed="400">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-table text-blue-500"></i>
            Recent Measurements
          </h2>
          <div class="text-sm text-gray-500 dark:text-gray-400">
            <span id="dataCount">0</span> records
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date/Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Value</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody id="recentData" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr class="animate-pulse">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Loading...</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row justify-between gap-4 mb-8">
        <div class="flex gap-3">
          <button onclick="fetchAllData()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-transform hover:scale-105">
            <i class="fas fa-sync-alt"></i>
            Refresh
          </button>
          <button onclick="exportData('pdf')" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg flex items-center gap-2 transition-transform hover:scale-105">
            <i class="fas fa-file-pdf"></i>
            PDF
          </button>
        </div>
        <a href="export.php" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg flex items-center gap-2 justify-center transition-transform hover:scale-105">
          <i class="fas fa-file-csv"></i>
          Export CSV
        </a>
      </div>

      <!-- Data Detail Modal -->
      <div x-show="modalOpen" x-transition class="modal fixed inset-0 bg-black/50 flex items-center justify-center z-50"
           :class="modalOpen ? '' : 'modal-hidden'">
        <div class="glass-card p-8 max-w-md w-full">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Data Details</h2>
            <button @click="modalOpen = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          <div class="space-y-4">
            <p><strong>Date/Time:</strong> <span x-text="selectedData.timestamp || 'N/A'"></span></p>
            <p><strong>Temperature:</strong> <span x-text="selectedData.value ? selectedData.value + '°C' : 'N/A'"></span></p>
            <p><strong>Status:</strong> <span x-text="selectedData.status || 'N/A'"></span></p>
          </div>
          <div class="mt-6 flex justify-end gap-3">
            <button @click="modalOpen = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
              Close
            </button>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="mt-12 text-center text-sm text-gray-500 dark:text-gray-400">
        <p>Smart IoT Monitoring System © <?= date('Y') ?> - All Rights Reserved</p>
        <p class="mt-1">Last Updated: <span id="footerUpdate"><?= date('d/m/Y H:i') ?></span></p>
      </footer>
    </div>
  </div>

  <script>
    function dashboardData() {
      return {
        sidebarOpen: true,
        ledOn: false,
        darkMode: false,
        modalOpen: false,
        selectedData: {},

        init() {
          const savedTheme = localStorage.getItem('theme');
          if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            this.darkMode = true;
          }
          this.setChartTooltipStyle();
          // Initialize Vanilla Tilt
          VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
            max: 10,
            speed: 400,
            glare: true,
            "max-glare": 0.2,
          });
        },

        toggleTheme() {
          this.darkMode = !this.darkMode;
          document.documentElement.classList.toggle('dark');
          localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
          this.setChartTooltipStyle();
          // Trigger chart color update
          document.documentElement.dispatchEvent(new CustomEvent('classChanged'));
        },

        setChartTooltipStyle() {
          if (window.Chart && window.Chart.Tooltip) {
            window.Chart.Tooltip.positioners.custom = function(elements, position) {
              return {
                x: position.x,
                y: position.y - 10
              };
            };
          }
        },

        toggleLed() {
          console.log('LED toggle requested');
        },

        updateChartRange(range) {
          console.log('Chart range update requested:', range);
        },

        openModal(type, data = {}) {
          if (type === 'data' || type === 'settings') {
            this.selectedData = data;
          }
          this.modalOpen = true;
        }
      }
    }
  </script>
</body>
</html>