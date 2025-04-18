#!/bin/bash

# Activer l'environnement virtuel
source /home/pi/iot-dashboard/venv/bin/activate

# Démarrer le serveur Flask
cd /home/pi/iot-dashboard
python app.py 