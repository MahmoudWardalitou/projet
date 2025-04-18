#!/usr/bin/python3
import Adafruit_DHT
import time

# Configuration du capteur
DHT_SENSOR = Adafruit_DHT.DHT11
DHT_PIN = 4

print("Test du capteur DHT11...")
print("Appuyez sur Ctrl+C pour arrêter")

try:
    while True:
        # Lecture du capteur
        humidity, temperature = Adafruit_DHT.read_retry(DHT_SENSOR, DHT_PIN)
        
        if humidity is not None and temperature is not None:
            print(f'Température: {temperature:.1f}°C')
            print(f'Humidité: {humidity:.1f}%')
            print('-' * 30)
        else:
            print('Échec de la lecture du capteur. Nouvelle tentative...')
        
        time.sleep(2)

except KeyboardInterrupt:
    print("\nTest terminé") 