function historyData() {
  return {
    selectedSensor: 'temperature',
    dateRange: '24h',
    startDate: '',
    endDate: '',
    chartType: 'line',
    chart: null,
    sidebarOpen: true,

    init() {
      this.initChart();
      this.loadData();
      this.loadSettings();
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
            borderColor: 'rgb(59, 130, 246)',
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
              mode: 'index',
              intersect: false,
              backgroundColor: 'rgba(255, 255, 255, 0.9)',
              titleColor: '#1f2937',
              bodyColor: '#1f2937',
              borderColor: 'rgba(229, 231, 235, 0.5)',
              borderWidth: 1,
              padding: 10,
              displayColors: false
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

    loadData() {
      // Show loading state
      document.getElementById('chartLoading').classList.remove('hidden');
      
      // Simulate API call with setTimeout
      setTimeout(() => {
        const data = this.generateMockData();
        this.updateChart(data);
        this.updateStats(data);
        document.getElementById('chartLoading').classList.add('hidden');
      }, 1000);
    },

    generateMockData() {
      const data = [];
      const labels = [];
      const now = new Date();
      const hours = this.dateRange === '24h' ? 24 : this.dateRange === '7d' ? 168 : 720;
      
      for (let i = hours; i >= 0; i--) {
        const time = new Date(now - i * 3600000);
        labels.push(time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
        data.push(20 + Math.random() * 20);
      }
      
      return { labels, data };
    },

    updateChart(data) {
      this.chart.data.labels = data.labels;
      this.chart.data.datasets[0].data = data.data;
      this.chart.update();
    },

    updateStats(data) {
      const values = data.data;
      const avg = values.reduce((a, b) => a + b, 0) / values.length;
      const min = Math.min(...values);
      const max = Math.max(...values);
      
      document.getElementById('avgValue').textContent = avg.toFixed(1);
      document.getElementById('minValue').textContent = min.toFixed(1);
      document.getElementById('maxValue').textContent = max.toFixed(1);
      document.getElementById('recordCount').textContent = values.length;
    },

    setDateRange(range) {
      this.dateRange = range;
      if (range === 'custom') {
        this.startDate = '';
        this.endDate = '';
      } else {
        this.loadData();
      }
    },

    updateChartType(type) {
      this.chartType = type;
      this.chart.config.type = type;
      this.chart.update();
    },

    downloadReport() {
      const data = this.generateMockData();
      const report = this.generateReport(data);
      const blob = new Blob([report], { type: 'text/plain' });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `sensor_report_${new Date().toISOString().split('T')[0]}.txt`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);
    },

    generateReport(data) {
      const values = data.data;
      const avg = values.reduce((a, b) => a + b, 0) / values.length;
      const min = Math.min(...values);
      const max = Math.max(...values);
      
      return `Sensor Report - ${new Date().toLocaleString()}
Period: ${this.dateRange}
Sensor: ${this.selectedSensor}

Statistics:
- Average: ${avg.toFixed(1)}°C
- Minimum: ${min.toFixed(1)}°C
- Maximum: ${max.toFixed(1)}°C
- Total Records: ${values.length}

Data Points:
${data.labels.map((label, i) => `${label}: ${values[i].toFixed(1)}°C`).join('\n')}`;
    },

    exportData(format) {
      const data = this.generateMockData();
      let content, filename, type;
      
      if (format === 'csv') {
        content = 'Time,Value\n' + data.labels.map((label, i) => `${label},${data.data[i]}`).join('\n');
        filename = `sensor_data_${new Date().toISOString().split('T')[0]}.csv`;
        type = 'text/csv';
      } else {
        // For PDF, we'll just download the CSV for now
        // In a real application, you would use a PDF generation library
        content = 'Time,Value\n' + data.labels.map((label, i) => `${label},${data.data[i]}`).join('\n');
        filename = `sensor_data_${new Date().toISOString().split('T')[0]}.csv`;
        type = 'text/csv';
      }
      
      const blob = new Blob([content], { type });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = filename;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);
    },

    loadSettings() {
      const settings = JSON.parse(localStorage.getItem('settings') || '{}');
      this.selectedSensor = settings.selectedSensor || 'temperature';
      this.dateRange = settings.dateRange || '24h';
    },

    saveSettings() {
      const settings = {
        selectedSensor: this.selectedSensor,
        dateRange: this.dateRange
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