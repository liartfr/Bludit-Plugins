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
	
	

	
	
	
	public function pageBegin(){
				//if ($this->getValue('enable')) {
			//exit( $this->getValue('message') );
		//}
		
	}
	
	
	
	
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
	
	
	
	// HTML for sidebar
	public function siteSidebar()
	{
		global $L;
		$html  = '<div class="plugin plugin-search">';
		$html .= '<h2 class="plugin-label">'.$this->getValue('label').'</h2>';
		$html .= '<div class="plugin-content">';
		$html .= '<input type="text" id="plugin-search-input" /> ';
		$html .= '<input type="button" value="'.$L->get('Search').'" onClick="javascript: window.open(\''.DOMAIN_BASE.'search/\' + document.getElementById(\'plugin-search-input\').value, \'_self\');" />';
		$html .= '</div>';
 		$html .= '</div>';
		return $html;
	}
	public function install($position=0)
	{
		parent::install($position);
		return $this->createCache();
	}
	// Method called when the user click on button save in the settings of the plugin
	public function post()
	{
		parent::post();
		$this->createCache();
	}
	public function afterPageCreate()
	{
		$this->createCache();
	}
	public function afterPageModify()
	{
		$this->createCache();
	}
	public function afterPageDelete()
	{
		$this->createCache();
	}

	public function paginator()
	{
		$webhook = 'members';
		if ($this->webhook($webhook, false, false)) {
			// Get the pre-defined variable from the rule 99.paginator.php
			// Is necessary to change this variable to fit the paginator with the result from the search
			global $numberOfItems;
			$numberOfItems = $this->numberOfItems;
		}
	}

	// Generate the cache file
	// This function is necessary to call it when you create, edit or remove content
	private function createCache()
	{
		// Get all pages published
		global $pages;
		$list = $pages->getList($pageNumber = 1, $numberOfItems = -1, $onlyPublished = true);
		$cache = array();
		foreach ($list as $pageKey) {
			$page = buildPage($pageKey);
			// Process content
			$words = $this->getValue('wordsToCachePerPage') * 5; // Asumming avg of characters per word is 5
			$content = $page->content();
			$content = Text::removeHTMLTags($content);
			$content = Text::truncate($content, $words, '');
			// Include the page to the cache
			$cache[$pageKey]['title'] = $page->title();
			$cache[$pageKey]['description'] = $page->description();
			$cache[$pageKey]['content'] = $content;
			$cache[$pageKey]['key'] = $pageKey;
		}
		// Generate JSON file with the cache
		$json = json_encode($cache);
		return file_put_contents($this->cacheFile(), $json, LOCK_EX);
	}
	// Returns the absolute path of the cache file
	private function cacheFile()
	{
		return $this->workspace().'cache.json';
	}
	// Search text inside the cache
	// Returns an array with the pages keys related to the text
	// The array is sorted by score
	private function search($text)
	{
		// Read the cache file
		$json = file_get_contents($this->cacheFile());
		$cache = json_decode($json, true);
		$found = array();
		foreach ($cache as $page) {
			$score = 0;
			if (Text::stringContains($page['title'], $text, false)) {
				$score += 10;
			}
			if (Text::stringContains($page['description'], $text, false)) {
				$score += 7;
			}
			if (Text::stringContains($page['content'], $text, false)) {
				$score += 5;
			}
			if ($score>0) {
				$found[$page['key']] = $score;
			}
		}
		// Sort array by the score, from max to min
		arsort($found);
		// Returns only the keys of the array contained the page key
		return array_keys($found);
	}
}
