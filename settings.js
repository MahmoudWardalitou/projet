function settingsData() {
  return {
    settings: {
      deviceName: '',
      darkMode: false,
      language: 'en',
      timezone: 'UTC',
      dateFormat: 'DD/MM/YYYY',
      emailNotifications: false,
      emailAddress: '',
      tempMin: 15,
      tempMax: 35,
      humidityMin: 30,
      humidityMax: 70,
      notificationFrequency: 'immediate',
      dataRetention: '30',
      collectionInterval: '5',
      autoBackup: false,
      backupFrequency: 'daily',
      twoFactorAuth: false
    },
    sidebarOpen: true,

    init() {
      this.loadSettings();
      this.applyTheme();
    },

    loadSettings() {
      const savedSettings = JSON.parse(localStorage.getItem('settings') || '{}');
      this.settings = { ...this.settings, ...savedSettings };
    },

    saveSettings() {
      localStorage.setItem('settings', JSON.stringify(this.settings));
      this.showNotification('Settings saved successfully');
    },

    toggleTheme() {
      this.settings.darkMode = !this.settings.darkMode;
      this.applyTheme();
      this.saveSettings();
    },

    applyTheme() {
      if (this.settings.darkMode) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    },

    setupTwoFactor() {
      // In a real application, this would initiate the 2FA setup process
      this.showNotification('Two-factor authentication setup initiated');
    },

    showNotification(message) {
      const notification = document.createElement('div');
      notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg notification';
      notification.textContent = message;
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.remove();
      }, 3000);
    }
  };
} 