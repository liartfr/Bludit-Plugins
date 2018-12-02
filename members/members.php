<?php

// You have to add :
//		elseif ($WHERE_AM_I == 'members') {
//			include(THEME_DIR_PHP.'membres.php');
//	}
// into your index.php to have an access to this one.

?>

<?php
echo '<section id="membres">'.PHP_EOL;
if (pluginActivated('pluginMembers')){
    if ($s_member == true){
        if ($s_member_exist == true){ // we found the user and he accept to display his information :
            $user = new User($selected_member);

            ?>
            <div class="main">
                <div class="bio">
                    <img class="profile-img" src="<?php if ($user->profilePicture()){echo $user->profilePicture();}else{echo 'https://picsum.photos/200';} ?>"/>
                    <h3 class="header"><?php if ($user->nickname()){echo $user->nickname();} else {echo $selected_member;} ?></h3>
                    <p><?php echo $user->lastName()?> <?php echo $user->firstName() ?></p>
                </div>
                <div class="contact">
                    <?php 
                    echo '<ul class="user_social">';
                    foreach(Theme::socialNetworks() as $social => $label ) {
                        if ( $user->{$social}() ) {
                            echo '<li><i class="fab fa-'.$social.'"></i>  <a href="'.$user->$social().'" >'.$label.'</a></li>';
                        }
                    }
                    echo '</ul>';
                    ?>
                </div>
            </div>


            <?php
        }
        else{
            echo '<h1>'.$L->get('members').'</h1>';
            echo '<h3>'.$L->get('hidden_member').'</h3>'.PHP_EOL;
        }
    }
    else{
        echo '<h1>'.$L->get('all_members').'</h1>'.PHP_EOL;
    }
}
else
{
    echo '<h1>Acces forbidden or plugin Unactiv</h1>';
}
echo '</section>'.PHP_EOL;
?>
