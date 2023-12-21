<?php
class Config
{
    private $dbSettings;
    private $errorsettings;
    
    public function __construct()
    {
        $this->dbSettings=[
            'dbname'=>'slimphp',
            'user'=> 'bipul',
            'password'=> 'password',
            'host'=> 'mysql',
            'driver' => 'pdo_mysql'
        ];
    }

    public function getDbConfig(){
        return $this->dbSettings;
    }
}