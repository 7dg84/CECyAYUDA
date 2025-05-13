<?php
return ['db' => [
    'host' => 'cecyayuda-db-1',
    'database' => 'cecyayuda',
    'user' => 'denuncia',
    'password' => '123'
    ],
    'admin' => [
        'user' => 'admin',
        'password' => '123'
    ]];

echo password_hash('cecyayuda2025', PASSWORD_DEFAULT);
?>