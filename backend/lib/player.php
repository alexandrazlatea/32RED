<?php

class Player {

    /**
     * Path of file used for storing players and their tags
     * Placeholder functionality only
     *
     * @var string
     */
    private static $dataFileName;
    
    /**
     * Current username
     *
     * @var string
     */
    private $username = null;
    
    /**
     * Is the current user valid?
     *
     * @var boolean
     */
    private $valid = false;
    
    /**
     * Current user's tags
     *
     * @var array
     */
    private $tags = array();
    
    /**
     * Initialize static variables
     */
     
    public static function init() {
        self::$dataFileName = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'playertags.json';
    }
    
    /**
     * Constructor
     * 
     * @param string username
     */
    public function __construct($username = null) {
        if ($username !== null) {
            $this->setUsername($username);
        }
    }
    
    /**
     * Sets the current username
     * This initializes the Player object
     */
    public function setUsername($value) {
        $this->username = $value;
        $this->loadTags();
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
     * Returns a boolean value indicating if the current user is valid
     * 
     * @return boolean
     */
    public function isValid() {
        return $this->valid;
    }
    
    /**
     * Gets an array of all of the current user's tags
     * 
     * @return array
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * Returns the current valid
     * 
     * @return string current valid
     */
    public function getValid() {
        return $this->valid;
    }
     /**
     * Set the c valid
     * 
     * @return string current valid
     */
    public function setValid($valid) {
        $this->valid = $valid;
    }
    
    /**
     * Allows checking if the current user has a specific tag
     * 
     * @param string $tag The tag to check
     * @return boolean
     */
    public function hasTag($promoKey) {    
         $currentPlayer = $this->getCurrentPlayer();
         $optinArray = explode(",", $currentPlayer['optin']);
         $promoKeyOption = $promoKey.':';
         foreach ($optinArray as $value) {
             if (($value == $promoKey) || (strstr($value, $promoKeyOption) == true)) {
                return true;
             }
         }
         return false;
    }
    /**
     * Allows checking if the current user is in black list
     * 
     * @param string $tag The tag to check
     * @return boolean
     */
    public function isOnBlackList($promoKey) {
        $currentPlayer = $this->getCurrentPlayer();
        if ($currentPlayer != null) {
            if (array_key_exists('blackList', $currentPlayer)) {
                if ($currentPlayer['blackList'] == true) return true;
            }
        }    
        return false;

    }
    
    /**
     * Adds the specified tag to the current user
     * 
     * @param string $tag The tag to add
     * @return boolean
     */
    public function addTag($tag) {
        if (!$this->hasTag($tag)) {
            $this->tags[] = $tag;
            $this->saveTags();
        }
    }
     /**
     * @param id of the promotion
     * @param the option the the user has selected
     * 
     * @return boolean
     */
    public function addUserToPromotion($promoKey, $option) {
         $players = json_decode(file_get_contents(self::$dataFileName), true);
         for($i = 0; $i < count($players); $i++) {
            if ($players[$i]['username'] == $this->username) {
                    if ($option != null)  {
                        $addOption = ':'.$option;
                    } else {
                        $addOption = '';
                    } 
                    $players[$i]['optin'] =  ($players[$i]['optin'] != '') ? $players[$i]['optin'] .','.$promoKey.$addOption : $promoKey.$addOption;
                    return $this->saveTags($players);
            }
         }
    }
    
    
    /**
     * Loads all players and tags from storage
     * (Placeholder functionality only)
     */
    private function loadTags() {
        $players = json_decode(file_get_contents(self::$dataFileName), true);
        if (array_key_exists($this->username, $players)) {
            $this->tags = $players[$this->username];
        }
        else {
            $this->tags = array();
        }
    }
    
    /**
     * Saves all players and tags to storage
     * (Placeholder functionality only)
     */
    private function saveTags($players) {
        file_put_contents(self::$dataFileName, json_encode($players));
    }

    /**
     * Validate user-> call the function to find the user after the unique key id
     *
     */
    public function validateUser() {
        $this->getCurrentPlayer();
       
    }

    public function getCurrentPlayer() {
        $dataFileName = self::$dataFileName;
        $players = json_decode(file_get_contents($dataFileName), true);
        for($i = 0; $i < count($players); $i++) {
            if ($players[$i]['username'] == $this->username) {
                  $this->valid = true;
                  return $players[$i];
            }
         }
    }
}

Player::init();