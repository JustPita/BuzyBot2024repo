import MySQLdb
import os

def get_user_info(idlocal):
    connection = MySQLdb.connect(
        host="localhost",
        user="ServiceAccount",
        passwd="S€rv!ce4ccount",
        db="DBBUSYBOT"
    )
    cursor = connection.cursor()

    try:
        query = f"SELECT user.tel FROM user INNER JOIN Affecter ON user.iduser = Affecter.iduser WHERE Affecter.idlocal = {idlocal}"
        cursor.execute(query)
        result = cursor.fetchone()

        if result:
            return result[0]
        else:
            return None

    except Exception as e:
        print(f"Erreur lors de l'exécution de la requête SQL : {e}")
        return None
    finally:
        cursor.close()
        connection.close()

def send_sms(message, destination_number):
    command = f'echo "{message}" | gammu sendsms TEXT {destination_number}'
    os.system(command)

def main():
    idlocal_specifique = 1
    phone_number = get_user_info(idlocal_specifique)

    if phone_number:
        message = "Quelqu'un sonne au portier"
        send_sms(message, phone_number)
        print("Message envoyé avec succès.")
    else:
        print("Impossible de récupérer le numéro de téléphone de l'utilisateur.")
if __name__ == "__main__":
    main()
