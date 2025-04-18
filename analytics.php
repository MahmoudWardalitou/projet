<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="fr" class="h-full" x-data="analyticsData()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT Analytics Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script src="script.js" defer></script>
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900">
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
    </div>

    <!-- Main Content -->
    <div class="flex-1 ml-0 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-20'>
        <div class="min-h-screen flex">
            <!-- Analytics Content -->
            <div class="flex-1 p-8">
                <header class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                        <i class="fas fa-chart-line text-blue-500"></i>
                        Analytics Dashboard
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Comprehensive IoT data analysis and insights</p>
                </header>

                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Temperature KPI -->
                    <div class="glass-card p-6" data-tilt>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Average Temperature</h3>
                            <i class="fas fa-temperature-high text-blue-500 text-xl"></i>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white" x-text="avgTemp">--</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">°C</span>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-500 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span x-text="tempTrend">2.5</span>%
                                </span>
                                <span class="text-gray-600 dark:text-gray-400 ml-2">vs last week</span>
                            </div>
                        </div>
                    </div>

                    <!-- Data Points KPI -->
                    <div class="glass-card p-6" data-tilt>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Total Readings</h3>
                            <i class="fas fa-database text-purple-500 text-xl"></i>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white" x-text="totalReadings">--</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">points</span>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-blue-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    Last 24 hours
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- System Uptime KPI -->
                    <div class="glass-card p-6" data-tilt>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">System Uptime</h3>
                            <i class="fas fa-clock text-green-500 text-xl"></i>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white" x-text="uptime">--</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">hours</span>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-500">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Operational
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Alerts KPI -->
                    <div class="glass-card p-6" data-tilt>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Active Alerts</h3>
                            <i class="fas fa-bell text-amber-500 text-xl"></i>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white" x-text="activeAlerts">--</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">alerts</span>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-amber-500">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Requires attention
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Temperature Trend Chart -->
                    <div class="glass-card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Temperature Trends</h3>
                            <div class="flex gap-2">
                                <button @click="updateTempChart('24h')" class="px-3 py-1 rounded-lg text-sm" :class="tempChartRange === '24h' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700'">24h</button>
                                <button @click="updateTempChart('7d')" class="px-3 py-1 rounded-lg text-sm" :class="tempChartRange === '7d' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700'">7d</button>
                                <button @click="updateTempChart('30d')" class="px-3 py-1 rounded-lg text-sm" :class="tempChartRange === '30d' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700'">30d</button>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="tempTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Distribution Chart -->
                    <div class="glass-card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Temperature Distribution</h3>
                            <div class="flex items-center gap-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total samples: <span x-text="totalSamples">0</span></span>
                            </div>
                        </div>
                        <div class="h-80">
                            <div id="tempDistChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Analytics -->
                <div class="glass-card p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Detailed Analysis</h3>
                        <div class="flex gap-4">
                            <button @click="exportData('csv')" class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fas fa-download"></i>
                                Export CSV
                            </button>
                            <button @click="exportData('pdf')" class="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                <i class="fas fa-file-pdf"></i>
                                Export PDF
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metric</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Value</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Change</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="metricsTable">
                                <!-- Table content will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 