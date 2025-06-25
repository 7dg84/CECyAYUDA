import json
import sys
import os

CONFIG_PATH = r'webpage\admin\config.json'
VERIFY_PATH = r'webpage\admin\verify.php'

def update_config_json(value):
    with open(CONFIG_PATH, 'r', encoding='utf-8') as f:
        f.write(value)
    print("Configuracion actualizada")

def update_retoken_php(new_value):
    with open(VERIFY_PATH, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    for i, line in enumerate(lines):
        if line.strip().startswith('$retoken'):
            lines[i] = f"$retoken = '{new_value}';\n"
            break
    else:
        # Si no existe, lo agrega al inicio
        lines.insert(0, f"$retoken = '{new_value}';\n")
    with open(VERIFY_PATH, 'w', encoding='utf-8') as f:
        f.writelines(lines)
    print("Actualizado $retoken en verify.php")

if __name__ == "__main__":
    
    print(os.getcwd())
    with open('webpage/admin/config.json', 'r') as config:
        config_data = json.load(config)
        for field, items in config_data.items():
            print(field)
            for key , value in items.items():
                user_input = input(f"{key} (Default: {value}): ").strip()
                if user_input:
                    config_data[key] = user_input
        with open('webpage/admin/config.json', 'w', encoding='utf-8') as config_out:
            json.dump(config_data, config_out, indent=4, ensure_ascii=False)
        print(config.read())
        config.close()
    print('Hecho')