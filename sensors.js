function sensorsData() {
  return {
    sensorStatus: true,
    alertEnabled: false,
    currentTemp: 0,
    currentHum: 0,
    currentLight: 0,
    lastUpdate: '--:--',
    sidebarOpen: true,
    updateInterval: null,

    init() {
      this.startSensorReading();
      this.loadSettings();
    },

    toggleSensorStatus(sensorId) {
      this.sensorStatus = !this.sensorStatus;
      if (!this.sensorStatus) {
        this.currentTemp = 0;
        this.currentHum = 0;
        this.currentLight = 0;
        this.lastUpdate = '--:--';
        if (this.updateInterval) {
          clearInterval(this.updateInterval);
          this.updateInterval = null;
        }
      } else {
        this.startSensorReading();
      }
    },

    toggleAlert(sensorId) {
      this.alertEnabled = !this.alertEnabled;
      if (this.alertEnabled) {
        this.checkTemperatureAlert();
      }
    },

    async readSensorData() {
      try {
        const response = await fetch('get_sensor_data.php');
        const data = await response.json();
        
        if (data.status === 'ok') {
          // Mise à jour des valeurs
          this.currentTemp = data.temperature;
          this.currentHum = data.humidity;
          this.currentLight = data.light;
          this.lastUpdate = new Date().toLocaleTimeString();
          
          // Mise à jour de l'interface
          this.updateUI();
          
          // Vérification des alertes
          if (this.alertEnabled) {
            this.checkTemperatureAlert();
          }
        } else {
          console.error('Erreur de lecture des capteurs:', data.message);
        }
      } catch (error) {
        console.error('Erreur lors de la récupération des données:', error);
      }
    },

    startSensorReading() {
      if (this.sensorStatus) {
        // Lecture immédiate
        this.readSensorData();
        
        // Lecture périodique toutes les 2 secondes
        this.updateInterval = setInterval(() => {
          if (this.sensorStatus) {
            this.readSensorData();
          }
        }, 2000);
      }
    },

    updateUI() {
      // Température
      const tempElement = document.getElementById('tempSensorValue');
      if (tempElement) {
        tempElement.textContent = this.currentTemp !== null ? this.currentTemp.toFixed(1) : '--';
      }
      
      // Humidité
      const humElement = document.getElementById('humSensorValue');
      if (humElement) {
        humElement.textContent = this.currentHum !== null ? this.currentHum.toFixed(1) : '--';
      }
      
      // Dernière mise à jour
      const tempUpdateElement = document.getElementById('tempSensorLastUpdate');
      const humUpdateElement = document.getElementById('humSensorLastUpdate');
      if (tempUpdateElement) tempUpdateElement.textContent = this.lastUpdate;
      if (humUpdateElement) humUpdateElement.textContent = this.lastUpdate;
      
      // Barres de progression
      const tempProgress = ((this.currentTemp - 10) / 40) * 100;
      const humProgress = this.currentHum;
      
      const tempProgressElement = document.getElementById('tempSensorProgress');
      const humProgressElement = document.getElementById('humSensorProgress');
      
      if (tempProgressElement) tempProgressElement.style.width = `${tempProgress}%`;
      if (humProgressElement) humProgressElement.style.width = `${humProgress}%`;
    },

    checkTemperatureAlert() {
      if (this.currentTemp > 35) {
        this.showNotification('Temperature Alert', `High temperature detected: ${this.currentTemp}°C`);
      }
    },

    showNotification(title, message) {
      if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(title, {
          body: message,
          icon: '/favicon.ico'
        });
      } else if ('Notification' in window && Notification.permission !== 'denied') {
        Notification.requestPermission().then(permission => {
          if (permission === 'granted') {
            new Notification(title, {
              body: message,
              icon: '/favicon.ico'
            });
          }
        });
      }
    },

    loadSettings() {
      const settings = JSON.parse(localStorage.getItem('settings') || '{}');
      this.alertEnabled = settings.alertEnabled || false;
      this.sensorStatus = settings.sensorStatus !== false;
    },

    saveSettings() {
      const settings = {
        alertEnabled: this.alertEnabled,
        sensorStatus: this.sensorStatus
      };
      localStorage.setItem('settings', JSON.stringify(settings));
    },

    toggleTheme() {
      document.documentElement.classList.toggle('dark');
      const isDark = document.documentElement.classList.contains('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }
  };
} 