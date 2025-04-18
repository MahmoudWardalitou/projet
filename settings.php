<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="fr" class="h-full" x-data="settingsData()">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart IoT - Settings</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.2/dist/vanilla-tilt.min.js"></script>
  <link rel="stylesheet" href="style.css">
  <script src="settings.js" defer></script>
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
          <a href="settings.php" class="sidebar-link flex items-center p-3 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
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
            <i class="fas fa-cog text-blue-500"></i>
            Settings
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2">Configure your IoT monitoring system</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
          <button @click="toggleTheme" class="p-2 rounded-lg glass-card hover:bg-gray-200 dark:hover:bg-gray-700 transition-transform hover:scale-105">
            <i class="fas fa-moon dark:fa-sun text-lg"></i>
          </button>
          <button @click="saveSettings()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-transform hover:scale-105">
            <i class="fas fa-save"></i>
            <span>Save Changes</span>
          </button>
        </div>
      </header>

      <!-- Settings Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- General Settings -->
        <div class="glass-card p-6">
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2 mb-6">
            <i class="fas fa-sliders-h text-blue-500"></i>
            General Settings
          </h2>
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Device Name</label>
              <input type="text" x-model="settings.deviceName" placeholder="Enter device name" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Theme</label>
              <div class="flex items-center gap-4">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" x-model="settings.darkMode" @change="toggleTheme()" class="sr-only peer">
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                  <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Dark Mode</span>
                </label>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Language</label>
              <select x-model="settings.language" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="en">English</option>
                <option value="fr">Français</option>
                <option value="es">Español</option>
                <option value="de">Deutsch</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time Zone</label>
              <select x-model="settings.timezone" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="UTC">UTC</option>
                <option value="Europe/Paris">Europe/Paris</option>
                <option value="America/New_York">America/New York</option>
                <option value="Asia/Tokyo">Asia/Tokyo</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Format</label>
              <select x-model="settings.dateFormat" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                <option value="YYYY-MM-DD">YYYY-MM-DD</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Notification Settings -->
        <div class="glass-card p-6">
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2 mb-6">
            <i class="fas fa-bell text-blue-500"></i>
            Notification Settings
          </h2>
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Notifications</label>
              <div class="flex items-center gap-4">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" x-model="settings.emailNotifications" class="sr-only peer">
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                  <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Enabled</span>
                </label>
                <input type="email" x-model="settings.emailAddress" placeholder="Email address" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :disabled="!settings.emailNotifications">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alert Thresholds</label>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Temperature (°C)</label>
                  <div class="flex items-center gap-2">
                    <input type="number" x-model="settings.tempMin" placeholder="Min" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-gray-500 dark:text-gray-400">to</span>
                    <input type="number" x-model="settings.tempMax" placeholder="Max" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                  </div>
                </div>
                <div>
                  <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Humidity (%)</label>
                  <div class="flex items-center gap-2">
                    <input type="number" x-model="settings.humidityMin" placeholder="Min" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-gray-500 dark:text-gray-400">to</span>
                    <input type="number" x-model="settings.humidityMax" placeholder="Max" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                  </div>
                </div>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notification Frequency</label>
              <select x-model="settings.notificationFrequency" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="immediate">Immediate</option>
                <option value="hourly">Hourly Digest</option>
                <option value="daily">Daily Digest</option>
                <option value="weekly">Weekly Digest</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Data Settings -->
        <div class="glass-card p-6">
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2 mb-6">
            <i class="fas fa-database text-blue-500"></i>
            Data Settings
          </h2>
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Retention</label>
              <select x-model="settings.dataRetention" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="7">7 Days</option>
                <option value="30">30 Days</option>
                <option value="90">90 Days</option>
                <option value="365">1 Year</option>
                <option value="0">Forever</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Collection Interval</label>
              <select x-model="settings.collectionInterval" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="1">Every Minute</option>
                <option value="5">Every 5 Minutes</option>
                <option value="15">Every 15 Minutes</option>
                <option value="30">Every 30 Minutes</option>
                <option value="60">Every Hour</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Auto Backup</label>
              <div class="flex items-center gap-4">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" x-model="settings.autoBackup" class="sr-only peer">
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                  <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Enabled</span>
                </label>
                <select x-model="settings.backupFrequency" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :disabled="!settings.autoBackup">
                  <option value="daily">Daily</option>
                  <option value="weekly">Weekly</option>
                  <option value="monthly">Monthly</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Security Settings -->
        <div class="glass-card p-6">
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-2 mb-6">
            <i class="fas fa-shield-alt text-blue-500"></i>
            Security Settings
          </h2>
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Two-Factor Authentication</label>
              <div class="flex items-center gap-4">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" x-model="settings.twoFactorAuth" class="sr-only peer">
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                  <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Enabled</span>
                </label>
                <button @click="setupTwoFactor()" class="px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300" :disabled="!settings.twoFactorAuth">
                  <i class="fas fa-qrcode mr-2"></i>
                  Setup 2FA
                </button>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session Timeout</label>
              <select x-model="settings.sessionTimeout" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="15">15 Minutes</option>
                <option value="30">30 Minutes</option>
                <option value="60">1 Hour</option>
                <option value="120">2 Hours</option>
                <option value="0">Never</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password Policy</label>
              <div class="space-y-2">
                <label class="flex items-center gap-2">
                  <input type="checkbox" x-model="settings.passwordPolicy.uppercase" class="rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500">
                  <span class="text-sm text-gray-700 dark:text-gray-300">Require uppercase letters</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="checkbox" x-model="settings.passwordPolicy.numbers" class="rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500">
                  <span class="text-sm text-gray-700 dark:text-gray-300">Require numbers</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="checkbox" x-model="settings.passwordPolicy.special" class="rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500">
                  <span class="text-sm text-gray-700 dark:text-gray-300">Require special characters</span>
                </label>
                <div class="mt-4">
                  <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Minimum Length</label>
                  <input type="number" x-model="settings.passwordPolicy.minLength" min="8" max="32" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
              </div>
            </div>
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
    function settingsData() {
      return {
        sidebarOpen: true,
        darkMode: false,
        settings: {
          systemName: 'Smart IoT Monitoring System',
          language: 'en',
          timezone: 'UTC',
          dateFormat: 'DD/MM/YYYY',
          emailNotifications: false,
          emailAddress: '',
          tempMin: 18,
          tempMax: 25,
          humidityMin: 40,
          humidityMax: 60,
          notificationFrequency: 'immediate',
          dataRetention: '30',
          collectionInterval: '5',
          autoBackup: true,
          backupFrequency: 'daily',
          twoFactorAuth: false,
          sessionTimeout: '30',
          passwordPolicy: {
            uppercase: true,
            numbers: true,
            special: true,
            minLength: 12
          }
        },

        init() {
          const savedTheme = localStorage.getItem('theme');
          if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            this.darkMode = true;
          }
          
          // Load saved settings
          const savedSettings = localStorage.getItem('settings');
          if (savedSettings) {
            this.settings = JSON.parse(savedSettings);
          }
          
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
        },

        saveSettings() {
          // Save settings to localStorage
          localStorage.setItem('settings', JSON.stringify(this.settings));
          
          // Show success message
          alert('Settings saved successfully!');
        },

        setupTwoFactor() {
          // Here you would implement the 2FA setup process
          console.log('Setting up 2FA...');
        }
      }
    }
  </script>
</body>
</html> 