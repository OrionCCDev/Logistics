import openpyxl
import json

wb = openpyxl.load_workbook('public/data.xlsx')
ws = wb.active
data = [[cell.value for cell in row] for row in ws.iter_rows()]
header = data[0]
idx_proj = 0  # first column
idx_type = 1  # second column
idx_plate = 2  # third column
# idx_year = 3  # fourth column (not used for now)
idx_supplier = -1  # last column
vehicles = {}
for row in data[1:]:
    plate = str(row[idx_plate]).strip() if row[idx_plate] else None
    if not plate:
        continue
    vtype = str(row[idx_type]).strip() if row[idx_type] else ''
    supplier = str(row[idx_supplier]).strip() if row[idx_supplier] else ''
    project = str(row[idx_proj]).strip() if row[idx_proj] else ''
    if plate not in vehicles:
        vehicles[plate] = {
            'plate_number': plate,
            'vehicle_type': vtype,
            'supplier_name': supplier,
            'projects': set()
        }
    vehicles[plate]['projects'].add(project)
# Convert sets to lists
result = [
    {
        'plate_number': v['plate_number'],
        'vehicle_type': v['vehicle_type'],
        'supplier_name': v['supplier_name'],
        'projects': list(v['projects'])
    }
    for v in vehicles.values()
]
with open('public/vehicles_data.json', 'w', encoding='utf-8') as f:
    json.dump(result, f, ensure_ascii=False, indent=2)
