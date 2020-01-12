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
      ?>
      <header id="fh5co-header" role="banner" class="fh5co-project js-fh5co-waypoint no-border" data-colorbg="#222222" data-next="yes">
        <div class="container">
          <div class="fh5co-text-wrap animate-box">
            <div class="fh5co-intro-text">
              <h1><?php echo  '<h1>'.$L->get('all_members').'</h1>'.PHP_EOL;?> </h1>
            </div>
          </div>
        </div>
        <div class="btn-next animate-box fh5co-learn-more">
          <a href="#" class="scroll-btn">
            <span>Voir la liste</span>
            <i class="icon-chevron-down"></i>
          </a>
        </div>
      </header>

      <div class="js-fh5co-waypoint fh5co-project-detail" id="fh5co-main" data-colorbg="">
        <div class="container">
        <div class="row row-bottom-padded-sm animate-box" id="2009/2019">
          <div class="col-md-3">
            <h3 class="fh5co-section-heading"><span class="fh5co-number">[B2P]</span>Les Membres</h3>
          </div>
          <div class="col-md-9">
            <ul class="fh5co-list-style-1 fh5co-inline">
            <?php


            global $users;
            $list = $users->keys();
            foreach ($list as $username) {
              $B2P = new User($username);
              ?>
                <li><a href="<?php echo $url->slug().'/'.$B2P->nickname(); ?>"><?php echo $B2P->nickname(); ?></li>

              <?php
            }
             ?>
           </ul>
         </div>
       </div>
     </div>
   </div>


        <?php
    }
}
else
{
    echo '<h1>Acces forbidden or plugin Unactiv</h1>';
}
echo '</section>'.PHP_EOL;
?>
