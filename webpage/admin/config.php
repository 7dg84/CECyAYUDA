<?php

class Config implements ArrayAccess {
    public $data;

    public function __construct($file = 'config.json') {
        $path = __DIR__ . DIRECTORY_SEPARATOR . $file;
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $this->data = json_decode($json, true);
        } else {
            $this->data = [];
        }
    }

    // Save changes to the config file
    public function save($file = 'config.json') {
        $path = __DIR__ . DIRECTORY_SEPARATOR . $file;
        file_put_contents($path, json_encode($this->data, JSON_PRETTY_PRINT));
        return file_exists($path);
    }

    // Implement ArrayAccess methods
    public function offsetExists($offset): bool {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value): void {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void {
        unset($this->data[$offset]);
    }
}
?>