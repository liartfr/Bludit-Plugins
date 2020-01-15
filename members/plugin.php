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
	foreach ($list as $nickname) {
		try {
		$user = new User($nickname);
		if (empty($this->dbFields)){
			$this->dbFields = array($nickname => false);
		} else {
			$this->dbFields = array_merge($this->dbFields, array($nickname => false));
		}
		} catch (Exception $e) {
		// Continue
		}
	} // End of foreach
}  //end of INIT


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
	foreach ($list as $nickname) {
		try {
			$user = new User($nickname);
			$html .= '<div class="col-md-4">'.PHP_EOL;
			$html .= '<label>'.$nickname.'</label>'.PHP_EOL;
			$html .= '<select name="'.$nickname.'">'.PHP_EOL;
			$html .= '<option value="1" '.($this->getValue($nickname)===true?'selected':'').'>'.$L->get('display').'</option>'.PHP_EOL;
			$html .= '<option value="0" '.($this->getValue($nickname)===false?'selected':'').'>'.$L->get('hide').'</option>'.PHP_EOL;
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



public function beforeAll()
{
	//var & paramèters
	global $url;
	global $s_member;
	$webhook = 'membres';

	// Check if the URL match with the webhook
	if ($this->webhook($webhook, false, false)) {
		$url->setWhereAmI($webhook); // THE CORRECT WEBHOOK !!!
		// Get the string to search from the URL
		$s_member = $this->webhook($webhook, true, false);
		$s_member = trim($s_member, '/');
	}
}


public function pageBegin()
{
	// We collect the data from $s_member;
	global $s_member;

	if ($s_member == null){
		// No selected member, we put the list of all the members
		echo 'personne !';
	}
	else{

		// if we found the user in the member plugins.
		if (array_key_exists($s_member, $this->dbFields)) {
			// we check if the user wants to display his data
			if ($this->getValue($s_member) === true){
				echo $s_member.' affiche ses données';
			}
			else // if not
			{
				echo $s_member.' n\'affiche pas ses données.';
			}


		}else{ // we don't found the selected user.

				echo 'erreur not found';



			}





	}

} // end pagebegin



} //end plugin
