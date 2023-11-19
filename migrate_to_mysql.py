import psycopg2 as sql

mydb = sql.connect(host="localhost", user="omar", password="az7az9az8", dbname="ratp_app")

if sql is not None:
    print("Connecté à la base de données PostgreSQL")

cursor = mydb.cursor()
cursor.execute("SELECT * FROM linedata")
rows = cursor.fetchall()

for row in rows:
    print(row)

cursor.close()
mydb.close()