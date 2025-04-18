from flask import Flask, render_template, jsonify
from flask_socketio import SocketIO
import Adafruit_DHT
import RPi.GPIO as GPIO
import time
import json
from threading import Thread
from flask_cors import CORS
import logging

# Configuration des logs
logging.basicConfig(level=logging.DEBUG)
logger = logging.getLogger(__name__)

app = Flask(__name__)
app.config['SECRET_KEY'] = 'your-secret-key'
socketio = SocketIO(app, cors_allowed_origins="*")
CORS(app)  # Activer CORS pour toutes les routes

# GPIO Setup
DHT_SENSOR = Adafruit_DHT.DHT11
DHT_PIN = 4
LED_PIN = 17

GPIO.setmode(GPIO.BCM)
GPIO.setup(LED_PIN, GPIO.OUT)
GPIO.output(LED_PIN, GPIO.LOW)

# Global variables
led_state = False

def read_sensor():
    logger.info("Démarrage de la lecture du capteur")
    while True:
        try:
            logger.debug("Tentative de lecture du capteur DHT11")
            humidity, temperature = Adafruit_DHT.read_retry(DHT_SENSOR, DHT_PIN)
            
            if humidity is not None and temperature is not None:
                data = {
                    'temperature': round(temperature, 1),
                    'humidity': round(humidity, 1),
                    'status': 'ok'
                }
                logger.info(f"Lecture réussie: {data}")
                socketio.emit('sensor_data', data)
            else:
                logger.error("Échec de la lecture du capteur")
                data = {
                    'status': 'error',
                    'message': 'Failed to read sensor'
                }
                socketio.emit('sensor_data', data)
        except Exception as e:
            logger.error(f"Erreur lors de la lecture du capteur: {str(e)}")
            data = {
                'status': 'error',
                'message': str(e)
            }
            socketio.emit('sensor_data', data)
        
        time.sleep(2)

@app.route('/')
def index():
    return render_template('index.html')

# API endpoint pour les données du capteur
@app.route('/api/sensor')
def get_sensor_data():
    try:
        logger.debug("Requête API pour les données du capteur")
        humidity, temperature = Adafruit_DHT.read_retry(DHT_SENSOR, DHT_PIN)
        
        if humidity is not None and temperature is not None:
            data = {
                'temperature': round(temperature, 1),
                'humidity': round(humidity, 1),
                'status': 'ok'
            }
            logger.info(f"Données envoyées: {data}")
            return jsonify(data)
        else:
            logger.error("Échec de la lecture du capteur")
            return jsonify({
                'status': 'error',
                'message': 'Failed to read sensor'
            }), 500
    except Exception as e:
        logger.error(f"Erreur lors de la lecture du capteur: {str(e)}")
        return jsonify({
            'status': 'error',
            'message': str(e)
        }), 500

@app.route('/api/led/<state>')
def toggle_led(state):
    global led_state
    led_state = state.lower() == 'on'
    GPIO.output(LED_PIN, GPIO.HIGH if led_state else GPIO.LOW)
    return jsonify({'status': 'success', 'led_state': led_state})

@app.route('/api/led/state')
def get_led_state():
    return jsonify({'led_state': led_state})

if __name__ == '__main__':
    logger.info("Démarrage du serveur Flask")
    sensor_thread = Thread(target=read_sensor)
    sensor_thread.daemon = True
    sensor_thread.start()
    
    try:
        socketio.run(app, host='0.0.0.0', port=5000, debug=True)
    finally:
        GPIO.cleanup() 