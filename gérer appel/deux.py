import base64
import json
import time
import paho.mqtt.client as mqtt

# Données pour se connecter à l'interface MQTT de ChirpStack
NOM         = "reexpediteur"
BROKER      = "localhost"
PORT        = 1883
KEEPALIVE   = 1000
FPORT       = 2
APPLICATION = "A REMPLACER"

# Informations sur nos objets
EUI_BOUTON   = 3600000000000000
EUI_SONNERIE = 2300000000000000

# Données pour communiquer avec l'interface MQTT
TOPIC_BOUTON   = f"application/{APPLICATION}/device/{EUI_BOUTON}/event/up"
TOPIC_SONNERIE = f"application/{APPLICATION}/device/{EUI_SONNERIE}/command/down"

# Retiens le dernier message à réexpédié, encore non réexpédier.
a_reexpedier = None

# Enregistre notre script comme client MQTT, càd comme pouvant interagir avec l'interface MQTT.
my_client = mqtt.Client(NOM)



### Fonctions de gestion de l'interface MQTT
def on_connect_cb(client, userdata, flags, return_code):
    """
        Fonction appelée lors de la connexion a l'interface MQTT.
    """
    del client, userdata, flags
    if return_code == 0:
        print("Connexion établie")
    else:
        print("Échec de connexion")
        sys.exit(-1)
        
def on_disconnect_cb(client, userdata, return_code):
    """
        Fonction appelée lors de la déconnexion a l'interface MQTT.
    """
    del client, userdata
    if return_code :
        print("Erreur de connexion, connexion perdue")
    else:
        print("Déconnexion")
    
        
def connect(client):
    """
        Fonction chargée de la connexion à l'interface MQTT.
    """
    client.loop_start()
    client.connect(BROKER, PORT, KEEPALIVE)
    # Attends que la connexion soit établie
    while not client.is_connected():
        time.sleep(.1)
        
def disconnect(client):
    """
        Fonction chargée de la déconnexion à l'interface MQTT.
    """
    client.disconnect()
    client.loop_stop()


def on_message_cb(client, userdata, message):
    """
        Fonction appelée lorsque un message est reçu.
    """
    global a_reexpedier
    del client, userdata
    print("Message reçu !")
    # On prend le message dans le paquet le contenant.
    message = message.payload
    # On décode le message.
    message_decode = message.decode("utf-8")
    # On précise qu'il est écrit au format JSON.
    message_json = json.loads(message_decode)
    # On récupère uniquement la phrase écrite dans le message, on oublie le destinataire et les autres informations inintéressantes ici.
    phrase_code = message_json["data"]
    # On décode encore une fois, la phrase étant compressée.
    phrase = base64.b64decode(phrase_code)
    
    ### On peut maintenant recréer un paquet pour renvoyer la phrase à la sonnerie.
    
    mon_message = {}
    # Le message doit être envoyé à la sonnerie.
    mon_message['devEui'] = "{}".format(EUI_SONNERIE)
    # On ne demande pas d'accuser réception.
    mon_message['confirmed'] = False
    # Le message doit utiliser le port 2, comme préciser dans le projet que nous avons modifier.
    mon_message['fPort'] = FPORT
    # On n'oublie pas de mettre dans notre message la phrase codée correctement.
    mon_message['data'] = base64.b64encode(phrase).decode('utf-8')
    # Finalement, on code le message.
    message_code = json.dumps(mon_message)
    
    # On met le message de coter pour qu'il soit envoyé dès que possible.
    a_reexpedier = message_code
    
    

# Enregistre les fonctions à appelés automatiquement lors de la connexion, déconnexion et la réception d'un message
my_client.on_connect = on_connect_cb
my_client.on_disconnect = on_disconnect_cb
my_client.on_message = on_message_cb

# Connect notre client à l'interface MQTT de notre serveur ChirpStack
connect(my_client)


my_client.subscribe(TOPIC_BOUTON)

while True:
    if a_reexpedier:
        my_client.publish(TOPIC_SONNERIE,a_reexpedier).wait_for_publish()
        print("Message envoyé !")
        a_reexpedier = None    