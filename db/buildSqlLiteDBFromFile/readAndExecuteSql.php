<?php


// $SQLfilePath = __DIR__ . '/../db/productsStructure.php';


// from
// http://stackoverflow.com/questions/4027769/running-mysql-sql-files-in-php
private function createDBFromSQLFile($SQLfilePath, $db)
{
    //load file
    $commands = file_get_contents($SQLfilePath);

    //delete comments
    $lines = explode("\n",$commands);
    $commands = '';
    foreach($lines as $line){
        $line = trim($line);
        if( $line && !$this->startsWith($line,'--') ){
            $commands .= $line . "\n";
        }
    }

    //convert to array
    $commands = explode(";", $commands);

    //run commands
    $total = $success = 0;
    foreach($commands as $command){
        if(trim($command)){
            $query = $db->query($command);
//            $success += (@mysql_query($command)==false ? 0 : 1);
//            $total += 1;
        }
    }

    //return number of successful queries and total number of queries found
    return array(
        "success" => $success,
        "total" => $total
    );
}


private function startsWith($haystack, $needle){
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}
