import os
import shutil

def main():
    # Directorio actual
    current_directory = os.path.join(os.getcwd(), "webpage")
    # Directorio de destino
    htdocs_directory = "C:\\xampp\\htdocs\\web\\"

    # Asegurarse de que el directorio de destino exista
    if not os.path.exists(htdocs_directory):
        os.makedirs(htdocs_directory)

    # Copiar archivos del directorio actual al directorio de destino
    for item in os.listdir(current_directory):
        source_path = os.path.join(current_directory, item)
        destination_path = os.path.join(htdocs_directory, item)

        if os.path.isfile(source_path):
            shutil.copy2(source_path, destination_path)  # Copiar archivos
        elif os.path.isdir(source_path):
            shutil.copytree(source_path, destination_path, dirs_exist_ok=True)  # Copiar directorios
    
    print("Successfully copied files to htdocs directory.")

if __name__ == "__main__":
    main()