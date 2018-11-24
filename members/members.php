<?php

// You have to add :
//		elseif ($WHERE_AM_I == 'members') {
//			include(THEME_DIR_PHP.'membres.php');
//	}
// into your index.php to have an access to this one.

?>

<?php
echo '<section>'.PHP_EOL;
if (pluginActivated('pluginMembers')){
	if (!empty($s_member)){

		echo '<h1>'.$s_member.'</h1>'.PHP_EOL;

	}
	else{
		echo '<h1>'.$L->get('all_members').'</h1>'.PHP_EOL;
	}
}
else{echo '<h1>Acces forbidden or plugin Unactiv</h1>';}
echo '</section>'.PHP_EOL;
?>
