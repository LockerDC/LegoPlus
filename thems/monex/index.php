<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>monex</title>
<link rel="stylesheet" href="styles.css" type="text/css" />
</head>

<body>
    		<header>
                <div id="logo">
                </div>
                <menu>
                		<li><a href="#">Главная</a></li>
                    	<li><a href="#">Новости</a></li>
                    	<li><a href="#">Отзывы</a></li>
                    	<li><a href="#">Вход</a></li>
                </menu>
       		</header>
            
        	<section>
           		<?php
					require_once 'php/login.php';
					$db_server = mysql_connect($db_hostname, $db_username, $db_password); //Подключение к серверу
					
					if (!$db_server) echo("Невозможно соеденится с сервером: " . mysql_error());
					if ($db_server) echo("Удачное подключение");
					
					mysql_select_db($db_database) or die("Невозможно выбрать базу " . mysql_error()); //Выбор базы данных
					
					echo "<br />";

					$query = "SELECT * FROM users";
					$result = mysql_query($query); //Отправка запроса на сервер
					if (!$result) die ("Сбой при доступе к базе данных ". mysql_error());
					
					$rows = mysql_num_rows($result); //Получение количества строк в ответе сервера
					for ($j = 0; $j < $rows; ++$j)
					{
						/*
						echo 'id: '.mysql_result($result, $j, 'id') . '<br />';
						echo 'username: '.mysql_result($result, $j, 'username') . '<br />';
						echo 'password: '.mysql_result($result, $j, 'password') . '<br />';
						*/
						$row = mysql_fetch_row($result);
						echo 'Id: '.$row[0].'<br />';
						echo 'Username: '.$row[1].'<br />';
						echo 'Password: '.$row[2].'<br />';
					}
						 
					

				?>
            </section>
            
    		<footer>
        		ЗДЕСЬ ФУТЕР!
    		</footer>
</body>
</html>
