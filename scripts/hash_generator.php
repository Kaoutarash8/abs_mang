<?php
$passwords = [
    'etu123',
    'etu456',
    'etu789',
    'motdepasse1',
    'motdepasse2',
    'motdepasse3',
    'secret123',
    'password',
    'admin123',
    'monmdp2025'
];

foreach ($passwords as $pwd) {
    $hash = password_hash($pwd, PASSWORD_DEFAULT);
    echo "Mot de passe : $pwd\nHash : $hash\n\n";
}
