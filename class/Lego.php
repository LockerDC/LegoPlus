<?php
###############Основной клас CMS
class Lego
{
	##########Конструктор##########
	function Lego()
	{
		
	}
	#######Подключаемся к БД###########
	function data_base()
	{
		include("conf.php");
		mysql_connect($db_host, $db_login, $db_pass) or die("Не могу подключится к серверу. Проверьте логин и пароль");
		mysql_select_db("legocms") or die("Не могу выбрать базу, она точно создана?");
	}
    #######Конфиги####################
	function get_config()
	{
		return mysql_fetch_array(mysql_query("SELECT * FROM `config`"));
	}
	#########Функция для определения новых модулей#############
	function new_mod()
	{
		$dir = opendir("mod");
		while($r = readdir($dir))
		{
			if($r != "." && $r != ".." && file_exists("mod/".$r."/mod.inf"))
			{
				$inf = file_get_contents("mod/".$r."/mod.inf");
				preg_match_all("/\{(.*?)=(.*?)\}/is", $inf, $res);
				$i=0;
				$data = Array();
				foreach($res[1] as $key)
				{
					$data[$key] = $res[2][$i];
					$i++;
				}
				$q=mysql_query("SELECT * FROM `mod` WHERE `name` = '".$data['name']."'");
				if(mysql_num_rows($q)==0)
				{
					mysql_query("INSERT INTO `mod` SET `name` = '".$data['name']."', `dir` = '".$r."', `url` = '".$data['url']."'");
				}
				else
				{
					$r = mysql_fetch_array($q);
					mysql_query("UPDATE `mod` SET `name` = '".$data['name']."', `dir` = '".$r['dir']."',`url` = '".$data['url']."' WHERE `name` = '".$r['name']."'");
				}
			}
		}
		closedir($dir);
	}
	###############Чекаем модули|Удаляем старые########
	function check_mod()
	{
		$q = mysql_query("SELECT `dir` FROM `mod`");
		while($r = mysql_fetch_array($q))
		{
			if(!file_exists("mod/".$r['dir']))
			{
				mysql_query("DELETE FROM `mod` WHERE `dir` = '".$r['dir']."'");
			}
		}
	}
	############Считываем URL и подключаем модуль##############
	function mod_inc()
	{
		$url = explode("/", $_SERVER['REQUEST_URI']);
		if($url[1] == "") $url[1] = "/";
		//exit (mysql_escape_string(str_replace("/", "",$url[1])));
		$q=mysql_query("SELECT `dir` FROM `mod` WHERE `url`='".mysql_escape_string(str_replace("/", "",$url[1]))."'") or die(mysql_error());
		$url[2] = str_replace(strstr($url[2], "?"), "", $url[2]);
		if(!isset($url[2]) || $url[2] == '')
			$url[2]="index";
		if(mysql_num_rows($q) >0)
		{
			$tamplate_inc = false;
			$r_Lego = mysql_fetch_array($q);
			unset($url[1]);
			$url = join("/", $url);
			include("mod/lego/mod_rule.php");
			#####################Правила модуля(php код)#########################
			if(file_exists("mod/".$r_Lego['dir']."/mod_rule.php"))
			{
				$tamplate_inc = true;
				include("mod/".$r_Lego['dir']."/mod_rule.php");
			}
			################Собираем страницу с исходников################
			if(file_exists("mod/".$r_Lego['dir'].$url.".src"))
			{
				$pg_data = $this->parse_src("mod/".$r_Lego['dir'].$url.".src");
			}
			else
			{
				$pg_data = $this->parse_src("mod/lego/index.src");
			}
			##############//////////#################
			
			#################Инклудим ТПЛ, если его нету в модуле тогда инклудим стандартный#########################
			if(file_exists("mod/".$r_Lego['dir']."/index.tpl"))
			{
				include("mod/".$r_Lego['dir']."/index.tpl");
			}
			else
			{
				include("mod/lego/index.tpl");
			}
			###########################/////////////////##############
		}
		else
		{
			header("Location: /main/");
		}
	}
	###########URL Import параметры(УРЛ, папка куда сохранить, имя файла)###############
	function url_import($url, $dir, $name)
	{
		if($r = file_get_contents($url))
		{
			if($f = fopen($dir.'/'.$name, "w"))
			{
				fwrite($f, $r);
				fclose($f);	
			}
			else
				return false;
			return true;
		}
		else
			return false;
	}
	#######Собираем данные с page.src##################
	function parse_src($file)
	{
		ob_start();
		include($file);
		$page = ob_get_clean();
		
		preg_match_all("/\{(.*?)\}(.*?)\{\/(.*?)\}/is", $page, $res);
		$i=0;
		foreach($res[1] as $key)
		{
			$data[$key] = $res[2][$i];
			$i++;
		}
		return $data;
	}
	##########Проверка авторизации####################	##########Авторизация#############
	function auth($login='', $password='')
	{
		if($login != '' && $password!='')
		{
			$q = mysql_query("SELECT * FROM `users` WHERE `login` = '".mysql_escape_string($login)."' AND `password` = '".mysql_escape_string($password)."'");
			if(mysql_num_rows($q)>0)
			{
				$_SESSION['login'] = $login;
				$_SESSION['password'] = $password;
				return true;
			}
			else
			{
				return false;
			}
		}
		if(isset($_SESSION['login']) && isset($_SESSION['password']))
		{
			$q = mysql_query("SELECT * FROM `users` WHERE `login` = '".mysql_escape_string($_SESSION['login'])."' AND `password` = '".mysql_escape_string($_SESSION['password'])."'");
			if(mysql_num_rows($q)>0)
			{
				return $q;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	########Получаем уровень доступа###############
	function get_level($query)
	{
		$query = mysql_fetch_array($query);
		return $query['access'];
	}
}
?>
