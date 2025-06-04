<?php

/**
 * @package     Pscms.Site
 * @subpackage  site_pscms
 * @version 	1.0.0
 * @since 		2017
 * 
 * @copyright   Copyright (C) 2017 Panchsoft Technologies. All rights reserved.
 * @author 		Panchsoft Technologies
 * @link 		http://www.panchsoft.com
 
 */



/**
 * Generate a Facebook login url
 * @param string redirect url  (eg: 'user/profile/connectFacebookAccount')
 * @return string Facebook login url
 */
function getFacebookLoginUrl($redirectUrl = '')
{
    $ci =& get_instance();
    return $ci->facebook->getLoginUrl($redirectUrl);
}
