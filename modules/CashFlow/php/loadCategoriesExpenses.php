<?php
	//Suprime warnings
	error_reporting(E_ERROR | E_PARSE);

	require('../../../php/conn.php');

	//Recebe dados para carregar categorias da natureza despesa
	$userId = $_POST['userId'];
	$ano = $_POST['ano'];

	//Caso a variavel ano chegue vazia, é setado o ano atual
	if($ano == ''){
		$ano = date("Y");
	}

	//Através do id do usuário logado é verificado quem é o usuário master
	$getMaster = mysql_query("Select userMaster From users Where id = $userId");
	$resMaster = mysql_fetch_object($getMaster);

	//Query que retorna lista com categorias de natureza despesa para o ano informado
	//e que estejam abaixo do usuário master
	$categoryListExpense = mysql_query("Select 
											a.categoryName,
											a.id As catId
											,Sum(b.jan) as TotalJan
											,Sum(b.fev) as TotalFev
											,Sum(b.mar) as TotalMar
											,Sum(b.abr) as TotalAbr
											,Sum(b.mai) as TotalMai
											,Sum(b.jun) as TotalJun
											,Sum(b.jul) as TotalJul
											,Sum(b.ago) as TotalAgo
											,Sum(b.set) as TotalSet
											,Sum(b.out) as TotalOut
											,Sum(b.nov) as TotalNov
											,Sum(b.dez) as TotalDez
										From 
											cashflowcategories a 
											Inner Join cashflowexpenses b On (a.id = b.categoryId)
										Where 
											a.userMaster = '$resMaster->userMaster'
											And b.ano = ". $ano ."
										Group By
											a.categoryName
											,a.id");

		//Variavel que recebe tabela, dessa forma não é necessário modificar o DOM
		//a cada iteração
		$table = "";

		$table = "	<tr>
						<th colspan = '13'> Natureza - Despesa </th>
					</tr>";

		//Verifica as categorias de natureza despesa que estejam sem despesas associadas
		$emptyCat = mysql_query("Select 
									id 
									,categoryName
								From 
									cashflowcategories 
								Where 
									userMaster = '". $resMaster->userMaster ."'
									And ano = ". $ano ."
									And categoryTypeId = 1
									And id not in(Select categoryId From cashflowexpenses Where ano = $ano)");

		//Itera categorias de natureza despesa vazias
		while($resEmptyCat = mysql_fetch_object($emptyCat)){
			//Retorna valor zerado uma vez que não há nenhuma despesa associada
			$table .= "	<tr class = 'tableRow' id = ". 'category_' .$resEmptyCat->id .">
							<td class = 'expenseTitleCat' title = ".str_replace(' ', '_', $resEmptyCat->categoryName).">". $resEmptyCat->categoryName ."</td>
							<td class = 'jan'> R$ 0,00 </td>
							<td class = 'fev'> R$ 0,00 </td>
							<td class = 'mar'> R$ 0,00 </td>
							<td class = 'abr'> R$ 0,00 </td>
							<td class = 'mai'> R$ 0,00 </td>
							<td class = 'jun'> R$ 0,00 </td>
							<td class = 'jul'> R$ 0,00 </td>
							<td class = 'ago'> R$ 0,00 </td>
							<td class = 'set'> R$ 0,00 </td>
							<td class = 'out'> R$ 0,00 </td>
							<td class = 'nov'> R$ 0,00 </td>
							<td class = 'dez'> R$ 0,00 </td>
						</tr>";
		}

		//Itera categorias de natureza despesa que contenham valor
		while($resCategoryListExpense = mysql_fetch_object($categoryListExpense)){
			//Imprime categorias com valor
			$table .= "	<tr class = 'tableRow' id = ". 'category_' .$resCategoryListExpense->catId .">
							<td class = 'expenseTitleCat' title = ".str_replace(' ', '_', $resCategoryListExpense->categoryName).">". $resCategoryListExpense->categoryName ."</td>
							<td class = 'jan'>". 'R$ ' . number_format($resCategoryListExpense->TotalJan,2,",",".") ."</td>
							<td class = 'fev'>". 'R$ ' . number_format($resCategoryListExpense->TotalFev,2,",",".") ."</td>
							<td class = 'mar'>". 'R$ ' . number_format($resCategoryListExpense->TotalMar,2,",",".") ."</td>
							<td class = 'abr'>". 'R$ ' . number_format($resCategoryListExpense->TotalAbr,2,",",".") ."</td>
							<td class = 'mai'>". 'R$ ' . number_format($resCategoryListExpense->TotalMai,2,",",".") ."</td>
							<td class = 'jun'>". 'R$ ' . number_format($resCategoryListExpense->TotalJun,2,",",".") ."</td>
							<td class = 'jul'>". 'R$ ' . number_format($resCategoryListExpense->TotalJul,2,",",".") ."</td>
							<td class = 'ago'>". 'R$ ' . number_format($resCategoryListExpense->TotalAgo,2,",",".") ."</td>
							<td class = 'set'>". 'R$ ' . number_format($resCategoryListExpense->TotalSet,2,",",".") ."</td>
							<td class = 'out'>". 'R$ ' . number_format($resCategoryListExpense->TotalOut,2,",",".") ."</td>
							<td class = 'nov'>". 'R$ ' . number_format($resCategoryListExpense->TotalNov,2,",",".") ."</td>
							<td class = 'dez'>". 'R$ ' . number_format($resCategoryListExpense->TotalDez,2,",",".") ."</td>
						</tr>";

			//Calcula valor total para cada mês
			$totalJan = $totalJan + $resCategoryListExpense->TotalJan;
			$totalFev = $totalFev + $resCategoryListExpense->TotalFev;
			$totalMar = $totalMar + $resCategoryListExpense->TotalMar;
			$totalAbr = $totalAbr + $resCategoryListExpense->TotalAbr;
			$totalMai = $totalMai + $resCategoryListExpense->TotalMai;
			$totalJun = $totalJun + $resCategoryListExpense->TotalJun;
			$totalJul = $totalJul + $resCategoryListExpense->TotalJul;
			$totalAgo = $totalAgo + $resCategoryListExpense->TotalAgo;
			$totalSet = $totalSet + $resCategoryListExpense->TotalSet;
			$totalOut = $totalOut + $resCategoryListExpense->TotalOut;
			$totalNov = $totalNov + $resCategoryListExpense->TotalNov;
			$totalDez = $totalDez + $resCategoryListExpense->TotalDez;
		}

		//Exibe valor total calculado
		$table .= "	<tr class = 'tableRow totalRow'>
						<td class = 'total'>Total</td>
						<td class = 'total'> ". 'R$ ' . number_format($totalJan,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalFev,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalMar,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalAbr,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalMai,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalJun,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalJul,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalAgo,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalSet,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalOut,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalNov,2,",",".")  ." </td>
						<td class = 'total'> ". 'R$ ' . number_format($totalDez,2,",",".")  ." </td>
					</tr>";	

		//Envia tabela como retorno, modifica o DOM apenas uma vez
		echo $table;		
?>