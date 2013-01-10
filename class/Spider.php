<?php
// Version: 1.1;
/*
 *  Автор Xsikor
 *  ICQ: 382504928
 *  E-mail: xsikor@gmail.com
*/

/*
 * Клас для упрощеной работы с сокетами.
 * data($set, $value) задает значение переменным "cookie", "ua", "refer", "link", "post", "header"
 *      cookie - передача кук в запросе
 *      ua - Браузер, по умолчанию Mozilla/5.0 (Windows NT 6.1; rv:14.0) Gecko/20100101 Firefox/14.0.1
 *      refer - Передача реферера. По умолчанию такой же как и url перехода
 *      link - всё что идёт после домена. По умолчанию "/"
 *      post - заполнить если необходимо сделать post запрос.
 *      header - Может принимать значения (true, false, all) по умолчанию false
 *          true - Возврат заголовка ответа
 *          false - Возврат страницы без заголовка
 *          all - Возвращает заголовок запроса, ответа и страницу
 * 
 * 
 *  Go($url, $port = 80, $return = false) натравляет паучка на заданый адрес
 *      $url - домен куда он пойдёт
 *      $port - через какой порт будем стучаться(по умолчанию 80)
 *      $return - Возвращаем или не возвращаем ответ от сервера(По умлочанию не возвращаем)
 * 
 * 
 *  cookie($str) стравливаем в эту функцию строку заголовка, в ответ получаем все полученые кукисы от сервера
 *  href_parse($str) Возвращает url ссылки.
 * 
 * #######################Пример запроса к гуглу##########################
 * 
 * $sp = new Spider(); //Создаем объект класа
 * $sp->data("link", "/search?q=test"); //Задаем GET запрос
 * echo $sp->Go("www.google.com", 80, true) //Запускаем паучка на гугл и выводим результат
 * 
 */
class Spider
{
        private $url = '';
        private $method = 'GET';
        private $link = '';
        private $port = 80;
        private $refer = '';
        private $UA = '';
        private $cookie = '';
        private $header = "false";
        private $post = '';
        private $head = '';
        private $forward ='';
    function Spider()
    {
        #echo 'Spider start<br>';
    }
    
    function data($set, $value)
    {
        if($set == 'cookie')
            $this->cookie = $value;
        
        if($set == 'ua')
            $this->UA = $value;
            
        if($set == 'refer')
            $this->refer = $value;
            
        if($set == 'link')
            $this->link = $value;
        if($set == 'header')
            $this->header = $value;
            
        if($set == 'post')
            $this->post .= $value;
        if($set == 'forward')
            $this->forward = $value;
        #echo $set.' set ok<br>';
    }
    
    function Go($url, $port = 80, $return = true)
    {
        if($this->refer == '')
            $this->refer = $url;
        if($this->UA == '')
            $this->UA = 'Mozilla/5.0 (Windows NT 6.1; rv:14.0) Gecko/20100101 Firefox/14.0.1';
        if($this->link == '')
            $this->link = '/';
        if($this->post !='')
            $this->method = 'POST';
		if(strstr($url, "/"))
		{
			$url = str_replace("http://", "", $url);
			$url = str_replace("https://", "", $url);
			$this->link=strchr($url, "/");
			$url = str_replace($this->link, "", $url);
		}
        $page = '';
        if($fs = fsockopen($url, $port, $err,$errt, 2))
        {
            $msg =  $this->method.' '.$this->link.' HTTP/1.1'."\r\n".
                                                'Host: '.$url."\r\n".
                                                'User-Agent: '.$this->UA."\r\n";
                            if($this->forward!='')
                                $msg .= 'X-Forwarded-For:'.$this->forward."\r\n";
                                                $msg.='Accept: */*'."\r\n".
                                            'Connection: close'."\r\n".
                                            'Referer: '.$this->refer."\r\n";
                                if($this->cookie != '')
                                    $msg.='Cookie: '.$this->cookie."\r\n";
                                    ///Добавить возможность пост запрса
                                    if($this->post == '')
                                            $msg.='Content-type: text/html'."\r\n\r\n";
                                    else
                                    {
                                        $msg.='Content-type: application/x-www-form-urlencoded'."\r\n";
                                        $msg.='Content-Length: '.strlen($this->post)."\r\n\r\n".$this->post;
                                    }
            fwrite($fs, $msg);

                while (!feof($fs)) {
                $page .=fgets ($fs,128);
                }
                fclose($fs);
                preg_match("/HTTP(.*?)\r\n\r\n/is", $page, $res);
                $this->head = $res[0];
                if($this->header == "true")
                    return $res[0];
                elseif($this->header == "false")
                    return str_replace($res[0], '', $page);
                else
                    return $msg.'<hr>'.$page;
            fclose($fs);
        }
        else
        {
            return false;
        }
    }
    
    function cookie()
    {
        if(isset($this->head))
        {
            preg_match('/Set-Cookie:(.*?);/is', $this->head, $res);
            $return = $res[1];
        }
        else
            $return = false;
        
        return $return;
    } 
    
    function href_parse($str)
    {
        preg_match("/href=\"(.*?)\"/is", $str, $res);
        return $res[1];
    }
    
    function destroy()
    {
        $this->url = '';
        $this->method = 'GET';
        $this->link = '';
        $this->port = 80;
        $this->refer = '';
        $this->UA = '';
        $this->cookie = '';
        $this->header = "false";
        $this->post = '';
    }
    


}
        
        
        
/*
 *  Автор Xsikor
 *  ICQ: 382504928
 *  E-mail: xsikor@gmail.com
*/



?>
