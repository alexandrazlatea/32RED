<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'player.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Utils.php';

class PromoManager {
    
    /**
     * Key of current promotion
     *
     * @var string
     */
    private $promoKey = null;
    
    /**
     * Current username
     *
     * @var string
     */
    private $username = null;

    /**
     * Current option
     *
     * @var string
     */
    private $option = null;
    
    /**
     * Current player object
     *
     * @var Player
     */
    private $player = null;
    
    /**
     * Constructor
     * 
     * @param string promo key to use
     * @param mixed username to load; set to "true" (boolean) for auto-detection
     */
    public function __construct($promoKey = null, $username = true) {
        if (!empty($promoKey)) {
            $this->promoKey = $promoKey;
        }

        if (empty($username)) {
                    // No auto username found
               $this->setUsername('');
            } else {
                $this->setUsername($username);
            }
        }
    
    static function promoData() {
        $promoDataFile = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'promotions.json';
        return json_decode(file_get_contents($promoDataFile), true);
         
    }
    /**
     * Returns the current player object
     * 
     * @return Player current player object
     */
    public function getPlayer() {
        return $this->player;
    }
    
    /**
     * Returns the current username
     * 
     * @return string current username
     */
    public function getUsername() {
        return $this->username;
    }
    
    /**
     * Sets the current username
     * This initializes the Player object
     */
    public function setUsername($value) {
        $this->username = $value;
        $this->player = new Player($this->username);
    }

     /**
     * Sets the current option
     */
    public function setOption($value) {
        $this->option = $value;
    }
    
    /**
     * Returns the current promotion key
     * 
     * @return string current promotion key
     */
    public function getPromoKey() {
        return $this->promoKey;
    }
    
    /**
     * Sets the current promotion key
     */
    public function setPromoKey($value) {
        $this->promoKey = $value;
    }
    
    /**
     * Helper function to ensure we have valid settings
     * 
     * @throws Exception if the settings aren't valid
     */
    private function requireValidSettings($optin = false) {
        if ($optin == true) {
            if (empty($_COOKIE['promo_username'])) return true;
        }    
        if (!$this->isValidUser()) {
            throw new Exception('Valid Username not set');
        }
        if (!$this->isValidPromo()) {
            throw new Exception('Valid promo not set');
        }
    }
    
    /**
     * Checks if the current user is valid
     * 
     * @return boolean
     */
    public function isValidUser($login = false) {
        if ($login == false) {
            if (empty($_COOKIE['promo_username'])) return true;
        }    
        $validateUser = $this->player->validateUser();
        if (empty($this->username) || ($this->player->isValid() == false)) {
            return false;
        }

        return true;
    }
    
    /**
     * Checks if the current promo is valid
     * 
     * @return boolean
     */
    public function isValidPromo() {
        $promoData = $this->getCurrentPromotion();
        if (empty($this->promoKey) && empty($promoData)) {
            return false;
        }
        return true;
    }

    public function getCurrentPromotion() {
         $promoData = PromoManager::promoData();
         for($i = 0; $i < count($promoData); $i++) {
            if ($promoData[$i]['id'] == $this->promoKey) {
                  return $promoData[$i]; 
            }
         }
         return;
    }
    
    /**
     * Checks if the current user is opted in to the current promo
     * 
     * @return boolean
     */
    public function isOptedIn() {
        $this->requireValidSettings();
        $currentPlayer = $this->player->getCurrentPlayer();
        return $this->player->hasTag($this->promoKey);
    }

    /**
     * Checks if the current user is on the blackList
     * 
     * @return boolean
     */
    public function isOnBlackList() {
        $this->requireValidSettings();
         return $this->player->isOnBlackList($this->promoKey);
    }


    
    /**
     * Checks if the current user is eligible for the current promo
     * For now just checks if the user is valid and not opted-in already
     * but this functionality could be extended.
     * 
     * @return boolean
     */
    public function isEligible() {
        $this->requireValidSettings();
        
        if ($this->isOptedIn() || $this->isOnBlackList()) return false;
        
        return true;
    }
    /**
     * Checks if the promotion is expired
     * 
     * @return boolean
     */
    public function isExpiredPromotion() {
         $promoData = $this->getCurrentPromotion();
         return Utils::compareDates($promoData['validTo']);
         
    }
    
    /**
     * Returns the promotion status for the current user in the current promo
     * 
     * @return string
     */
    public function getStatus() {
        
        if (!$this->isValidPromo()) {
            return 'error';
        }
        if ($this->isOptedIn()) {
            return 'You are registered to this promotion';
        }
         if ($this->isOnBlackList()) {
            return 'We are sorry but you are not able to access this promotion. You are on our black list.';
        }
        if ($this->isExpiredPromotion()) {
            return 'We are sorry, but the promotion is expired';
        }
        if (!$this->isValidUser()) {
            return 'no-user';
        }
        if ($this->isEligible()) {
            return 'eligible';
        }

        return 'showForm';

    }

    
    /**
     * Opts the current player in to the current promo
     * 
     * @return boolean success state
     */
    public function optIn() {
        $this->requireValidSettings(true);
        
        if (!$this->isEligible()) {
            return false;
        }
        $currentPlayer = $this->player->getCurrentPlayer();
        $this->player->addUserToPromotion($this->promoKey, $this->option);
        
        return true;
    }

    public function saveCookie($username) {
         setcookie('promo_username', $username, time()+60*60*24);
         return true;
    }
    
}