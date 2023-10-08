import json

datatr = json.load(open("data/data-tr.json", "r", encoding="utf-8"))
lines = json.load(open("data/lines.json", "r", encoding="utf-8"))
blines = {}
bstops = {}


for line in lines:
    if line["transportmode"] == "bus":
        for arret_tr in datatr:
            x = arret_tr["line"].split(":")[-2]
            if x == line["id_line"] and line["name_line"] not in blines.keys():
                blines[line["name_line"]] = line["id_line"]


for arret_tr in datatr:
    lineId = arret_tr["line"].split(":")[-2]
    stopId = arret_tr["stopPointRef"].split(":")[-2]
    stopName = arret_tr["stopName"]
    try:
        if lineId in blines.values() and lineId not in bstops:
            bstops[lineId] = {}
        if stopName not in bstops[lineId] and lineId in blines.values():
            bstops[lineId][stopName] = [stopId]
        else:
            bstops[lineId][stopName].append(stopId)
    except:
        continue
    
json.dump(blines, open("data/blines2.json", "w", encoding="utf-8"), ensure_ascii=False, indent=4)
json.dump(bstops, open("data/bstops2.json", "w", encoding="utf-8"), ensure_ascii=False, indent=4)