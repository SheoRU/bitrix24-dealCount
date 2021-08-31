<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>DealCount</title>
	<style>
		.container{
			text-align: center;
			font-size: 30px;
			height:500px;
			line-height:500px;
		}
	</style>
</head>
<body>
	<div class="container">
	<?php
		//Метод к которому обращаемся c помощью API
		$method = "crm.deal.list";

		//Получаем текущий год и первый и последнйи день предыдущего месяца
		$firstDayPrevMonth = date("Y-m-d", strtotime("first day of previous month"));
		$lastDayPrevMonth = date("Y-m-d", strtotime("last day of previous month"));

		//Параметры для фильтрации
		$params = [
			'filter' => ['STAGE_ID' => 'NEW',
			'>=BEGINDATE' => $firstDayPrevMonth,
			'<=BEGINDATE' => $lastDayPrevMonth,
			],
		];

		//Отправляем запрос к API
		$queryUrl = 'https://'.$_REQUEST['DOMAIN'].'/rest/'.$method.'.json';
			
		$queryData = http_build_query(array_merge($params,array(
			"auth" => $_REQUEST['AUTH_ID']
		)));

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryData,
		));

		$result = json_decode(curl_exec($curl), true);
		curl_close($curl);
		
		$dealCount = count($result['result']);
		echo "<span style='font-weight: bold;'>Количество новых сделок за предыдущий месяц: </span>".$dealCount
	?>
	</div>
</body>
</html>
