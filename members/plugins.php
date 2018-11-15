<?php
class pluginmembers extends Plugin {
	public function init()
	{
		// Fields and default values for the database of this plugin
		$this->dbFields = array(
			'username'=>true,
			'nickname'=>true,
			'last-Name'=>true,
			'first-Name'=>true,
			'role'=>true,
			'email'=>true,
			'twitter'=>true,
			'facebook'=>true,
			'googlePlus'=>true,
			'instagram'=>true,
			'codepen'=>true,
			'linkedin'=>true,
			'github'=>true,
			'gitlab'=>true
		);
	}
	
		public function beforeAll()
	{
		// Check if the URL match with the webhook
		$webhook = 'members';
		if ($this->webhook($webhook, true, false)) {
			global $site;
			global $url;
			$url->setWhereAmI($webhook); // THE CORRECT WEBHOOK !!!
			
			// We search if the URL try to found a specific user.
			$stringToSearch = $this->webhook($webhook, true, false);
			$stringToSearch = trim($stringToSearch, '/');

			// IF we found specific user (s_member) :
			global $s_member;
			if (!empty($stringToSearch)) {
				// we defined $s_member on true
				$s_member = true;
			}
				else {
				// else on False
				$s_member = false;
			}
			
		}
	}
	
		public function siteBodyBegin()
	{
		$webhook = 'members';
		if ($this->webhook($webhook, true, false)) {
			global $url;
			global $WHERE_AM_I;
			$url->setWhereAmI($webhook); 
			// Get the pre-defined variable from the rule 69.pages.php
			// We change the content to show in the website

			global $users;
			$list = $users->keys();
			foreach ($list as $username) {
				try {
					$user = new User($username);
					// We will select only the information about the user we want to :		

					print_r($user);
					
				} catch (Exception $e) {
					// Continue
				}
			}

			
			
			
			} //endif webhook
		} // End siteBodyBegin
	
	

	public function form()
	{
		global $L;
		global $users;
		
		$html  = '<div class="alert alert-primary" role="alert">'.PHP_EOL;
		$html .= $this->description();
		$html .= '</div>'.PHP_EOL;
		$html .= '<div style="border:1px solid #ccc;padding:10px;border-radius:5px;">'.PHP_EOL;
		$html .= '<h5 style="text-decoration:underline;">'.$L->get('what_you_want_to_see').'</h5>'.PHP_EOL;
	
		foreach ($this->dbFields as $key => $value) {
			try {
				
				$html .=  Bootstrap::formCheckbox(array(
					'name'=> $key,
					'label'=>'',
					'labelForCheckbox'=>$L->g($key),
					'placeholder'=>'',
					'checked'=> $value,
					'tip'=>''
				)).PHP_EOL;
					} catch (Exception $e) {
				// Continue
			}
		}
		
		$html .= '</div>'.PHP_EOL;
		return $html;
	}
	
	


}
