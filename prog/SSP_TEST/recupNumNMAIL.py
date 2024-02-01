import MySQLdb

def get_user_info(idlocal):
    connection = MySQLdb.connect(
        host="localhost",
        user="ServiceAccount",
        passwd="S€rv!ce4ccount",
        db="DBBUSYBOT"
    )
    cursor = connection.cursor()

    try:
        query = f"SELECT user.mail, user.tel FROM user INNER JOIN Affecter ON user.iduser = Affecter.iduser WHERE Affecter.idlocal = {idlocal}"
        cursor.execute(query)
        result = cursor.fetchone()

        if result:
            email, phone = result
            print(f"Adresse e-mail de l'utilisateur : {email}")
            print(f"Numéro de téléphone de l'utilisateur : {phone}")
        else:
            print("Aucun utilisateur trouvé pour le local spécifié.")

    except Exception as e:
        print(f"Erreur lors de l'exécution de la requête SQL : {e}")
    finally:
        cursor.close()
        connection.close()

idlocal_specifique = 1
get_user_info(idlocal_specifique)
