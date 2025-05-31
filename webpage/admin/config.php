<?php

class Config implements ArrayAccess
{
    public $data;

    public function __construct($file = 'config.json')
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . $file;
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $this->data = json_decode($json, true);
        } else {
            // If the file does not exist, create the file with default data
            $this->data = [
                "db" => [
                    "host" => "cecyayuda-db-1",
                    "database" => "cecyayuda",
                    "user" => "denuncia",
                    "password" => "123"
                ],
                "admin" => [
                    "user" => "admin",
                    "passwordhash" => "$2y$10\$nUJHzwlR98IDhwt8T.QDtOkCYZxj6S5VoxyhKHbBWzy/3dK67psLK",
                    "email" => ""
                ],
                "mail" => [
                    "enckey" => "123",
                    "host" => "smtp.gmail.com",
                    "port" => 587,
                    "user" => "",
                    "password" => "",
                    "from" => [
                        "Denuncias",
                        "Denuncias"
                    ],
                    "url" => "192.168.0.1"
                ]
            ];
            $this->save($file); // Save the default config
        }
    }

    // Save changes to the config file
    public function save($file = 'config.json')
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . $file;
        file_put_contents($path, json_encode($this->data, JSON_PRETTY_PRINT));
        return file_exists($path);
    }

    // Implement ArrayAccess methods
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }
}
