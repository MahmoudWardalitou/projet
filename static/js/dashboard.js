function dashboard() {
    return {
        temperature: '--',
        humidity: '--',
        ledState: false,
        temperatureData: [],
        humidityData: [],
        labels: [],
        maxDataPoints: 20,
        
        init() {
            // Initialize charts
            this.initCharts();
            
            // Connect to WebSocket
            this.connectSocket();
            
            // Get initial LED state
            this.getLEDState();
        },
        
        connectSocket() {
            const socket = io();
            
            socket.on('connect', () => {
                console.log('Connected to server');
            });
            
            socket.on('sensor_data', (data) => {
                this.updateSensorData(data);
            });
            
            socket.on('disconnect', () => {
                console.log('Disconnected from server');
            });
        },
        
        updateSensorData(data) {
            // Update current values
            this.temperature = data.temperature;
            this.humidity = data.humidity;
            
            // Update chart data
            const now = new Date();
            const timeLabel = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
            
            this.labels.push(timeLabel);
            this.temperatureData.push(data.temperature);
            this.humidityData.push(data.humidity);
            
            // Keep only the last maxDataPoints
            if (this.labels.length > this.maxDataPoints) {
                this.labels.shift();
                this.temperatureData.shift();
                this.humidityData.shift();
            }
            
            // Update charts
            this.updateCharts();
        },
        
        initCharts() {
            // Temperature Chart
            this.temperatureChart = new Chart(document.getElementById('temperatureChart'), {
                type: 'line',
                data: {
                    labels: this.labels,
                    datasets: [{
                        label: 'Temperature (°C)',
                        data: this.temperatureData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
            
            // Humidity Chart
            this.humidityChart = new Chart(document.getElementById('humidityChart'), {
                type: 'line',
                data: {
                    labels: this.labels,
                    datasets: [{
                        label: 'Humidity (%)',
                        data: this.humidityData,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        },
        
        updateCharts() {
            this.temperatureChart.data.labels = this.labels;
            this.temperatureChart.data.datasets[0].data = this.temperatureData;
            this.temperatureChart.update();
            
            this.humidityChart.data.labels = this.labels;
            this.humidityChart.data.datasets[0].data = this.humidityData;
            this.humidityChart.update();
        },
        
        toggleLED() {
            const newState = !this.ledState;
            fetch(`/api/led/${newState ? 'on' : 'off'}`)
                .then(response => response.json())
                .then(data => {
                    this.ledState = data.led_state;
                })
                .catch(error => {
                    console.error('Error toggling LED:', error);
                });
        },
        
        getLEDState() {
            fetch('/api/led/state')
                .then(response => response.json())
                .then(data => {
                    this.ledState = data.led_state;
                })
                .catch(error => {
                    console.error('Error getting LED state:', error);
                });
        }
    };
} 