<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="fr" class="h-full" x-data="sensorsData()">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart IoT - Sensors Management</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.2/dist/vanilla-tilt.min.js"></script>
  <link rel="stylesheet" href="style.css">
  <script src="sensors.js" defer></script>
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
            <i class="fas fa-thermometer-half text-blue-500"></i>
            Sensors Management
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2">Configure and monitor your IoT sensors</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
          <button @click="toggleTheme" class="p-2 rounded-lg glass-card hover:bg-gray-200 dark:hover:bg-gray-700 transition-transform hover:scale-105">
            <i class="fas fa-moon dark:fa-sun text-lg"></i>
          </button>
          <button @click="openModal('addSensor')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-transform hover:scale-105">
            <i class="fas fa-plus"></i>
            <span>Add Sensor</span>
          </button>
        </div>
      </header>

      <!-- Sensor Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Temperature Sensor Card -->
        <div class="glass-card p-6 relative overflow-hidden group" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 opacity-20 group-hover:opacity-40 transition-opacity"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-gray-600 dark:text-gray-300 flex items-center gap-2">
                  <i class="fas fa-thermometer-half text-blue-500"></i>
                  TEMPERATURE SENSOR
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ID: TEMP-001</p>
              </div>
              <div class="flex gap-2">
                <button @click="toggleSensorStatus('TEMP-001')" class="p-2 rounded-full" :class="sensorStatus ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400'" class="hover:bg-green-200 dark:hover:bg-green-800 transition-transform hover:scale-105">
                  <i class="fas fa-power-off"></i>
                </button>
                <button @click="toggleAlert('TEMP-001')" class="p-2 rounded-full" :class="alertEnabled ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' : 'bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400'" class="hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-transform hover:scale-105">
                  <i class="fas fa-bell"></i>
                </button>
                <button @click="openModal('editSensor', {id: 'TEMP-001', name: 'Temperature Sensor', type: 'temperature'})" class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-transform hover:scale-105">
                  <i class="fas fa-cog"></i>
                </button>
              </div>
            </div>
            <div class="flex items-end gap-2 mt-3">
              <span id="tempSensorValue" class="text-6xl font-extrabold" :class="{'text-red-600 dark:text-red-400': currentTemp > 35, 'text-gray-800 dark:text-white': currentTemp <= 35}">--</span>
              <span class="text-3xl text-gray-500 dark:text-gray-400 mb-1">°C</span>
            </div>
            <div class="mt-6">
              <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mb-1">
                <span>10°C</span>
                <span>50°C</span>
              </div>
              <div class="progress-bar h-3 rounded-full overflow-hidden">
                <div id="tempSensorProgress" class="progress-value h-full rounded-full bg-gradient-to-r from-blue-400 to-blue-600 transition-all duration-500" :class="{'from-red-400 to-red-600': currentTemp > 35}" style="width: 0%"></div>
              </div>
            </div>
            <div class="mt-4 flex justify-between items-center">
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" :class="sensorStatus ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400'">
                  <i class="fas" :class="sensorStatus ? 'fa-check-circle' : 'fa-times-circle'" class="mr-1"></i>
                  <span x-text="sensorStatus ? 'Active' : 'Inactive'"></span>
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">Last updated: <span id="tempSensorLastUpdate">--:--</span></span>
              </div>
              <button @click="viewSensorDetails('TEMP-001')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                <i class="fas fa-chart-line"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Humidity Sensor Card -->
        <div class="glass-card p-6 relative overflow-hidden group" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 opacity-20 group-hover:opacity-40 transition-opacity"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-gray-600 dark:text-gray-300 flex items-center gap-2">
                  <i class="fas fa-tint text-green-500"></i>
                  HUMIDITY SENSOR
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ID: HUM-001</p>
              </div>
              <div class="flex gap-2">
                <button @click="toggleSensorStatus('HUM-001')" class="p-2 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 transition-transform hover:scale-105">
                  <i class="fas fa-power-off"></i>
                </button>
                <button @click="openModal('editSensor', {id: 'HUM-001', name: 'Humidity Sensor', type: 'humidity'})" class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-transform hover:scale-105">
                  <i class="fas fa-cog"></i>
                </button>
              </div>
            </div>
            <div class="flex items-end gap-2 mt-3">
              <span id="humSensorValue" class="text-6xl font-extrabold text-gray-800 dark:text-white">--</span>
              <span class="text-3xl text-gray-500 dark:text-gray-400 mb-1">%</span>
            </div>
            <div class="mt-6">
              <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mb-1">
                <span>0%</span>
                <span>100%</span>
              </div>
              <div class="progress-bar h-3 rounded-full overflow-hidden">
                <div id="humSensorProgress" class="progress-value h-full rounded-full bg-gradient-to-r from-green-400 to-green-600 transition-all duration-500" style="width: 0%"></div>
              </div>
            </div>
            <div class="mt-4 flex justify-between items-center">
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 animate-pulse-slow">
                  <i class="fas fa-check-circle mr-1"></i> Active
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">Last updated: <span id="humSensorLastUpdate">--:--</span></span>
              </div>
              <button @click="viewSensorDetails('HUM-001')" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                <i class="fas fa-chart-line"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Light Sensor Card -->
        <div class="glass-card p-6 relative overflow-hidden group" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 opacity-20 group-hover:opacity-40 transition-opacity"></div>
          <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
              <div>
                <p class="text-gray-600 dark:text-gray-300 flex items-center gap-2">
                  <i class="fas fa-sun text-amber-500"></i>
                  LIGHT SENSOR
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ID: LIGHT-001</p>
              </div>
              <div class="flex gap-2">
                <button @click="toggleSensorStatus('LIGHT-001')" class="p-2 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 transition-transform hover:scale-105">
                  <i class="fas fa-power-off"></i>
                </button>
                <button @click="openModal('editSensor', {id: 'LIGHT-001', name: 'Light Sensor', type: 'light'})" class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-transform hover:scale-105">
                  <i class="fas fa-cog"></i>
                </button>
              </div>
            </div>
            <div class="flex items-end gap-2 mt-3">
              <span id="lightSensorValue" class="text-6xl font-extrabold text-gray-800 dark:text-white">--</span>
              <span class="text-3xl text-gray-500 dark:text-gray-400 mb-1">lux</span>
            </div>
            <div class="mt-6">
              <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mb-1">
                <span>0 lux</span>
                <span>1000 lux</span>
              </div>
              <div class="progress-bar h-3 rounded-full overflow-hidden">
                <div id="lightSensorProgress" class="progress-value h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-600 transition-all duration-500" style="width: 0%"></div>
              </div>
            </div>
            <div class="mt-4 flex justify-between items-center">
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 animate-pulse-slow">
                  <i class="fas fa-check-circle mr-1"></i> Active
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">Last updated: <span id="lightSensorLastUpdate">--:--</span></span>
              </div>
              <button @click="viewSensorDetails('LIGHT-001')" class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300">
                <i class="fas fa-chart-line"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Add Sensor Card -->
        <div class="glass-card p-6 relative overflow-hidden group border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 cursor-pointer" @click="openModal('addSensor')" data-tilt data-tilt-max="10" data-tilt-speed="400">
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
              <i class="fas fa-plus-circle text-6xl text-gray-400 dark:text-gray-600 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors duration-300"></i>
              <p class="mt-4 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">Add New Sensor</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Sensor Details Section -->
      <div class="glass-card p-6 mb-8" x-show="selectedSensor">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
            <i :class="getSensorIcon(selectedSensor.type)" class="text-blue-500"></i>
            <span x-text="selectedSensor.name"></span>
          </h2>
          <button @click="selectedSensor = null" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Sensor Information</h3>
            <div class="space-y-4">
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Sensor ID:</span>
                <span class="font-medium" x-text="selectedSensor.id"></span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Type:</span>
                <span class="font-medium capitalize" x-text="selectedSensor.type"></span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                  <i class="fas fa-check-circle mr-1"></i> Active
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Last Updated:</span>
                <span class="font-medium" x-text="selectedSensor.lastUpdate || '--:--'"></span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Current Value:</span>
                <span class="font-medium" x-text="selectedSensor.value + getSensorUnit(selectedSensor.type)"></span>
              </div>
            </div>
          </div>
          <div>
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
              <button @click="toggleSensorStatus(selectedSensor.id)" class="p-4 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-transform hover:scale-105 flex flex-col items-center justify-center gap-2">
                <i class="fas fa-power-off text-2xl"></i>
                <span>Toggle Power</span>
              </button>
              <button @click="calibrateSensor(selectedSensor.id)" class="p-4 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 transition-transform hover:scale-105 flex flex-col items-center justify-center gap-2">
                <i class="fas fa-balance-scale text-2xl"></i>
                <span>Calibrate</span>
              </button>
              <button @click="openModal('editSensor', selectedSensor)" class="p-4 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 hover:bg-purple-200 dark:hover:bg-purple-800 transition-transform hover:scale-105 flex flex-col items-center justify-center gap-2">
                <i class="fas fa-cog text-2xl"></i>
                <span>Configure</span>
              </button>
              <button @click="viewSensorHistory(selectedSensor.id)" class="p-4 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-800 transition-transform hover:scale-105 flex flex-col items-center justify-center gap-2">
                <i class="fas fa-history text-2xl"></i>
                <span>History</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Sensor Alerts -->
      <div class="glass-card p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2 mb-6">
          <i class="fas fa-bell text-red-500"></i>
          Sensor Alerts
        </h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sensor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alert Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Value</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody id="sensorAlerts" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr class="animate-pulse">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Loading...</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Footer -->
      <footer class="mt-12 text-center text-sm text-gray-500 dark:text-gray-400">
        <p>Smart IoT Monitoring System © <?= date('Y') ?> - All Rights Reserved</p>
        <p class="mt-1">Last Updated: <span id="footerUpdate"><?= date('d/m/Y H:i') ?></span></p>
      </footer>
    </div>
  </div>

  <!-- Add/Edit Sensor Modal -->
  <div x-show="modalOpen" x-transition class="modal fixed inset-0 bg-black/50 flex items-center justify-center z-50"
       :class="modalOpen ? '' : 'modal-hidden'">
    <div class="glass-card p-8 max-w-md w-full">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white" x-text="modalType === 'addSensor' ? 'Add New Sensor' : 'Edit Sensor'"></h2>
        <button @click="modalOpen = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sensor Name</label>
          <input type="text" x-model="modalData.name" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sensor Type</label>
          <select x-model="modalData.type" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="temperature">Temperature</option>
            <option value="humidity">Humidity</option>
            <option value="light">Light</option>
            <option value="pressure">Pressure</option>
            <option value="motion">Motion</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sensor ID</label>
          <input type="text" x-model="modalData.id" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Update Interval (seconds)</label>
          <input type="number" x-model="modalData.updateInterval" min="1" max="3600" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alert Thresholds</label>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Min</label>
              <input type="number" x-model="modalData.thresholdMin" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Max</label>
              <input type="number" x-model="modalData.thresholdMax" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
          </div>
        </div>
      </div>
      <div class="mt-6 flex justify-end gap-3">
        <button @click="modalOpen = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
          Cancel
        </button>
        <button @click="saveSensor()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
          Save
        </button>
      </div>
    </div>
  </div>

  <script>
    function sensorsData() {
      return {
        sidebarOpen: true,
        darkMode: false,
        modalOpen: false,
        modalType: 'addSensor',
        modalData: {
          name: '',
          type: 'temperature',
          id: '',
          updateInterval: 5,
          thresholdMin: 0,
          thresholdMax: 100
        },
        selectedSensor: null,

        init() {
          const savedTheme = localStorage.getItem('theme');
          if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            this.darkMode = true;
          }
          // Initialize Vanilla Tilt
          VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
            max: 10,
            speed: 400,
            glare: true,
            "max-glare": 0.2,
          });
          this.fetchSensorData();
        },

        toggleTheme() {
          this.darkMode = !this.darkMode;
          document.documentElement.classList.toggle('dark');
          localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        },

        openModal(type, data = {}) {
          this.modalType = type;
          if (type === 'editSensor') {
            this.modalData = { ...data };
          } else {
            this.modalData = {
              name: '',
              type: 'temperature',
              id: '',
              updateInterval: 5,
              thresholdMin: 0,
              thresholdMax: 100
            };
          }
          this.modalOpen = true;
        },

        saveSensor() {
          // Here you would save the sensor data to your backend
          console.log('Saving sensor:', this.modalData);
          this.showNotification('Sensor saved successfully', 'success');
          this.modalOpen = false;
        },

        toggleSensorStatus(sensorId) {
          // Here you would toggle the sensor status in your backend
          console.log('Toggling sensor status:', sensorId);
          this.showNotification('Sensor status updated', 'success');
        },

        calibrateSensor(sensorId) {
          // Here you would calibrate the sensor in your backend
          console.log('Calibrating sensor:', sensorId);
          this.showNotification('Sensor calibration started', 'info');
        },

        viewSensorDetails(sensorId) {
          // Here you would fetch the sensor details from your backend
          console.log('Viewing sensor details:', sensorId);
          this.selectedSensor = {
            id: sensorId,
            name: this.getSensorName(sensorId),
            type: this.getSensorType(sensorId),
            value: this.getRandomValue(sensorId),
            lastUpdate: new Date().toLocaleTimeString()
          };
        },

        viewSensorHistory(sensorId) {
          // Here you would navigate to the history page with the sensor ID
          console.log('Viewing sensor history:', sensorId);
          window.location.href = `history.php?sensor=${sensorId}`;
        },

        getSensorName(sensorId) {
          const names = {
            'TEMP-001': 'Temperature Sensor',
            'HUM-001': 'Humidity Sensor',
            'LIGHT-001': 'Light Sensor'
          };
          return names[sensorId] || 'Unknown Sensor';
        },

        getSensorType(sensorId) {
          const types = {
            'TEMP-001': 'temperature',
            'HUM-001': 'humidity',
            'LIGHT-001': 'light'
          };
          return types[sensorId] || 'unknown';
        },

        getSensorIcon(type) {
          const icons = {
            'temperature': 'fas fa-thermometer-half text-blue-500',
            'humidity': 'fas fa-tint text-green-500',
            'light': 'fas fa-sun text-amber-500',
            'pressure': 'fas fa-compress-alt text-purple-500',
            'motion': 'fas fa-walking text-red-500'
          };
          return icons[type] || 'fas fa-question-circle text-gray-500';
        },

        getSensorUnit(type) {
          const units = {
            'temperature': '°C',
            'humidity': '%',
            'light': ' lux',
            'pressure': ' hPa',
            'motion': ''
          };
          return units[type] || '';
        },

        getRandomValue(sensorId) {
          const ranges = {
            'TEMP-001': [15, 30],
            'HUM-001': [40, 80],
            'LIGHT-001': [100, 800]
          };
          const range = ranges[sensorId] || [0, 100];
          return (Math.random() * (range[1] - range[0]) + range[0]).toFixed(1);
        },

        fetchSensorData() {
          // Here you would fetch the sensor data from your backend
          console.log('Fetching sensor data');
          // For demo purposes, we'll update the UI with random values
          this.updateSensorUI();
        },

        updateSensorUI() {
          // Update temperature sensor
          const tempValue = this.getRandomValue('TEMP-001');
          document.getElementById('tempSensorValue').textContent = tempValue;
          document.getElementById('tempSensorProgress').style.width = `${(tempValue - 10) / 40 * 100}%`;
          document.getElementById('tempSensorLastUpdate').textContent = new Date().toLocaleTimeString();

          // Update humidity sensor
          const humValue = this.getRandomValue('HUM-001');
          document.getElementById('humSensorValue').textContent = humValue;
          document.getElementById('humSensorProgress').style.width = `${humValue}%`;
          document.getElementById('humSensorLastUpdate').textContent = new Date().toLocaleTimeString();

          // Update light sensor
          const lightValue = this.getRandomValue('LIGHT-001');
          document.getElementById('lightSensorValue').textContent = lightValue;
          document.getElementById('lightSensorProgress').style.width = `${lightValue / 10}%`;
          document.getElementById('lightSensorLastUpdate').textContent = new Date().toLocaleTimeString();

          // Update footer
          document.getElementById('footerUpdate').textContent = new Date().toLocaleString();
        },

        showNotification(message, type = 'success') {
          // Here you would show a notification to the user
          console.log(`${type}: ${message}`);
        }
      }
    }
  </script>
</body>
</html> 