# IoT Dashboard for Raspberry Pi

A real-time IoT dashboard that displays temperature and humidity data from a DHT11 sensor and allows control of an LED via a web interface.

## Features

- Real-time temperature and humidity monitoring
- Interactive LED control
- Responsive design for mobile and desktop
- Real-time data visualization with Chart.js
- WebSocket-based updates for instant data refresh

## Hardware Requirements

- Raspberry Pi (any model)
- DHT11 temperature and humidity sensor
- LED (any color)
- 4.7kΩ pull-up resistor (for DHT11)
- 220Ω resistor (for LED)
- Jumper wires

## Hardware Connections

### DHT11 Sensor
- VCC pin → 3.3V (Pin 1)
- GND pin → GND (Pin 6)
- DATA pin → GPIO4 (Pin 7)
- Add a 4.7kΩ pull-up resistor between VCC and DATA

### LED
- Anode (longer leg) → GPIO17 (Pin 11) through 220Ω resistor
- Cathode (shorter leg) → GND (Pin 9)

## Software Setup

1. Update your Raspberry Pi:
   ```bash
   sudo apt-get update
   sudo apt-get upgrade -y
   ```

2. Install required system packages:
   ```bash
   sudo apt-get install -y python3-pip python3-venv git nginx
   ```

3. Clone this repository:
   ```bash
   git clone https://github.com/yourusername/iot-dashboard.git
   cd iot-dashboard
   ```

4. Create and activate virtual environment:
   ```bash
   python3 -m venv venv
   source venv/bin/activate
   ```

5. Install Python dependencies:
   ```bash
   pip install -r requirements.txt
   ```

6. Set up the systemd service for auto-start:
   ```bash
   sudo cp iot-dashboard.service /etc/systemd/system/
   sudo systemctl daemon-reload
   sudo systemctl enable iot-dashboard
   sudo systemctl start iot-dashboard
   ```

7. Check the service status:
   ```bash
   sudo systemctl status iot-dashboard
   ```

## Accessing the Dashboard

Once the service is running, you can access the dashboard from any device on your local network:

```
http://<raspberry-pi-ip>:5000
```

To find your Raspberry Pi's IP address, run:
```bash
hostname -I
```

## Troubleshooting

### Sensor Issues
- Check wiring connections
- Verify the pull-up resistor is correctly installed
- Test the sensor directly:
  ```bash
  python3 -c "import Adafruit_DHT; print(Adafruit_DHT.read_retry(Adafruit_DHT.DHT11, 4))"
  ```

### LED Issues
- Verify the LED is connected with the correct polarity
- Check the resistor value
- Test the LED directly:
  ```bash
  python3 -c "import RPi.GPIO as GPIO; GPIO.setmode(GPIO.BCM); GPIO.setup(17, GPIO.OUT); GPIO.output(17, GPIO.HIGH)"
  ```

### Service Issues
- Check the service logs:
  ```bash
  sudo journalctl -u iot-dashboard
  ```
- Restart the service:
  ```bash
  sudo systemctl restart iot-dashboard
  ```

## Security Considerations

- Change the default Raspberry Pi password
- Consider setting up HTTPS using Let's Encrypt
- Configure your firewall to only allow necessary ports
- Keep your system and packages updated

## License

MIT 