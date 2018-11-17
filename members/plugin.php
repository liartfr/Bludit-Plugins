<?php
class pluginMembers extends Plugin {
    private $username=null;

    public function init()
    {
        // Fields and default values for the database of this plugin
        $this->dbFields = array(
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
    }

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
        global $L;
        $html  = '<div class="alert alert-primary" role="alert">'.PHP_EOL;
        $html .= $this->description();
        $html .= '</div>'.PHP_EOL;
        $html .= '<div style="border:1px solid #ccc;padding:10px;border-radius:5px;">'.PHP_EOL;
        $html .= '<h5 style="text-decoration:underline;">'.$L->get('what_you_want_to_see').'</h5>'.PHP_EOL;

        foreach ($this->dbFields as $key => $value) {
            try {
                if ($this->getValue($key) == true){$returnvalue = true;echo " hey dude ";}
                $html .=  Bootstrap::formCheckbox(array(
                    'name'=> $key,
                    'label'=>'',
                    'labelForCheckbox'=>$L->g($key),
                    'placeholder'=>'',
                    'checked'=> $returnvalue,
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
