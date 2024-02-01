import os
import time
import paho.mqtt.client as mqtt
import MySQLdb

def execute_sql_command(command):
    connection = MySQLdb.connect(
        host="localhost",
        user="ServiceAccount",
        passwd="S€rv!ce4ccount",
        db="DBBUSYBOT"
    )
    cursor = connection.cursor()

    try:
        cursor.execute(command)
        connection.commit()
    except Exception as e:
        print(f"Erreur lors de l'exécution de la commande SQL: {e}")
        connection.rollback()
    finally:
        cursor.close()
        connection.close()

def on_message(client, userdata, msg):
    if msg.topic == "REPONSE":
        topic = "R"
    elif msg.topic == "APPEL":
        topic = "A"
    message = msg.payload.decode("utf-8")
    if message.strip():
        log_message = f"[ {time.strftime('%Y-%m-%d %H:%M:%S')} ] Topic: {topic} - Message: {message}"
        with open(log_file, 'a') as log:
            log.write(f"{log_message}\n")

        command = f"INSERT INTO event (date, evenement, message, idportier) VALUES (NOW(), '{topic}', '{message}', 1)"
        execute_sql_command(command)

topics_file = "/etc/mosquitto/topicsSub.txt"
log_file = "/var/log/mosquitto/allTopics.log"

if not os.path.exists(log_file):
    open(log_file, 'w').close()

client = mqtt.Client()
client.on_message = on_message
client.connect("localhost", 1883, 60)
with open(topics_file, 'r') as file:
    topics = file.read().split(';')
    for topic in topics:
        topic = topic.strip()
        if topic:
            client.subscribe(topic)

client.loop_forever()
