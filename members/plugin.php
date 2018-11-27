<?php
class pluginMembers extends Plugin {
private $s_member=null;
public function init()
{
	// We collect the list of members
	// We put all value in a array for visibility of the user.
	// For Privacy reason (like RGPD), All value start with "false" attribut.
	global $users;
	$list = $users->keys();
	foreach ($list as $username) {
		try {
		$user = new User($username);
		if (empty($this->dbFields)){
			$this->dbFields = array($username => false);
		} else {
			$this->dbFields = array_merge($this->dbFields, array($username => false));
		}
		} catch (Exception $e) {
		// Continue
		}
	} // End of foreach

}  //end of INIT


public function beforeAll()
{
	// Check if the URL match with the webhook
	$webhook = 'members';
	if ($this->webhook($webhook, false, false)) {
	global $site;
	global $url;
	global $s_member;
	$url->setWhereAmI($webhook); // THE CORRECT WEBHOOK !!!

		// Get username from URI
		$this->username = $this->webhook($webhook, true, false);
		$this->username = trim($this->username, '/');

	if (empty($this->username)) {
		$s_member = false;   
	//var_dump('Without username defined');
	} else {
		$s_member = $this->username;     
	//var_dump('Username defined: '.$this->username);
	}

	}
}

public function siteBodyBegin()
{
	$webhook = 'members';
	if ($this->webhook($webhook, false, false)) {
		global $url;
		global $WHERE_AM_I;
		global $s_member;
		$url->setWhereAmI($webhook);
		// Get the pre-defined variable from the rule 69.pages.php
		// We change the content to show in the website

		if (empty($this->username)) {
			$s_member = false;   
			//exit('Without username defined');
		} else {
			$s_member = true;  
			if (array_key_exists($this->username, $this->dbFields)) {
					$s_member_exist = true;
					$selected_member = $this->username;
			} else{
				$s_member_exist = false;
				$selected_member = $this->username; //debug
			}
		//var_dump('Username defined: '.$this->username);
		}
	} //endif webhook
} // End siteBodyBegin



public function form()
{
	global $L;
	global $users;
	$list = $users->keys();

	// Light customized form
	$html = '<style>.col-md-4{text-transform:uppercase;}.col-md-4 label{border-bottom:1px dotted grey;width:200px;}.col-md-4 select{height:30px;width:200px;text-transform: uppercase;}';
	$html .= '.col-md-4 label{ }';
	$html .= '</style>'.PHP_EOL;

	$html  .= '<div class="alert alert-primary" role="alert">'.PHP_EOL;
	$html .= $this->description();
	$html .= '</div>'.PHP_EOL;


	// Who do you want to see ?
	$html .= '<div style="border:1px solid #ccc;padding:10px;border-radius:5px;">'.PHP_EOL;
	$html .= '<h5 style="text-decoration:underline;">'.$L->get('who_do_you_want_to_see').' </h5>'.PHP_EOL;
	$html .= '<div class="row">';

	$list = $users->keys();
	foreach ($list as $username) {
		try {
			$user = new User($username);
			$html .= '<div class="col-md-4">'.PHP_EOL;
			$html .= '<label>'.$username.'</label>'.PHP_EOL;
			$html .= '<select name="'.$username.'">'.PHP_EOL;
			$html .= '<option value="1" '.($this->getValue($username)===true?'selected':'').'>'.$L->get('display').'</option>'.PHP_EOL;
			$html .= '<option value="0" '.($this->getValue($username)===false?'selected':'').'>'.$L->get('hide').'</option>'.PHP_EOL;
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
