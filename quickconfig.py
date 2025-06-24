import json
import sys

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
    update_config_json('''
{
    "db": {
        "host": "cecyayuda-db-1",
        "database": "cecyayuda",
        "user": "denuncia",
        "password": "123"
    },
    "admin": {
        "user": "admin",
        "passwordhash": "$2y$10$nUJHzwlR98IDhwt8T.QDtOkCYZxj6S5VoxyhKHbBWzy\/3dK67psLK",
        "email": ""
    },
    "mail": {
        "enckey": "6e4f3c0a-1b2d-4b8e-9c5f-7a0d1f3e2b8f",
        "host": "smtp.gmail.com",
        "port": 587,
        "user": "dragonflycodes5@gmail.com",
        "password": "gsdw cnqr tlta ksft",
        "from": [
            "denuncias@cecyayuda.lat",
            "CECyAYUDA"
        ],
        "url": "cecyayuda.lat"
    }
}
''')
    update_retoken_php('re_GdTH1Hj9_CzjMScwYwiXaUjmKDPjssVN6')
    print('Hecho')