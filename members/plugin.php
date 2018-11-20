<?php
class pluginMembers extends Plugin {
    private $username=null;
				private $Nb_members=null;
				private $var1=null;
				private $var2=null;
    public function init()
    {

							// We collect the list of members
							global $users;
							$list = $users->keys();
					
					
							// Fields and default values for the database of this plugin
							// For Privacy reason (like RGPD), All value start with "false" attribut.
							$var1 = array(
											'username'=>false,
											'nickname'=>false,
											'last-name'=>false,
											'first-name'=>false,
											'role'=>false,
											'email'=>false,
											'twitter'=>false,
											'facebook'=>false,
											'googlePlus'=>false,
											'instagram'=>false,
											'codepen'=>false,
											'linkedin'=>false,
											'github'=>false,
											'gitlab'=>false
							);
					
					
							foreach ($list as $username) {
										try {
											$user = new User($username);
											if (empty($var2)){
												$var2 = array($username => false);
											}
											else{
												$var2 = array_merge($var2, array($username => false));
											}
											echo $username;

										} catch (Exception $e) {
													// Continue
									}
							} 

							$this->dbFields = array_merge($var1,$var2);
							// We generate the new value "Visible" into JSON File.
							//$this->visibility();
    }  //end of INIT

	
    public function beforeAll()
    {
        // Check if the URL match with the webhook
        $webhook = 'members';
        if ($this->webhook($webhook, false, false)) {
            global $site;
            global $url;
            $url->setWhereAmI($webhook); // THE CORRECT WEBHOOK !!!

            // Get username from URI
            $this->username = $this->webhook($webhook, true, false);
            $this->username = trim($this->username, '/');

            if (empty($this->username)) {
                var_dump('Without username defined');
            } else {
                var_dump('Username defined: '.$this->username);
            }

        }
    }

    public function siteBodyBegin()
    {
        $webhook = 'members';
        if ($this->webhook($webhook, false, false)) {
            global $url;
            global $WHERE_AM_I;
            $url->setWhereAmI($webhook);
            // Get the pre-defined variable from the rule 69.pages.php
            // We change the content to show in the website

            if (empty($this->username)) {

                exit('Without username defined');
            } else {
                var_dump('Username defined: '.$this->username);
            }
        } //endif webhook
    } // End siteBodyBegin



    public function form()
    {
        global $L; //language
								global $users; // Users
								$list = $users->keys(); // we list the users
								$Nb_members = count($users->keys()); // We count the user
								$nB_dBEntry = count($this->dbFields ); // We count the dB Entry
					
								// Light customized form
								$html = '<style>.col-md-4{text-transform:uppercase;}.col-md-4 label{border-bottom:1px dotted grey;width:200px;}.col-md-4 select{height:30px;width:200px;text-transform: uppercase;}';
								$html .= '.col-md-4 label{ }';
								$html .= '</style>';
					
        $html  .= '<div class="alert alert-primary" role="alert">'.PHP_EOL;
        $html .= $this->description();
        $html .= '</div>'.PHP_EOL;
					
								// First part : what you want to see ?
        $html .= '<div style="border:1px solid #ccc;padding:10px;border-radius:5px;">'.PHP_EOL;
        $html .= '<h5 style="text-decoration:underline;">'.$L->get('what_you_want_to_see').'</h5>'.PHP_EOL;
								$html .= '<div class="row">';
					
								
        foreach ($this->dbFields as $key => $value) {
            try {
															$html .= '<div class="col-md-4">';
															$html .= '<label>'.$L->get($key).' :</label>';
															$html .= '<select name="'.$key.'">';
															$html .= '<option value="true" '.($this->getValue($key)===true?'selected':'').'>'.$L->get('yes').'</option>';
															$html .= '<option value="false" '.($this->getValue($key)===false?'selected':'').'>'.$L->get('no').'</option>';
															$html .= '</select>';
															$html .= '</div>'.PHP_EOL;
												} catch (Exception $e) {
              // Continue
            }
									
										// we stop before the Member list
										if (--$nB_dBEntry <= $Nb_members) {
											break;
										}
									
        } //foreach
								$html .= '</div>';
        $html .= '</div>'.PHP_EOL;
			
								// Second part : Who do you want to see ?
								$html .= '<div style="border:1px solid #ccc;padding:10px;border-radius:5px;">'.PHP_EOL;
        $html .= '<h5 style="text-decoration:underline;">'.$L->get('who_do_you_want_to_see').' </h5>'.PHP_EOL;
								$html .= '<div class="row">';
								
								// We make a search again in dbField but only for the last entry on the array.
								// Members only.
        foreach (array_slice($this->dbFields, -$Nb_members, $Nb_members) as $key => $value) {
									try {
												$html .= '<div class="col-md-4">'.PHP_EOL;
												$html .= '<label>'.$key.'</label>'.PHP_EOL;
												$html .= '<select name="'.$key.'">'.PHP_EOL;
												$html .= '<option value="1" '.($this->getValue($key)===true?'selected':'').'>'.$L->get('yes').'</option>'.PHP_EOL;
												$html .= '<option value="0" '.($this->getValue($key)===false?'selected':'').'>'.$L->get('no').'</option>'.PHP_EOL;
												$html .= '</select>'.PHP_EOL;
												$html .= '</div>'.PHP_EOL;
														} catch (Exception $e) {
                // Continue
            }
        } //foreach username
					
								$html .= '</div>';
        $html .= '</div>'.PHP_EOL;
					
        return $html;
    } // End form

 
	
	

	
	
		


} //end plugin
