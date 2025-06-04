<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * @package     Pscms.Administrator
 * @subpackage  site_pscms
 * @version 	1.0.0
 * @since 		2017
 * 
 * @copyright   Copyright (C) 2017 Panchsoft Technologies. All rights reserved.
 * @author 		Panchsoft Technologies
 * @link 		http://www.panchsoft.com
 *
 */

// ------------------------------------------------------------------------

/**
 * TCPDF Library
 *
 * Nothing but legless, boneless creatures who are responsible for creating
 * magic "friendly urls" in your CodeIgniter application. Slugs are nocturnal
 * feeders, hiding during daylight hours.
 *
 * @subpackage Libraries
 */

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';


class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
}