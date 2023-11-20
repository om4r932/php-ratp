import psycopg2 as sql
import json

mydb = sql.connect(host="localhost", user="omar", password=json.load(open("pass.json", "r", encoding="utf-8"))["pgsql_pass"], dbname="ratp_app")
lines = json.load(open("data/lines.json", "r", encoding="utf-8"))
stops = json.load(open("data/stops.json", "r", encoding="utf-8"))

if mydb is not None:
    print("Connecté à la base de données PostgreSQL")

cursor = mydb.cursor()
for mode in lines.keys():
    for lineName in lines[mode].keys():
        lineId = lines[mode][lineName]
        lineName = lineName.replace("\'", "\'\'")
        cursor.execute(f"INSERT INTO linedata VALUES ('{lineId}', '{lineName}', '{mode}')")

for lineId in stops.keys():
    for stopName in stops[lineId].keys():
        for ref in stops[lineId][stopName]:
            stopName = stopName.replace("\'", "\'\'")
            cursor.execute(f"INSERT INTO stopdata VALUES ('{ref}', '{lineId}', '{stopName}')")


mydb.commit()

cursor.close()
mydb.close()