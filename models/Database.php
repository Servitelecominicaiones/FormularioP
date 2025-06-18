<?php
    
    class DataBase{       
    
         public static function connection(){
            $config = [
                'host' => '172.20.160.9', 
                'port' => '3306',
                'dbname' => 'automatizacion', 
                'charset' => 'utf8mb4',
                'username' => 'user_automic', 
                'password' => '4uT0m1c.Serv1t3l.D3v*' 
            ];
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            try {
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
                return new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
    }
?>