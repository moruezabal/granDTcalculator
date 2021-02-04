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

            $listScore = array_slice($player,4);
    
            $stmt = $conn->prepare( "SELECT * FROM player WHERE player.name = ?");
            $stmt->execute([$player['name']]);
            $results = $stmt -> fetch(PDO::FETCH_OBJ);
            $id = $results->id_player;
            $nro_round = 1;

            foreach ($listScore as $round => $score) {
                $stmt = $conn->prepare("INSERT INTO player_round (fk_id_player, fk_id_round, score) VALUES (?, ?, ?)");
                $insert = $stmt->execute([$id, $nro_round, $score]);
                if($insert){
                    $nro_round ++;
                }
                else{
                    echo "Error al insertar";
                }
            }
            echo("Jugador " . $id . " cargado");
        }
        echo("Proceso finalizado");
        
    }
?>