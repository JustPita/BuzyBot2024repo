import MySQLdb
from datetime import datetime, timedelta

# Paramètres de connexion à la base de données
db_config = {
    'host': 'localhost',
    'user': 'ServiceAccount',
    'password': 'S€rv!ce4ccount',
    'database': 'DBBUSYBOT'
}

# Générer une nouvelle date
new_date = (datetime.now() + timedelta(seconds=1)).strftime('%Y-%m-%d %H:%M:%S')

# La requête SQL à exécuter
sql_query = f"INSERT INTO `event` (`date`, `evenement`, `message`, `idportier`) VALUES ('{new_date}', 'A', '', '1')"

# Connexion à la base de données et exécution de la requête
try:
    # Établir la connexion
    conn = MySQLdb.connect(**db_config)

    # Créer un objet curseur pour exécuter des requêtes
    cursor = conn.cursor()

    # Exécuter la requête SQL
    cursor.execute(sql_query)

    # Valider la transaction
    conn.commit()

    print("Requête SQL exécutée avec succès!")

except MySQLdb.Error as err:
    print(f"Erreur MySQL : {err}")

finally:
    # Fermer le curseur et la connexion
    if 'cursor' in locals():
        cursor.close()
    if 'conn' in locals():
        conn.close()
