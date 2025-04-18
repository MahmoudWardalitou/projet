# Guide d'Installation du Dashboard IoT sur Raspberry Pi

## Table des matières
1. [Prérequis](#prérequis)
2. [Installation des paquets](#installation-des-paquets)
3. [Configuration d'Apache](#configuration-dapache)
4. [Installation des fichiers](#installation-des-fichiers)
5. [Configuration des capteurs](#configuration-des-capteurs)
6. [Tests et débogage](#tests-et-débogage)
7. [Démarrage automatique](#démarrage-automatique)
8. [Sécurité](#sécurité)

## Prérequis
- Raspberry Pi (tous modèles)
- Capteur DHT11 ou DHT22
- Capteur de lumière (optionnel)
- Câbles de connexion
- Carte SD avec Raspberry Pi OS installé
- Accès Internet

## Installation des paquets

### 1. Mise à jour du système
```bash
sudo apt-get update
sudo apt-get upgrade -y
```

### 2. Installation des paquets nécessaires
```bash
# Apache et PHP
sudo apt-get install -y apache2 php libapache2-mod-php

# Python et pip
sudo apt-get install -y python3-pip

# Git (optionnel)
sudo apt-get install -y git

# Bibliothèques Python pour les capteurs
sudo pip3 install Adafruit_DHT RPi.GPIO
```

## Configuration d'Apache

### 1. Configuration des permissions
```bash
# Ajouter www-data aux groupes nécessaires
sudo usermod -a -G gpio www-data
sudo usermod -a -G spi www-data
sudo usermod -a -G i2c www-data

# Redémarrer Apache
sudo service apache2 restart
```

### 2. Préparation du dossier projet
```bash
# Créer le dossier du projet
cd /var/www/html
sudo mkdir iot-dashboard
sudo chown -R www-data:www-data iot-dashboard
sudo chmod -R 775 iot-dashboard
```

## Installation des fichiers

### 1. Structure des fichiers
Votre projet doit avoir la structure suivante :
```
/var/www/html/iot-dashboard/
├── dashboard.php
├── sensors.php
├── analytics.php
├── history.php
├── settings.php
├── get_sensor_data.php
├── read_sensors.py
├── sensors.js
├── style.css
└── db.php
```

### 2. Configuration des permissions
```bash
# Définir les permissions des fichiers
sudo chown www-data:www-data /var/www/html/iot-dashboard/*
sudo chmod 644 /var/www/html/iot-dashboard/*.php
sudo chmod 644 /var/www/html/iot-dashboard/*.js
sudo chmod 644 /var/www/html/iot-dashboard/*.css
sudo chmod 755 /var/www/html/iot-dashboard/*.py
```

### 3. Configuration sudo pour Python
```bash
# Éditer le fichier sudoers
sudo visudo

# Ajouter cette ligne à la fin
www-data ALL=(ALL) NOPASSWD: /usr/bin/python3 /var/www/html/iot-dashboard/read_sensors.py
```

## Configuration des capteurs

### 1. Branchement du capteur DHT11/DHT22
```
DHT11/DHT22:
- VCC → 3.3V (Pin 1)
- GND → Ground (Pin 6)
- DATA → GPIO4 (Pin 7)
```

### 2. Branchement du capteur de lumière (optionnel)
```
Capteur de lumière:
- VCC → 3.3V (Pin 17)
- GND → Ground (Pin 20)
- DATA → GPIO17 (Pin 11)
```

### 3. Vérification du script Python
```bash
# Tester le script
sudo python3 /var/www/html/iot-dashboard/read_sensors.py
```

## Tests et débogage

### 1. Test de l'API
```bash
# Tester l'API PHP
curl http://localhost/iot-dashboard/get_sensor_data.php
```

### 2. Vérification des logs
```bash
# Logs Apache
sudo tail -f /var/log/apache2/error.log

# Logs système
sudo journalctl -f
```

### 3. Commandes utiles
```bash
# Statut d'Apache
sudo systemctl status apache2

# Redémarrer Apache
sudo service apache2 restart

# Vérifier la version PHP
php -v

# Vérifier les permissions
ls -l /var/www/html/iot-dashboard/
```

## Démarrage automatique

### 1. Configuration rc.local
```bash
# Éditer rc.local
sudo nano /etc/rc.local

# Ajouter avant "exit 0":
sudo service apache2 start
```

## Sécurité

### 1. Mesures de base
```bash
# Changer le mot de passe par défaut
passwd

# Mettre à jour régulièrement
sudo apt-get update && sudo apt-get upgrade
```

### 2. Configuration du pare-feu (optionnel)
```bash
# Installer ufw
sudo apt-get install ufw

# Configurer les règles de base
sudo ufw allow 80/tcp  # Pour Apache
sudo ufw allow 22/tcp  # Pour SSH
sudo ufw enable
```

## Accès au dashboard

1. Trouvez l'adresse IP de votre Raspberry Pi :
```bash
hostname -I
```

2. Ouvrez votre navigateur et accédez à :
```
http://adresse_ip_raspberry/iot-dashboard/dashboard.php
```

## Résolution des problèmes courants

### 1. Le dashboard ne s'affiche pas
- Vérifiez qu'Apache est en cours d'exécution
- Vérifiez les permissions des fichiers
- Consultez les logs Apache

### 2. Les capteurs ne fonctionnent pas
- Vérifiez les branchements
- Testez le script Python manuellement
- Vérifiez les permissions du groupe www-data

### 3. Erreurs PHP
- Vérifiez la configuration PHP
- Activez l'affichage des erreurs dans php.ini
- Consultez les logs PHP

## Maintenance

### 1. Sauvegarde
```bash
# Sauvegarder les fichiers du projet
sudo cp -r /var/www/html/iot-dashboard/ /backup/

# Sauvegarder la configuration Apache
sudo cp /etc/apache2/apache2.conf /backup/
```

### 2. Mises à jour
```bash
# Système
sudo apt-get update && sudo apt-get upgrade

# Python
sudo pip3 install --upgrade Adafruit_DHT
```

## Support

Pour plus d'aide ou en cas de problèmes :
1. Vérifiez les logs système
2. Consultez la documentation officielle de Raspberry Pi
3. Utilisez les forums de la communauté Raspberry Pi 