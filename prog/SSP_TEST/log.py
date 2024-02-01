import os
import time
import paho.mqtt.client as mqtt

def on_message(client, userdata, msg):
    topic = msg.topic
    message = msg.payload.decode("utf-8")
    if message.strip():
        log_message = f"[ {time.strftime('%Y-%m-%d %H:%M:%S')} ] Topic: {topic} - Message: {message}"
        with open(log_file, 'a') as log:
            log.write(f"{log_message}\n")

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
