1 - To use the plugins, you have to add the members.php page into your {yourtheme}/php/ section.

2 - You have to add :
	elseif ($WHERE_AM_I == 'members') {
			include(THEME_DIR_PHP.'membres.php');
	}
  
  into your index.php page.
