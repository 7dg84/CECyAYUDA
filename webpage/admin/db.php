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
    public function insertDenuncia($folio, $hechos, $fecha, $hora, $cp, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file) {
        // Preparar y bind
        $stmt = $this->conn->prepare("INSERT INTO denuncias (Folio, Descripcion, Fecha, Hora, CP, Estado, Municipio, Colonia, Calle, Nombre, CURP, Correo, Numtelefono, Tipo, Evidencia) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("sssssssssssssss", $folio, $hechos, $fecha, $hora, $cp, $estado , $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file);
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
        $stmt->close();
        return $result;
    }

    // Metodo para buscar una denuncia por folio
    public function searchDenunciaBy($field, $operator, $value, $order, $limit, $offset) {
        // Validar el campo y el operador
        $valid_fields = ['Folio', 'Descripcion', 'Fecha', 'Hora', 'Estado', 'Municipio', 'Calle', 'Colonia', 'Nombre', 'CURP', 'Correo', 'Numtelefono', 'Tipo', 'Verified', 'Status'];
        $valid_operators = ['=', '<>', '>', '<', '>=', '<=', 'LIKE'];

        if (!in_array($field, $valid_fields)) {
            die("Campo inválido: " . htmlspecialchars($field));
        }
        if (!in_array($operator, $valid_operators)) {
            die("Operador inválido: " . ($operator));
        }

        $sql = "SELECT * FROM denuncias WHERE $field $operator ? ORDER BY $order LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }

        // Si es LIKE, agrega los comodines al valor
        if ($operator == 'LIKE') {
            $value = "%$value%";
        }

        $stmt->bind_param("si", $value, $limit);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    // Metodo para obtener la imagen de una denuncia
    public function getDenunciaImage($folio) {
        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT Evidencia FROM denuncias WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("s", $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        $stmt->close();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Evidencia']; // Retorna la imagen
        } else {
            return null; // No se encontró la denuncia
        }
    }

    // Metodo para verificar si esxiste una denuncia por folio
    public function existsDenuncia($folio) {
        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT Folio FROM denuncias WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("s", $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows > 0; // Retorna true si existe, false si no
    }

    // Metodo para actualizar una denuncia
    public function updateDenunciaWithoutFile($folio, $hechos, $fecha, $hora,$cp, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo) {
        // Preparar y bind
        $stmt = $this->conn->prepare("UPDATE denuncias SET Descripcion = ?, Fecha = ?, Hora = ?, CP = ?, Estado = ?, Municipio = ?, Colonia = ?, Calle = ?, Nombre = ?, CURP = ?, Correo = ?, Numtelefono = ?, Tipo = ? WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("ssssssssssssss", $hechos, $fecha, $hora, $cp, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $stmt->close();
    }

    // Metodo para actualizar una denuncia con archivo
    public function updateDenunciaWithFile($folio, $hechos, $fecha, $hora, $cp, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file) {
        // Preparar y bind
        $stmt = $this->conn->prepare("UPDATE denuncias SET Descripcion = ?, Fecha = ?, Hora = ?, CP = ?, Estado = ?, Municipio = ?, Colonia = ?, Calle = ?, Nombre = ?, CURP = ?, Correo = ?, Numtelefono = ?, Tipo = ?, Evidencia = ? WHERE Folio = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("sssssssssssssss", $hechos, $fecha, $hora,$cp, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file, $folio);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $stmt->close();
    }

    // Metodo para actualizar una denuncia desde el admin
    public function updateDenunciaBy($folio, $status, $verify) {
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
    public function deleteDenuncia($folio) {
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
    public function isEmailVerified($folio) {
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
        $stmt->close();
    }

    // Metodo para verificar una denuncia
    public function verifyDenuncia($folio) {
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
    public function changeStatus($folio, $status) {
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
    public function getAllDenuncias() {
        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT * FROM denuncias");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    // Metodo para obtener algunas las denuncias
    public function getDenuncias($n) {
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
        $stmt->close();
        return $result;
    }

    // Metodo para ontener algunas denuncias con orden de un campo
    public function getDenunciasWithOrder($n, $order) {
        // Validar el campo de orden
        $valid_orders = ['Folio', 'Fecha', 'Hora', 'Estado', 'Municipio', 'Colonia', 'Calle', 'Nombre', 'CURP', 'Correo', 'Numtelefono', 'Tipo', 'Status', 'Verified', 'Created'];
        if (!in_array($order, $valid_orders)) {
            die("Campo de orden inválido: " . ($order));
        }

        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT * FROM denuncias ORDER BY $order LIMIT ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("i", $n);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    // Metodo para buscar un folio de denuncia por datos del denunciante
    public function searchFolio($nombre, $curp, $correo, $telefono) {
        // Sanitizar los datos
        $nombre = self::sanitize($nombre);
        $curp = self::sanitize($curp);
        $correo = self::sanitize($correo);
        $telefono = self::sanitize($telefono);

        // Preparar y bind
        $stmt = $this->conn->prepare("SELECT Folio, Correo, Verified, Nombre FROM denuncias WHERE Nombre = ? AND CURP = ? AND Correo = ? AND Numtelefono = ?");
        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("ssss", $nombre, $curp, $correo, $telefono);
        if (!$stmt->execute()) {
            die("Error al ejecutar la declaración: " . htmlspecialchars($stmt->error));
        }
        $result = $stmt->get_result();
        $stmt->close();
        // Verificar si se encontró el folio
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $result; // Retorna los datos
        } else {
            return null; // No se encontró el folio
        }
    }
}



?>