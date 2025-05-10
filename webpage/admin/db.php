<?php
// Clase para manejar la conexión a la base de datos de la tabla de denuncias
class Denuncia {
    private $host = "localhost";
    private $db_name = "cecyayuda";
    private $username = "denuncia";
    private $password = "123";

    public $conn = null;

    // Constructor de la clase
    public function __construct() {
        $this->conn = $this->getConnection();
    }

    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            // Verificar la conexión
            if ($this->conn->connect_error) {
                throw new Exception('Conexion a base de datos fallida.'.$this->conn->connect_error);
            }
        } catch(Exception $exception) {
            throw new Exception('Error al conectar con la base de datos.'.$exception->getMessage());
        }

        return $this->conn;
    }

    // Método para cerrar la conexión a la base de datos
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    // Metodo para insertar una denuncia
    public function insertDenuncia($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo) {
        // Preparar y bind
        $stmt = $this->conn->prepare("INSERT INTO denuncias (Folio, Descripcion, Fecha, Hora, Ubicacion, Nombre, CURP, Correo, Numtelefono, Tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("ssssssssss", $folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $stmt->close();
    }

    // Metodo para buscar una denuncia por folio
    public function searchDenuncia($folio) {
        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT * FROM denuncias WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("s", $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        return $result;
    }

    // Metodo para actualizar una denuncia
    function updateDenuncia($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo) {
        // Preparar y bind
        $stmt = $this->conn->prepare("UPDATE denuncias SET Descripcion = ?, Fecha = ?, Hora = ?, Ubicacion = ?, Nombre = ?, CURP = ?, Correo = ?, Numtelefono = ?, Tipo = ? WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("ssssssssss", $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo, $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $stmt->close();
    }

    // Metodo para eliminar una denuncia
    function deleteDenuncia($folio) {
        // Preparar y bind
        $stmt = $this->conn->prepare("DELETE FROM denuncias WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("s", $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $stmt->close();
    }

    // Metodo para verificar una denuncia
    function verifyDenuncia($folio) {
        // Preparar y bind
        $stmt = $this->conn->prepare("UPDATE denuncias SET Verified = 1 WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
            return false; // La denuncia no fue verificada
        }
        $stmt->bind_param("s", $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
            return false; // La denuncia no fue verificada
        }
        $stmt->close();
        return true; // La denuncia fue verificada
    }

    // Metodo para cambiar el status de una denuncia
    function changeStatus($folio, $status) {
        // Preparar y bind
        $stmt = $this->conn->prepare("UPDATE denuncias SET Status = ? WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("ss", $status, $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $stmt->close();
    }

    // Metodo para obtener todas las denuncias
    function getAllDenuncias() {
        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT * FROM denuncias");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        return $result;
    }
}


// Clase para manejar la conexión a la base de datos 
class Admins {
    private $host = "localhost";
    private $db_name = "cecyayuda";
    private $username = "denuncia";
    private $password = "123";

    public $conn = null;

    // Constructor de la clase
    public function __construct() {
        $this->conn = $this->getConnection();
    }

    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            // Verificar la conexión
            if ($this->conn->connect_error) {
                throw new Exception('Conexion a base de datos fallida.'.$this->conn->connect_error);
            }
        } catch(Exception $exception) {
            throw new Exception('Error al conectar con la base de datos.'.$exception->getMessage());
        }
    }

    // Método para cerrar la conexión a la base de datos
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    // Metodo para verificar si un usuario existe
    function userExists($username) {
        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE username = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("s", $username);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true; // El usuario existe
        } else {
            return false; // El usuario no existe
        }
    }

    // Metodo para verificar si un usuario y contraseña son correctos
    function verifyUser($username, $password) {
        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("ss", $username, $password);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verificar el usuario y contraseña
            if (password_verify($password, $row['password'])) {
                return true; // El usuario y contraseña son correctos
            } else {
                return false; // La contraseña es incorrecta
            }
        } else {
            return false; // El usuario y contraseña son incorrectos
        }
    }
}
?>