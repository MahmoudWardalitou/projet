<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historical Data - Smart IoT Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
  <script src="script.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    [x-cloak] { display: none !important; }
    .glass-card {
      @apply bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50;
    }
    .progress-ring__circle {
      transition: stroke-dashoffset 0.35s;
      transform: rotate(-90deg);
      transform-origin: 50% 50%;
    }
    .progress-ring__circle-bg {
      stroke: currentColor;
      opacity: 0.2;
    }
    .chart-tooltip {
      @apply bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border border-gray-200/50 dark:border-gray-700/50 shadow-lg rounded-lg p-2;
    }
    .dark .chart-tooltip {
      @apply bg-gray-800/90 border-gray-700/50 text-white;
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
<body class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex" x-data="historyData()">
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
            <i class="fas fa-history text-blue-500 animate-pulse-slow"></i>
            Historical Data
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2">View and analyze historical sensor data</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
          <button @click="toggleTheme" class="p-2 rounded-lg glass-card hover:bg-gray-200 dark:hover:bg-gray-700 transition-transform hover:scale-105">
            <i class="fas fa-moon dark:fa-sun text-lg"></i>
          </button>
        </div>
      </header>

      <!-- Filter Controls -->
      <div class="glass-card p-6 mb-8" data-tilt data-tilt-max="5" data-tilt-speed="400">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Sensor Selection -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Sensor</label>
            <select x-model="selectedSensor" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
              <option value="temperature">Temperature</option>
              <option value="humidity">Humidity</option>
              <option value="pressure">Pressure</option>
            </select>
          </div>
          <!-- Date Range -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
            <div class="flex gap-2">
              <button @click="setDateRange('24h')" class="px-4 py-2 rounded-lg" :class="dateRange === '24h' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                24h
              </button>
              <button @click="setDateRange('7d')" class="px-4 py-2 rounded-lg" :class="dateRange === '7d' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                7d
              </button>
              <button @click="setDateRange('30d')" class="px-4 py-2 rounded-lg" :class="dateRange === '30d' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                30d
              </button>
              <button @click="setDateRange('custom')" class="px-4 py-2 rounded-lg" :class="dateRange === 'custom' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                Custom
              </button>
            </div>
          </div>
          <!-- Custom Date Range (shown when custom is selected) -->
          <div x-show="dateRange === 'custom'" class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
              <input type="datetime-local" x-model="startDate" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
              <input type="datetime-local" x-model="endDate" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
          </div>
        </div>
        <div class="mt-6 flex justify-end gap-4">
          <button @click="downloadReport()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-transform hover:scale-105">
            <i class="fas fa-file-alt"></i>
            Download Report
          </button>
          <button @click="exportData('csv')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg flex items-center gap-2 transition-transform hover:scale-105">
            <i class="fas fa-file-csv"></i>
            Export CSV
          </button>
          <button @click="exportData('pdf')" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg flex items-center gap-2 transition-transform hover:scale-105">
            <i class="fas fa-file-pdf"></i>
            Export PDF
          </button>
        </div>
      </div>

      <!-- Historical Chart -->
      <div class="glass-card p-6 mb-8" data-tilt data-tilt-max="5" data-tilt-speed="400">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-chart-area text-blue-500"></i>
            Historical Trends
          </h2>
          <div class="flex flex-wrap gap-2">
            <button @click="updateChartType('line')" class="px-4 py-2 rounded-lg" :class="chartType === 'line' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
              <i class="fas fa-chart-line"></i>
            </button>
            <button @click="updateChartType('bar')" class="px-4 py-2 rounded-lg" :class="chartType === 'bar' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
              <i class="fas fa-chart-bar"></i>
            </button>
            <button @click="updateChartType('area')" class="px-4 py-2 rounded-lg" :class="chartType === 'area' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
              <i class="fas fa-chart-area"></i>
            </button>
          </div>
        </div>
        <div class="relative h-96 bg-white/50 dark:bg-gray-800/50 rounded-xl p-4">
          <canvas id="historyChart"></canvas>
          <div id="chartLoading" class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-gray-800/50 hidden">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
          </div>
        </div>
      </div>

      <!-- Data Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Average Value Card -->
        <div class="glass-card p-6" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Average Value</h3>
            <i class="fas fa-calculator text-blue-500 text-xl"></i>
          </div>
          <div class="flex items-baseline gap-2">
            <span id="avgValue" class="text-4xl font-bold text-gray-800 dark:text-white">--</span>
            <span id="avgUnit" class="text-xl text-gray-500 dark:text-gray-400">°C</span>
          </div>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Based on selected period</p>
        </div>

        <!-- Minimum Value Card -->
        <div class="glass-card p-6" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Minimum Value</h3>
            <i class="fas fa-arrow-down text-emerald-500 text-xl"></i>
          </div>
          <div class="flex items-baseline gap-2">
            <span id="minValue" class="text-4xl font-bold text-gray-800 dark:text-white">--</span>
            <span id="minUnit" class="text-xl text-gray-500 dark:text-gray-400">°C</span>
          </div>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-2" id="minTimestamp">--</p>
        </div>

        <!-- Maximum Value Card -->
        <div class="glass-card p-6" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Maximum Value</h3>
            <i class="fas fa-arrow-up text-rose-500 text-xl"></i>
          </div>
          <div class="flex items-baseline gap-2">
            <span id="maxValue" class="text-4xl font-bold text-gray-800 dark:text-white">--</span>
            <span id="maxUnit" class="text-xl text-gray-500 dark:text-gray-400">°C</span>
          </div>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-2" id="maxTimestamp">--</p>
        </div>
      </div>

      <!-- Historical Data Table -->
      <div class="glass-card overflow-hidden mb-8" data-tilt data-tilt-max="5" data-tilt-speed="400">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-table text-blue-500"></i>
            Historical Records
          </h2>
          <div class="text-sm text-gray-500 dark:text-gray-400">
            <span id="recordCount">0</span> records
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
            <tbody id="historyData" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr class="animate-pulse">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Loading...</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 dark:text-gray-400">Page</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">1</span>
            <span class="text-sm text-gray-500 dark:text-gray-400">of</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">1</span>
          </div>
          <div class="flex gap-2">
            <button class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 disabled:opacity-50">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 disabled:opacity-50">
              <i class="fas fa-chevron-right"></i>
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
    function historyData() {
      return {
        sidebarOpen: true,
        darkMode: false,
        selectedSensor: 'temperature',
        dateRange: '24h',
        startDate: '',
        endDate: '',
        chartType: 'line',
        chart: null,

        init() {
          const savedTheme = localStorage.getItem('theme');
          if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            this.darkMode = true;
          }
          this.setChartTooltipStyle();
          this.initChart();
          this.fetchHistoricalData();
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
          this.updateChartColors();
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

        initChart() {
          const ctx = document.getElementById('historyChart').getContext('2d');
          this.chart = new Chart(ctx, {
            type: this.chartType,
            data: {
              labels: [],
              datasets: [{
                label: 'Temperature',
                data: [],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false
                },
                tooltip: {
                  position: 'custom',
                  backgroundColor: 'rgba(255, 255, 255, 0.9)',
                  titleColor: '#1f2937',
                  bodyColor: '#1f2937',
                  borderColor: 'rgba(229, 231, 235, 0.5)',
                  borderWidth: 1,
                  padding: 12,
                  displayColors: false,
                  callbacks: {
                    label: function(context) {
                      return `${context.parsed.y}°C`;
                    }
                  }
                }
              },
              scales: {
                x: {
                  grid: {
                    display: false
                  }
                },
                y: {
                  beginAtZero: false,
                  grid: {
                    color: 'rgba(229, 231, 235, 0.5)'
                  }
                }
              }
            }
          });
        },

        updateChartType(type) {
          this.chartType = type;
          this.chart.config.type = type;
          this.chart.update();
        },

        updateChartColors() {
          const isDark = this.darkMode;
          this.chart.options.plugins.tooltip.backgroundColor = isDark ? 'rgba(31, 41, 55, 0.9)' : 'rgba(255, 255, 255, 0.9)';
          this.chart.options.plugins.tooltip.titleColor = isDark ? '#f3f4f6' : '#1f2937';
          this.chart.options.plugins.tooltip.bodyColor = isDark ? '#f3f4f6' : '#1f2937';
          this.chart.options.plugins.tooltip.borderColor = isDark ? 'rgba(75, 85, 99, 0.5)' : 'rgba(229, 231, 235, 0.5)';
          this.chart.options.scales.y.grid.color = isDark ? 'rgba(75, 85, 99, 0.5)' : 'rgba(229, 231, 235, 0.5)';
          this.chart.update();
        },

        async fetchHistoricalData() {
          const loading = document.getElementById('chartLoading');
          loading.classList.remove('hidden');
          
          try {
            const response = await fetch('chart.php');
            const data = await response.json();
            
            // Update chart data
            this.chart.data.labels = data.timestamps;
            this.chart.data.datasets[0].data = data.values;
            this.chart.update();

            // Update summary cards
            const values = data.values;
            const avg = values.reduce((a, b) => a + b, 0) / values.length;
            const min = Math.min(...values);
            const max = Math.max(...values);

            document.getElementById('avgValue').textContent = avg.toFixed(1);
            document.getElementById('minValue').textContent = min.toFixed(1);
            document.getElementById('maxValue').textContent = max.toFixed(1);

            // Update timestamps
            const minIndex = values.indexOf(min);
            const maxIndex = values.indexOf(max);
            document.getElementById('minTimestamp').textContent = data.timestamps[minIndex];
            document.getElementById('maxTimestamp').textContent = data.timestamps[maxIndex];

            // Update record count
            document.getElementById('recordCount').textContent = values.length;

          } catch (error) {
            console.error('Error fetching historical data:', error);
          } finally {
            loading.classList.add('hidden');
          }
        },

        exportData(format) {
          console.log(`Exporting data as ${format}...`);
          // Implement export functionality
        },

        downloadReport() {
          console.log('Downloading report...');
          // Implement download report functionality
        },

        setDateRange(range) {
          this.dateRange = range;
          if (range === 'custom') {
            this.startDate = '';
            this.endDate = '';
          }
        }
      }
    }
  </script>
</body>
</html> 