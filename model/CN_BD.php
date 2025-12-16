<?php
//if (!class_exists('CN_BD')) {
class CN_BD
{
    public function conectar()
    {
        $server   = "tcp:serverab.database.windows.net,1433";
        $database = "NuevaSanta";
        $user     = "Julianab@serverab";
        $pass     = "tuguis2004A@";

        try {
            $pdo = new PDO(
                "sqlsrv:server=$server;Database=$database",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            die("ERROR CONECTANDO AZURE SQL: " . $e->getMessage());
        }
    }
}
//}


//if (!isset($pdo)) {
   // $cn = new CN_BD();
    //$pdo = $cn->conectar();
//}
