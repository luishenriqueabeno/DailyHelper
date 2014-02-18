<?php
	require('../../../php/conn.php');

	//Suprime warnings
	error_reporting(E_ERROR | E_PARSE);

	//Recebe dados para carregar receitas
	$ano = $_POST['ano'];
	$userId = $_POST['userId'];

	//Caso a variavel ano chegue vazia, é setado o ano atual
	if($ano == ''){
		$ano = date("Y");
	}

	//Através do id do usuário logado é verificado quem é o usuário master
	$getMaster = mysql_query("Select userMaster From users Where id = $userId");
	$resMaster = mysql_fetch_object($getMaster);

	//Query para retornar todas as receitas para o ano selecionado e que estão abaixo do usuário master
	$incomeList = mysql_query("Select * From cashflowincome Where ano = ". $ano ." And userMaster = '". $resMaster->userMaster ."'");

	echo "<tr>";
		echo "<th colspan = '13'> Receitas </th>";
	echo "</tr>";

	//Itera despesas
	while($resIncomeList = mysql_fetch_object($incomeList)){
		//Imprime receitas para cada mês
		echo "<tr class = 'tableRow' id = ". 'income_' . $resIncomeList->id .">";
			echo "<td class = 'incomeTitle' title = ".str_replace(' ', '_', $resIncomeList->incomeName).">". ((($resIncomeList->categoryId) == '0') ? " <img src = 'modules/cashFlow/resources/images/alert.png' title = 'Não há categoria associada'> " : " "). $resIncomeList->incomeName ."</td>";
			echo "<td class = 'jan'>". 'R$ ' . number_format($resIncomeList->jan,2,",",".") ."</td>";
			echo "<td class = 'fev'>". 'R$ ' . number_format($resIncomeList->fev,2,",",".") ."</td>";
			echo "<td class = 'mar'>". 'R$ ' . number_format($resIncomeList->mar,2,",",".") ."</td>";
			echo "<td class = 'abr'>". 'R$ ' . number_format($resIncomeList->abr,2,",",".") ."</td>";
			echo "<td class = 'mai'>". 'R$ ' . number_format($resIncomeList->mai,2,",",".") ."</td>";
			echo "<td class = 'jun'>". 'R$ ' . number_format($resIncomeList->jun,2,",",".") ."</td>";
			echo "<td class = 'jul'>". 'R$ ' . number_format($resIncomeList->jul,2,",",".") ."</td>";
			echo "<td class = 'ago'>". 'R$ ' . number_format($resIncomeList->ago,2,",",".") ."</td>";
			echo "<td class = 'set'>". 'R$ ' . number_format($resIncomeList->set,2,",",".") ."</td>";
			echo "<td class = 'out'>". 'R$ ' . number_format($resIncomeList->out,2,",",".") ."</td>";
			echo "<td class = 'nov'>". 'R$ ' . number_format($resIncomeList->nov,2,",",".") ."</td>";
			echo "<td class = 'dez'>". 'R$ ' . number_format($resIncomeList->dez,2,",",".") ."</td>";
		echo "</tr>";

		//Calcula valor total de receitas para cada mês
		$totalJan = $totalJan + $resIncomeList->jan;
		$totalFev = $totalFev + $resIncomeList->fev;
		$totalMar = $totalMar + $resIncomeList->mar;
		$totalAbr = $totalAbr + $resIncomeList->abr;
		$totalMai = $totalMai + $resIncomeList->mai;
		$totalJun = $totalJun + $resIncomeList->jun;
		$totalJul = $totalJul + $resIncomeList->jul;
		$totalAgo = $totalAgo + $resIncomeList->ago;
		$totalSet = $totalSet + $resIncomeList->set;
		$totalOut = $totalOut + $resIncomeList->out;
		$totalNov = $totalNov + $resIncomeList->nov;
		$totalDez = $totalDez + $resIncomeList->dez;
	}

	//Imprime valor total de receitas
	echo "<tr class = 'tableRow totalRow'>";
		echo "<td class = 'total'>Total</td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalJan,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalFev,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalMar,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalAbr,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalMai,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalJun,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalJul,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalAgo,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalSet,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalOut,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalNov,2,",",".")  ." </td>";
		echo "<td class = 'total'> ". 'R$ ' . number_format($totalDez,2,",",".")  ." </td>";
	echo "</tr>";					
?>