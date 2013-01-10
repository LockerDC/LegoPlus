<?php
/*
 * text(имя, значение) - текстовое поле
 * submit(Значение) - Кнопка submit
 * select(имя) - Поле выбора select (Перед ним обязательно задать данные через fopt(значение, имя)!
 * out(метод, направление) - Вывод формы
 * hidden(имя, значение) - Скрытое поле
 * textarea(Имя, значение) - textarea
 */
class html_form
{
	var $text = array();
	var $option = array();
	function text($name, $value='')
	{
		$this->text[] ='<input type="text" name="'.$name.'" value="'.$value."\"><br>";
	}
	
	function submit($value="Отправить")
	{
		$this->text[] = '<input type="submit" value="'.$value.'"><br>';
	}
	
	function option($value, $text)
	{
		$this->option[] = '<option value="'.$value.'">'.$text.'</option>';
	}
	
	function select($name)
	{
		$this->text[] = '<select name="'.$name.'">'.join("\r\n", $this->option).'</select><br>';
		unset($this->option);
	}
	
	function hidden($name, $value='')
	{
		$this -> text[] = '<input type="hidden" value="'.$value.'" name="'.$name.'"><br>';
	}
	
	function out($method, $action='?', $file=false)
	{
		if($file==false) echo '<form method="'.$method.'" action="'.$action.'">'."\r\n".join("\r\n", $this->text)."</form>";
        else echo '<form method="'.$method.'" action="'.$action.'" enctype="multipart/form-data">'."\r\n".join("\r\n", $this->text)."</form>";
		unset($this->text);
		unset($this->option);
	}
    
    function textarea($name, $value='', $row = 5, $kol = 30)
    {
        $this -> text[] = '<textarea name="'.$name.'" rows="'.$row.'" cols="'.$kol.'">'.$value.'</textarea><br>';
    }
    
    function file($name)
    {
        $this-> text[] = "<input type=\"file\" name=\"".$name."\"><br>";
    }
    
    function about($text, $br=true)
    {
        $this -> text[] = $text;
        if($br==true)
        {
            $this -> text[] = '<br>';
        }
    }
}
?>
