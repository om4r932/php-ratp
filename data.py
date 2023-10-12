import json

datatr = json.load(open("data/data-tr.json", "r", encoding="utf-8"))
lines = json.load(open("data/data-lines.json", "r", encoding="utf-8"))
full_lines = {"bus": {}, "metro": {}, "rail": {}, "tram": {}} # séparés par les différents catégories
full_stops = {} # tous mélangés (vu qu'on se base sur les clés (lineId))

# Oublie pas de supprimer les différents xlines et xstops

for line in lines:
    if line["transportmode"] == "bus":
        for arret_tr in datatr:
            x = arret_tr["line"].split(":")[-2]
            if x == line["id_line"] and line["name_line"] not in full_lines.keys():
                full_lines["bus"][line["name_line"]] = line["id_line"]
    elif line["transportmode"] == "metro":
        for arret_tr in datatr:
            x = arret_tr["line"].split(":")[-2]
            if x == line["id_line"] and line["name_line"] not in full_lines.keys():
                full_lines["metro"][line["name_line"]] = line["id_line"]
    elif line["transportmode"] == "rail":
        for arret_tr in datatr:
            x = arret_tr["line"].split(":")[-2]
            if x == line["id_line"] and line["name_line"] not in full_lines.keys():
                full_lines["rail"][line["name_line"]] = line["id_line"]
    elif line["transportmode"] == "tram":
        for arret_tr in datatr:
            x = arret_tr["line"].split(":")[-2]
            if x == line["id_line"] and line["name_line"] not in full_lines.keys():
                full_lines["tram"][line["name_line"]] = line["id_line"]

for arret_tr in datatr:
    lineId = arret_tr["line"].split(":")[-2]
    stopId = arret_tr["stopPointRef"].split(":")[-2]
    stopName = arret_tr["stopName"]
    try:
        if lineId in full_lines.values() and lineId not in full_stops:
            full_stops[lineId] = {}
        if stopName not in full_stops[lineId] and lineId in full_lines.values():
            full_stops[lineId][stopName] = [stopId]
        else:
            full_stops[lineId][stopName].append(stopId)
    except:
        continue
    
json.dump(full_lines, open("data/lines.json", "w", encoding="utf-8"), ensure_ascii=False, indent=4)
json.dump(full_stops, open("data/stops.json", "w", encoding="utf-8"), ensure_ascii=False, indent=4)