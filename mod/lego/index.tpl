<?php
 $lg = new Lego();
 $conf = $lg->get_config();
  include("thems/".$conf['them']."/head.tpl"); 
 ?>
     		<header>
                <div id="logo">
                </div>
                <menu>
                	<li><a href="index">Главная</a></li>
                    <li><a href="news">Новости</a></li>
                    <li><a href="echo">Отзывы</a></li>
                   <li><a href="enter">Вход</a></li>
                </menu>
       		</header>
            
        	<section>
	     		<?php
	     		if(!isset($pg_data['lock']))
					echo $pg_data['page'];
				else
					echo 'Эта страница недоступна для неавторизированых пользователей<br>';
	     		?>
        	</section>
<?php include("thems/".$conf['them']."/foot.tpl"); ?>
