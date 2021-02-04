<?php
    $db_host="localhost";
    $db_name="db_grandt";
    $db_user="root";
    $db_pass="";
    include 'simpleXLSX.php';

    try {
        $conn = new PDO( "mysql:host=$db_host;dbname=$db_name", "$db_user", "$db_pass");
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    if ( $xlsx = SimpleXLSX::parse('planteles.xlsx')) {

        $header_values = $rows = [];
    
        foreach ( $xlsx->rows() as $k => $r ) {
            if ( $k === 0 ) {
                $header_values = $r;
                continue;
            }
            $rows[] = array_combine( $header_values, $r);

        }

        foreach ($rows as $player){
            foreach ($player as $header => $value){
                if (empty($value)){
                    $player[$header] = null;
                }
            }

            
            $stmt = $conn->prepare( "SELECT * FROM player WHERE player.name = ?");
            $stmt->execute([$player['name']]);
            $results = $stmt -> fetch(PDO::FETCH_OBJ);
            var_dump($results->id_player);


            exit;
        }
        
           
       
    }
?>