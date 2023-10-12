import json

datatr = json.load(open("data/data-tr.json", "r", encoding="utf-8"))
lines = json.load(open("data/lines.json", "r", encoding="utf-8"))
mlines = {}
mstops = {}

for line in lines:
    if line["transportmode"] == "rail":
        for arret_tr in datatr:
            x = arret_tr["line"].split(":")[-2]
            if x == line["id_line"] and line["name_line"] not in mlines.keys():
                mlines[line["name_line"]] = line["id_line"]


for arret_tr in datatr:
    lineId = arret_tr["line"].split(":")[-2]
    stopId = arret_tr["stopPointRef"].split(":")[-2]
    stopName = arret_tr["stopName"]
    try:
        if lineId in mlines.values() and lineId not in mstops:
            mstops[lineId] = {}
        if stopName not in mstops[lineId] and lineId in mlines.values():
            mstops[lineId][stopName] = [stopId]
        else:
            mstops[lineId][stopName].append(stopId)
    except:
        continue
    
json.dump(mlines, open("data/rlines.json", "w", encoding="utf-8"), ensure_ascii=False, indent=4)
json.dump(mstops, open("data/rstops.json", "w", encoding="utf-8"), ensure_ascii=False, indent=4)