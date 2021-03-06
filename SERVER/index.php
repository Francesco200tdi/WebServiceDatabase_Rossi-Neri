<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libridb";



$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




// process client request (via URL)
	header ("Content-Type_application/json");
	$funzione=$_GET['funzione'];
	switch($funzione)
	{
		case '0':
						
			
			deliver_response(200,"libri", $res);
		break;
		case '1':
			$stmt = $conn->prepare("SELECT COUNT(libri.id) FROM libri join reparti on libri.reparto = reparti.id join libricategoria on libri.id = libricategoria.libro where reparti.tipo = 'fumetti' and libricategoria.categoria = 'Ultimi arrivi'");
			$stmt->execute();

			$res = $stmt->fetchAll();
				
		
			deliver_response(200,"fumetti ", $res);		
			
		break;
		case '2':
			$stmt = $conn->prepare("SELECT titolo, sconto FROM libri JOIN libricategoria ON libri.id = libricategoria.libro JOIN categorie ON libricategoria.categoria = categorie.tipo WHERE sconto > 0 ORDER BY sconto");
			$stmt->execute();

			$res = $stmt->fetchAll();
		   
			deliver_response(200,"libri scontati ", $res);		
						
		break;
		case '3':
			$data1=$_GET['data1'];
			$data2=$_GET['data2'];			
			$stmt = $conn->prepare("SELECT titolo FROM libri WHERE dataArch BETWEEN data1 AND data2" );
            
            deliver_response(200,"date    ", $res);    
		break;
		
		default:
			deliver_response(400,"Invalid request", NULL);
		break;

        
	}
	
	//funzione per l'invio di messaggi al client
	function deliver_response($status, $status_message, $data)
	{
		header("HTTP/1.1 $status $status_message");
		
		$response ['status']=$status;
		$response['status_message']=$status_message;
		$response['data']=$data;
		
		$json_response=json_encode($response);
		echo $json_response;
	}
?>