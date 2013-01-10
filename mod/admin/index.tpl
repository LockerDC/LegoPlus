<?php include("thems/new_them/head.tpl"); ?>

        	<div class="title">
	     		<?php
	     		if(!isset($pg_data['lock']))
					echo $pg_data['page'];
				else
					echo 'Эта страница недоступна для неавторизированых пользователей<br>';
	     		?>
        	</div>
        <div class="in"><a href="/">Главная</a></div>
        <div class="in"><a href="/myadmin/">Админка</a></div>	
<?php include("thems/new_them/foot.tpl"); ?>
