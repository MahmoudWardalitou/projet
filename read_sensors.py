#!/usr/bin/python3
import Adafruit_DHT
import RPi.GPIO as GPIO
import json
import sys

# Configuration des broches
DHT_SENSOR = Adafruit_DHT.DHT11  # ou DHT22 selon votre capteur
DHT_PIN = 4  # GPIO4

def read_dht():
    humidity, temperature = Adafruit_DHT.read_retry(DHT_SENSOR, DHT_PIN)
    return temperature, humidity

def main():
    try:
        temperature, humidity = read_dht()
        
        data = {
            "temperature": round(temperature, 1) if temperature is not None else None,
            "humidity": round(humidity, 1) if humidity is not None else None,
            "status": "ok"
        }
        
    except Exception as e:
        data = {
            "status": "error",
            "message": str(e)
        }
    
    print(json.dumps(data))

if __name__ == "__main__":
    main() 