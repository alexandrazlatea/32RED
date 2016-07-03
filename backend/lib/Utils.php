<?php

class Utils {

	 /**
     * Get the data from file
     * 
     * @return json
     */
    static function getData($fileName) {
        $promoDataFile = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . '$fileName';
        return json_decode(file_get_contents($promoDataFile), true);
         
    }
     /**
     * compare a date with current date
     * @param $endDate
     *
     * @return boolean
     */
    static function compareDates($endDate) {
    	$endDate = strtotime($endDate);
    	$now = time();
    	if ($endDate < $now ) {
    		return true;
    	}
    	return false;
    }

   } 