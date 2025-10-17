<?php
$input = '123';
$hash = '$2y$10$ZxMjb0hZq3fBjwnJbO6kCOj/X.4PLvV1FGgZbAak4gLCgIT55UmVm';

if (password_verify($input, $hash)) {
    echo "✅ Coincide, el password_verify funciona correctamente.";
} else {
    echo "❌ No coincide, el hash no corresponde a '123'.";
}
?>
