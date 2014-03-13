<?php
	require ("conn.php");

	//Suprime warnings
	error_reporting(E_ERROR | E_PARSE);

	//Nome do arquivo
	$fileName = $_FILES["filePhoto"]["name"]; 

	//Garente que não haverá uma imagem com o mesmo nome
	$newImageName = 'image_' . date('Y-m-d') . '_' . uniqid() . '_' . $fileName ;

	//Id do usuário
	$userId = $_POST['userValuePhotoName'];

	$fileTmpLoc = $_FILES["filePhoto"]["tmp_name"];

	//Local + Nome do arquivo
	$pathAndName = "../resources/images/uploads/". $newImageName;

	//Verifica se o usuário já fez o upload de alguma photo
	//isso servirá para remover a imagem antiga e atualizar o path com a nova
	$checkPhoto = mysql_query("	Select
									id
								From
									userlogo
								Where
									userId = ". $userId ."
							");

	$rowsCheckPhoto = mysql_num_rows($checkPhoto);

	if($rowsCheckPhoto >= 1){

		//Move imagem antiga
		moveOldFiles($userId, $fileTmpLoc);

		//Foi encontrada photo, faz update do nome imagem
		$updatePhoto = mysql_query ("Update userlogo Set logoName = '". $newImageName ."' Where userId = ". $userId ."");

		//Move arquivo para a pasta
		$moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);

		//Verifica se o arquivo foi movido corretamente
		if ($moveResult == true) {
			$message = "Foto alterada com sucesso.";
		} else {
			$message = "Falha ao alterar foto.";
		}
		
	} else {
		//Não foi encontrada photo, insert
		$insertPhoto = mysql_query ("Insert Into userlogo Values ('', '". $newImageName ."', ". $userId .") ");

		//Move arquivo para a pasta
		$moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);

		//Verifica se o arquivo foi movido corretamente
		if ($moveResult == true) {
			$message = "Foto alterada com sucesso.";
		} else {
			$message = "Falha ao alterar foto.";
		}
	}


	//Função para mover arquivos antigos
	function moveOldFiles($userId, $fileTmpLoc){
		//Verifica nome da imagem atual
		$getName = mysql_query("Select logoName From userLogo Where userId = ". $userId ." ");
		
		//Pega nome do arquivo atual para o usuário especificado
		$resName = mysql_fetch_object($getName);

		//Pega url base
		$baseUrl = "http://" . $_SERVER['SERVER_NAME'] . '/';

		//Verifica se é ambiente de produção ou desenvolvimento
		if($baseUrl == 'http://localhost/'){		
			//Remove imagem antiga
			$unlink = unlink("../resources/images/uploads/". $resName->logoName);

		} else {
			//Remove imagem antiga
			//$unlink = unlink("../resources/images/uploads/". $resName->logoName);
			unlink("/home/luish360/public_html/trabalhos/2014/BManager/resources/images/uploads/image_2014-03-13_5321b19d5c3db_Chrysanthemum.jpg");
		}

		
	}


	echo $message;
	
?>