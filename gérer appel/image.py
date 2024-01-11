import time
import picamera
from gpiozero import Button

# Définir le numéro de broche du bouton
bouton_numero_broche = 17

# Initialiser la caméra
camera = picamera.PiCamera()

# Initialiser le bouton
bouton = Button(bouton_numero_broche)

def prendre_photo():
    # Nom du fichier image avec un horodatage
    nom_fichier = f"photo_{time.strftime('%Y%m%d%H%M%S')}.jpg"
    
    # Prendre une photo
    camera.capture(nom_fichier)
    
    # Afficher le chemin du fichier
    print(f"Photo enregistrée : {nom_fichier}")

# Associer la fonction `prendre_photo` au signal du bouton
bouton.when_pressed = prendre_photo

try:
    # Garder le programme en cours d'exécution
    while True:
        time.sleep(1)

except KeyboardInterrupt:
    # Arrêter la caméra lorsqu'on appuie sur Ctrl+C
    camera.close()
    print("Programme arrêté.")