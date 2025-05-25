<?php
include_once 'config.php';
// Clase para manejar la conexión a la base de datos de la tabla de denuncias
class Denuncia {
    private $host;
    private $db_name;
    private $username;
    private $password;

    public $conn = null;

    // Constructor de la clase
    public function __construct() {
        $config = new Config();
        $this->host = $config['db']['host'];
        $this->db_name = $config['db']['database'];
        $this->username = $config['db']['user'];
        $this->password = $config['db']['password'];
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
            $this->conn->set_charset("utf8");
            date_default_timezone_set('America/Mexico_City');
        } catch(Exception $exception) {
            throw new Exception('Error al conectar con la base de datos.'.$exception->getMessage());
        }

        return $this->conn;
    }

    // Sanitizar los datos de entrada
    public static function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Método para cerrar la conexión a la base de datos
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    // Metodo para insertar una denuncia
    public function insertDenuncia($folio, $hechos, $fecha, $hora, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file) {
        // Preparar y bind
        $stmt = $this->conn->prepare("INSERT INTO denuncias (Folio, Descripcion, Fecha, Hora, Estado, Municipio, Colonia, Calle, Nombre, CURP, Correo, Numtelefono, Tipo, Evidencia) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("ssssssssssssss", $folio, $hechos, $fecha, $hora, $estado , $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file);
        // Verificar si se ejecutó correctamente
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

    // Metodo para buscar una denuncia por folio
    public function searchDenunciaBy($field, $operator, $value) {
        // Validar el campo y el operador
        $valid_fields = ['Folio', 'Descripcion', 'Fecha', 'Hora', 'Ubicacion', 'Nombre', 'CURP', 'Correo', 'Numtelefono', 'Tipo', 'Verified', 'Status'];
        $valid_operators = ['=', '!=', '>', '<', '>=', '<=', 'LIKE'];

        if (!in_array($field, $valid_fields)) {
            die("Campo inválido: " . htmlspecialchars($field));
        }
        if (!in_array($operator, $valid_operators)) {
            die("Operador inválido: " . htmlspecialchars($operator));
        }

        $sql = "SELECT * FROM denuncias WHERE `$field` $operator ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }

        // Si es LIKE, agrega los comodines al valor
        if ($operator == 'LIKE') {
            $value = "%$value%";
        }

        $stmt->bind_param("s", $value);
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

    // Metodo para actualizar una denuncia desde el admin
    function updateDenunciaBy($folio, $status, $verify) {
        // Preparar y bind
        $stmt = $this->conn->prepare("UPDATE denuncias SET `Status`=?, `Verified`=? WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("sss", $status, $verify, $folio);
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

    // Metodo para verificar si el email de la denuncia esta verificado
    function isEmailVerified($folio) {
        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT Verified FROM denuncias WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("s", $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Verified'] == 1; // Retorna true si el email está verificado
        } else {
            return false; // La denuncia no existe
        }
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

    // Metodo para obtener algunas las denuncias
    function getDenuncias($n) {
        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT * FROM denuncias LIMIT ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("i", $n);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        return $result;
    }
}



?>