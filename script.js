// script.js - Smart IoT Dashboard Controller

// Configuration
const config = {
  updateInterval: 5000, // 5 seconds
  apiEndpoints: {
    current: 'data.php',
    stats: 'api/get_stats.php',
    history: 'chart.php',
    control: 'api/control.php'
  },
  tempThresholds: {
    high: 35,
    normal: 25,
    min: 10,
    max: 50
  }
};

// DOM Elements
const elements = {
  currentTemp: document.getElementById('currentTemp'),
  currentTempDisplay: document.getElementById('currentTempDisplay'),
  tempProgress: document.getElementById('tempProgress'),
  tempIndicator: document.getElementById('tempIndicator'),
  tempMessage: document.getElementById('tempMessage'),
  tempMax: document.getElementById('tempMax'),
  tempMin: document.getElementById('tempMin'),
  avg24hTemp: document.getElementById('avg24hTemp'),
  min24hTemp: document.getElementById('min24hTemp'),
  max24hTemp: document.getElementById('max24hTemp'),
  trendIndicator: document.getElementById('trendIndicator'),
  trendText: document.getElementById('trendText'),
  trendIcon: document.getElementById('trendIcon'),
  trendArrow: document.getElementById('trendArrow'),
  trendPercent: document.getElementById('trendPercent'),
  trendVariation: document.getElementById('trendVariation'),
  lastUpdate: document.getElementById('lastUpdate'),
  lastUpdateAgo: document.getElementById('lastUpdateAgo'),
  tempChart: null,
  chartCanvas: document.getElementById('tempChart'),
  chartLoading: document.getElementById('chartLoading'),
  recentData: document.getElementById('recentData'),
  dataCount: document.getElementById('dataCount'),
  ledStatus: document.querySelector('[x-text="ledOn ? \'ACTIVE\' : \'INACTIVE\'"]'),
  ledButton: document.querySelector('button[onclick="toggleLed()"]'),
  minTempPercent: document.getElementById('minTempPercent'),
  maxTempPercent: document.getElementById('maxTempPercent'),
  tempChange: document.getElementById('tempChange'),
  tempChangePercent: document.getElementById('tempChangePercent'),
  tempChangeIcon: document.getElementById('tempChangeIcon'),
  tempTrend: document.getElementById('tempTrend'),
  tempTrendPercent: document.getElementById('tempTrendPercent'),
  tempTrendIcon: document.getElementById('tempTrendIcon'),
  tempStatus: document.getElementById('tempStatus'),
  tempStatusIcon: document.getElementById('tempStatusIcon'),
  tempStatusText: document.getElementById('tempStatusText')
};

let previousTemp = null;
let previousPeriodTemp = null;

// Initialize the dashboard
function initDashboard() {
  if (elements.tempMax) elements.tempMax.textContent = `${config.tempThresholds.max}°C`;
  if (elements.tempMin) elements.tempMin.textContent = `${config.tempThresholds.min}°C`;
  initChart();
  fetchAllData();
  setInterval(fetchAllData, config.updateInterval);
}

// Initialize the temperature chart
function initChart() {
  if (!elements.chartCanvas) return;

  elements.tempChart = new Chart(elements.chartCanvas, {
    type: 'line',
    data: {
      labels: [],
      datasets: [{
        label: 'Temperature (°C)',
        data: [],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        borderWidth: 2,
        tension: 0.3,
        fill: true,
        pointRadius: 4,
        pointBackgroundColor: '#3b82f6',
        pointHoverRadius: 8,
        pointHoverBorderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        intersect: false,
        mode: 'index'
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          mode: 'index',
          intersect: false,
          position: 'custom',
          backgroundColor: 'rgba(15, 23, 42, 0.9)',
          bodyColor: '#fff',
          titleColor: '#fff',
          borderColor: 'rgba(255, 255, 255, 0.1)',
          borderWidth: 1,
          padding: 12,
          cornerRadius: 8,
          usePointStyle: true,
          callbacks: {
            label: function(context) {
              return `${context.dataset.label}: ${context.parsed.y}°C`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: false,
          min: config.tempThresholds.min,
          max: config.tempThresholds.max,
          title: {
            display: true,
            text: 'Temperature (°C)',
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#666'
          },
          grid: {
            color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
          },
          ticks: {
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#666'
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#666'
          }
        }
      },
      animation: {
        duration: 1000,
        easing: 'easeOutQuart'
      }
    }
  });

  document.documentElement.addEventListener('classChanged', () => {
    updateChartColors();
  });
}

// Update chart colors based on theme
function updateChartColors() {
  if (!elements.tempChart) return;
  const isDark = document.documentElement.classList.contains('dark');
  elements.tempChart.options.scales.y.grid.color = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
  elements.tempChart.options.scales.y.ticks.color = isDark ? '#fff' : '#666';
  elements.tempChart.options.scales.y.title.color = isDark ? '#fff' : '#666';
  elements.tempChart.options.scales.x.ticks.color = isDark ? '#fff' : '#666';
  elements.tempChart.update();
}

// Fetch all required data
async function fetchAllData() {
  try {
    if (elements.chartLoading) elements.chartLoading.classList.remove('hidden');
    const [currentData, historyData] = await Promise.all([
      fetchData(config.apiEndpoints.current),
      fetchData(config.apiEndpoints.history)
    ]);
    updateCurrentTemperature(currentData);
    updateChartData(historyData);
    updateRecentDataTable(historyData);
    // Simulate 24h stats (since get_stats.php is not provided)
    update24hStats({
      avg_temp: historyData.values ? (historyData.values.reduce((a, b) => a + b, 0) / historyData.values.length).toFixed(1) : null,
      min_temp: historyData.values ? Math.min(...historyData.values) : null,
      max_temp: historyData.values ? Math.max(...historyData.values) : null,
      trend: historyData.values && historyData.values.length > 1 ? 
        (historyData.values[historyData.values.length - 1] > historyData.values[0] ? 'up' : 
         historyData.values[historyData.values.length - 1] < historyData.values[0] ? 'down' : 'stable') : 'stable',
      trend_percent: historyData.values && historyData.values.length > 1 ? 
        Math.abs(((historyData.values[historyData.values.length - 1] - historyData.values[0]) / historyData.values[0]) * 100).toFixed(1) : 0
    });
  } catch (error) {
    console.error('Error fetching data:', error);
    showError('Failed to fetch data');
  } finally {
    if (elements.chartLoading) elements.chartLoading.classList.add('hidden');
  }
}

// Generic data fetcher
async function fetchData(endpoint) {
  const response = await fetch(endpoint);
  if (!response.ok) throw new Error('Network response was not ok');
  return await response.json();
}

// Update current temperature display
function updateCurrentTemperature(data) {
  if (!data || !data.temperature) return;
  const temp = parseFloat(data.temperature);
  
  // Update current temperature display in the icon section
  if (elements.currentTempDisplay) {
    elements.currentTempDisplay.textContent = `${temp.toFixed(1)}°C`;
  }

  // Update min temperature and its percentage change
  if (elements.min24hTemp) {
    elements.min24hTemp.textContent = data.min_temp ? data.min_temp.toFixed(1) : '--';
  }

  // Update max temperature and its percentage change
  if (elements.max24hTemp) {
    elements.max24hTemp.textContent = data.max_temp ? data.max_temp.toFixed(1) : '--';
  }

  // Update percentages
  if (elements.minTempPercent) {
    const minPercent = data.min_temp && data.prev_min_temp ? 
      Math.round((data.min_temp - data.prev_min_temp) / data.prev_min_temp * 100) : 0;
    elements.minTempPercent.textContent = minPercent;
  }

  if (elements.maxTempPercent) {
    const maxPercent = data.max_temp && data.prev_max_temp ? 
      Math.round((data.max_temp - data.prev_max_temp) / data.prev_max_temp * 100) : 0;
    elements.maxTempPercent.textContent = maxPercent;
  }

  if (elements.trendPercent) {
    const trendPercent = data.trend_percent ? Math.round(data.trend_percent) : 0;
    elements.trendPercent.textContent = trendPercent;
  }

  // Update current temperature with animation
  if (elements.currentTemp) {
    elements.currentTemp.style.animation = 'none';
    void elements.currentTemp.offsetWidth;
    elements.currentTemp.style.animation = 'tempChange 0.5s ease';
    elements.currentTemp.textContent = temp.toFixed(2);
    
    // Update temperature change
    if (previousTemp !== null) {
      const tempDiff = temp - previousTemp;
      const tempPercent = ((tempDiff / previousTemp) * 100).toFixed(1);
      
      if (elements.tempChange) {
        elements.tempChange.textContent = Math.abs(tempDiff).toFixed(1);
      }
      if (elements.tempChangePercent) {
        elements.tempChangePercent.textContent = `${Math.abs(tempPercent)}%`;
        elements.tempChangePercent.parentElement.className = 
          `text-sm ${tempDiff < 0 ? 'text-green-500' : 'text-red-500'} mt-1`;
      }
      if (elements.tempChangeIcon) {
        elements.tempChangeIcon.className = `fas fa-arrow-${tempDiff < 0 ? 'down' : 'up'}`;
      }
    }
    
    // Update trend vs previous period
    if (previousPeriodTemp !== null) {
      const trendDiff = temp - previousPeriodTemp;
      const trendPercent = ((trendDiff / previousPeriodTemp) * 100).toFixed(1);
      
      if (elements.tempTrend) {
        elements.tempTrend.textContent = Math.abs(trendDiff).toFixed(1);
      }
      if (elements.tempTrendPercent) {
        elements.tempTrendPercent.textContent = `${Math.abs(trendPercent)}%`;
        elements.tempTrendPercent.parentElement.className = 
          `text-sm ${trendDiff < 0 ? 'text-green-500' : 'text-red-500'} mt-1`;
      }
      if (elements.tempTrendIcon) {
        elements.tempTrendIcon.className = `fas fa-arrow-${trendDiff < 0 ? 'down' : 'up'}`;
      }
    }

    // Update status message
    if (elements.tempStatus && elements.tempStatusText) {
      let statusClass = 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400';
      let statusIcon = 'fa-info-circle';
      let statusText = 'Temperature stable';

      if (temp > config.tempThresholds.high) {
        statusClass = 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400';
        statusIcon = 'fa-exclamation-triangle';
        statusText = 'High temperature alert!';
      } else if (temp < config.tempThresholds.normal) {
        statusClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400';
        statusIcon = 'fa-thermometer-empty';
        statusText = 'Low temperature';
      }

      elements.tempStatus.className = `mt-4 text-sm flex items-center justify-center gap-2 px-4 py-2 rounded-lg ${statusClass}`;
      elements.tempStatusIcon.className = `fas ${statusIcon}`;
      elements.tempStatusText.textContent = statusText;
    }
  }

  // Store current temperature for next comparison
  previousTemp = temp;
  
  // Update previous period temperature every 5 minutes
  if (!previousPeriodTemp || (timestamp - new Date(previousPeriodTemp.timestamp)) > 300000) {
    previousPeriodTemp = {
      temperature: temp,
      timestamp: timestamp
    };
  }

  if (elements.tempProgress) {
    const percentage = Math.min(100, Math.max(0, 
      ((temp - config.tempThresholds.min) / (config.tempThresholds.max - config.tempThresholds.min)) * 100
    ));
    elements.tempProgress.style.width = `${percentage}%`;
    elements.tempProgress.className = 'progress-value h-full rounded-full bg-gradient-to-r ' +
      (temp > config.tempThresholds.high ? 'from-red-400 to-red-600' :
       temp > config.tempThresholds.normal ? 'from-green-400 to-green-600' :
       'from-blue-400 to-blue-600');
  }

  if (elements.tempIndicator) {
    elements.tempIndicator.innerHTML = `
      <div class="w-16 h-16 rounded-full flex items-center justify-center ${
        temp > config.tempThresholds.high ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' :
        temp > config.tempThresholds.normal ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' :
        'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
      } border-4 border-white dark:border-gray-800 shadow-lg transition-all">
        <i class="fas fa-${
          temp > config.tempThresholds.high ? 'temperature-high' :
          temp > config.tempThresholds.normal ? 'temperature-low' :
          'snowflake'
        } text-2xl"></i>
      </div>`;
  }

  if (elements.tempMessage) {
    elements.tempMessage.innerHTML = `
      <i class="fas fa-${
        temp > config.tempThresholds.high ? 'exclamation-triangle text-red-500' :
        temp > config.tempThresholds.normal ? 'check-circle text-green-500' :
        'info-circle text-blue-500'
      }"></i>
      <span>${
        temp > config.tempThresholds.high ? 'High temperature!' :
        temp > config.tempThresholds.normal ? 'Normal temperature' :
        'Low temperature'
      }</span>`;
  }

  if (elements.lastUpdate) {
    elements.lastUpdate.textContent = timestamp.toLocaleTimeString();
  }

  if (elements.lastUpdateAgo) {
    const now = new Date();
    const diff = Math.floor((now - timestamp) / 1000);
    elements.lastUpdateAgo.textContent = 
      diff < 60 ? 'just now' :
      diff < 3600 ? `about ${Math.floor(diff/60)} min ago` :
      diff < 86400 ? `about ${Math.floor(diff/3600)} hr ago` :
      `about ${Math.floor(diff/86400)} days ago`;
  }
}

// Update 24h statistics
function update24hStats(data) {
  if (!data) return;
  
  // Update average temperature
  if (elements.avg24hTemp) {
    elements.avg24hTemp.textContent = data.avg_temp ? data.avg_temp.toFixed(1) : '--';
  }

  // Update min temperature and its percentage change
  if (elements.min24hTemp) {
    elements.min24hTemp.textContent = data.min_temp ? data.min_temp.toFixed(2) : '--';
  }
  if (elements.minTempPercent) {
    const minPercent = data.min_temp && data.prev_min_temp ? 
      Math.round((data.min_temp - data.prev_min_temp) / data.prev_min_temp * 100) : 0;
    elements.minTempPercent.textContent = minPercent;
  }

  // Update max temperature and its percentage change
  if (elements.max24hTemp) {
    elements.max24hTemp.textContent = data.max_temp ? data.max_temp.toFixed(2) : '--';
  }
  if (elements.maxTempPercent) {
    const maxPercent = data.max_temp && data.prev_max_temp ? 
      Math.round((data.max_temp - data.prev_max_temp) / data.prev_max_temp * 100) : 0;
    elements.maxTempPercent.textContent = maxPercent;
  }

  // Update trend variation
  if (elements.trendVariation && elements.trendArrow && elements.trendPercent) {
    const trendPercent = data.trend_percent ? Math.round(data.trend_percent) : 0;
    const trend = trendPercent > 0 ? 'up' : trendPercent < 0 ? 'down' : 'stable';
    
    elements.trendVariation.className = `mt-4 text-sm flex items-center justify-center gap-2 px-4 py-2 rounded-lg ${
      trend === 'up' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' :
      trend === 'down' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' :
      'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400'
    }`;
    
    elements.trendArrow.className = `fas fa-arrow-${trend === 'up' ? 'up' : trend === 'down' ? 'down' : 'right'}`;
    elements.trendPercent.textContent = trendPercent;
  }
}

// Update chart data
function updateChartData(data) {
  if (!elements.tempChart || !data) return;
  elements.tempChart.data.labels = data.labels ? data.labels.map(label => formatTime(label)) : [];
  elements.tempChart.data.datasets[0].data = data.values || [];
  elements.tempChart.data.datasets[0].pointBackgroundColor = data.values ? data.values.map(val => 
    val > config.tempThresholds.high ? '#ef4444' : 
    val > config.tempThresholds.normal ? '#f59e0b' : '#3b82f6'
  ) : [];
  elements.tempChart.update();
  if (elements.dataCount && data.values) {
    elements.dataCount.textContent = data.values.length;
  }
}

// Update recent data table
function updateRecentDataTable(data) {
  if (!elements.recentData || !data || !data.values || !data.labels) return;
  let html = '';
  const now = new Date();
  const recentData = data.values.slice(-5).reverse();
  const recentLabels = data.labels.slice(-5).reverse();

  recentData.forEach((value, index) => {
    const timestamp = new Date(recentLabels[index]);
    const timeDiff = Math.floor((now - timestamp) / 1000);
    let timeText = 
      timeDiff < 60 ? 'Just now' :
      timeDiff < 3600 ? `About ${Math.floor(timeDiff/60)} min ago` :
      timeDiff < 86400 ? `About ${Math.floor(timeDiff/3600)} hr ago` :
      `About ${Math.floor(timeDiff/86400)} days ago`;

    html += `
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">${formatTime(timestamp)}</td>
        <td class="px-6 py-4 whitespace-nowrap">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
            value > config.tempThresholds.high ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' :
            value > config.tempThresholds.normal ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400' :
            'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400'
          }">
            ${value}°C
          </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${timeText}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
          <button @click="openModal('data', { timestamp: '${formatTime(timestamp)}', value: ${value}, status: '${timeText}' })" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
            <i class="fas fa-chart-line"></i>
          </button>
          <button class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
    `;
  });

  elements.recentData.innerHTML = html;
}

// Toggle LED state
async function toggleLed() {
  try {
    const originalContent = elements.ledButton.innerHTML;
    elements.ledButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    elements.ledButton.disabled = true;
    const response = await fetch(config.apiEndpoints.control, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'toggle' })
    });
    const result = await response.json();
    if (result.success) {
      showNotification(`LED ${result.ledState ? 'turned on' : 'turned off'} successfully`, 'success');
    }
  } catch (error) {
    console.error('Error toggling LED:', error);
    showError('Failed to toggle LED');
  } finally {
    elements.ledButton.innerHTML = elements.ledStatus.textContent === 'ACTIVE' ? 
      '<i class="fas fa-power-off"></i> Turn Off' : 
      '<i class="fas fa-toggle-off"></i> Turn On';
    elements.ledButton.disabled = false;
  }
}

// Change chart time range
function updateChartRange(range) {
  if (elements.chartLoading) elements.chartLoading.classList.remove('hidden');
  fetch(`${config.apiEndpoints.history}?range=${range}`)
    .then(response => response.json())
    .then(data => {
      updateChartData(data);
      document.querySelectorAll('[onclick^="updateChart"], [x-on\\:click^="updateChart"]').forEach(btn => {
        const btnRange = btn.getAttribute('x-on:click')?.match(/'([^']+)'/)?.[1] || 
                        btn.getAttribute('onclick')?.match(/'([^']+)'/)?.[1];
        if (btnRange === range) {
          btn.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
          btn.classList.add('bg-blue-100', 'dark:bg-blue-900/30', 'text-blue-700', 'dark:text-blue-400');
        } else {
          btn.classList.remove('bg-blue-100', 'dark:bg-blue-900/30', 'text-blue-700', 'dark:text-blue-400');
          btn.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        }
      });
    })
    .catch(error => {
      console.error('Error updating chart range:', error);
      showError('Failed to load historical data');
    })
    .finally(() => {
      if (elements.chartLoading) elements.chartLoading.classList.add('hidden');
    });
}

// Format time for display
function formatTime(date) {
  if (!date) return 'N/A';
  if (!(date instanceof Date)) date = new Date(date);
  return date.toLocaleString([], { 
    month: 'short', 
    day: 'numeric', 
    hour: '2-digit', 
    minute: '2-digit',
    hour12: false 
  });
}

// Show error message
function showError(message) {
  const errorElement = document.createElement('div');
  errorElement.className = 'notification fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2';
  errorElement.innerHTML = `
    <i class="fas fa-exclamation-triangle"></i>
    <span>${message}</span>
  `;
  document.body.appendChild(errorElement);
  setTimeout(() => {
    errorElement.classList.add('animate-fade-out');
    setTimeout(() => errorElement.remove(), 300);
  }, 5000);
}

// Show success notification
function showNotification(message, type = 'success') {
  const notification = document.createElement('div');
  notification.className = `notification fixed bottom-4 right-4 ${
    type === 'success' ? 'bg-green-500' : 'bg-blue-500'
  } text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2`;
  notification.innerHTML = `
    <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
    <span>${message}</span>
  `;
  document.body.appendChild(notification);
  setTimeout(() => {
    notification.classList.add('animate-fade-out');
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

// Export data (placeholder)
function exportData(format) {
  showNotification(`Exporting as ${format} is under development`, 'info');
}

// Fonction pour contrôler la LED via l'API Flask
function toggleLED(state) {
    fetch(`http://localhost:5000/api/led/${state}`)
        .then(response => response.json())
        .then(data => {
            console.log('LED state:', data.led_state);
            updateLEDStatus(data.led_state);
        })
        .catch(error => {
            console.error('Error toggling LED:', error);
        });
}

// Fonction pour mettre à jour l'interface utilisateur en fonction de l'état de la LED
function updateLEDStatus(isOn) {
    const ledButton = document.getElementById('led-button');
    if (ledButton) {
        ledButton.classList.toggle('active', isOn);
        ledButton.textContent = isOn ? 'ON' : 'OFF';
    }
}

// Fonction pour obtenir l'état actuel de la LED
function getLEDState() {
    fetch('http://localhost:5000/api/led/state')
        .then(response => response.json())
        .then(data => {
            updateLEDStatus(data.led_state);
        })
        .catch(error => {
            console.error('Error getting LED state:', error);
        });
}

// Appeler cette fonction au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    getLEDState();
});

// Start the dashboard
document.addEventListener('DOMContentLoaded', initDashboard);