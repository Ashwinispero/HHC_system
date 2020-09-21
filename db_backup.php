<?php 

 // public function auto_backup_generator(){
        
        $dbhost   = "localhost";
        $dbuser   = "hospital_sp_pune";
        $dbpwd    = "Spero@Pune@2016";
        $dbname   = "hospital_spero_broadcast_live";
        
       
        
        $dumpfile = "/home/hospitalguru/public_html/Database_backup/".$dbname . "_" . date("Y-m-d_H-i-s") . ".sql";
        //echo "mysqldump --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname > $dumpfile";
        $db_result = system("mysqldump --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname > $dumpfile");
       // var_dump($db_result);
        die();
       
   // }
?>