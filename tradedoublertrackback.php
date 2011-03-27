<?php

/**
 *    This module for OXID eSales is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This module for OXID eSales is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this module for OXID eSales.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.muxcom.net/code/tradedoubler-tracking/oxid-esales-module-tradedoubler/
 * @package   tradedoubler
 * @copyright (c) muxcom UG 2010
 */


class tradedoublerTrackback extends tradedoublerTrackback_parent 
{

	
	protected $orgId = '123';
	protected $eventId = '456';
	protected $secretCode = '789';
	
	
  protected function _getNextStep($iSuccess)
  {
		
  	$tduid = $_COOKIE['tduid'];
  	
  	if ($tduid != '') {
  	
	  	$language = oxLang::getInstance()->getLanguageAbbr();
			$oxOrder = oxNew('oxorder');
			if ($oxOrder->load(oxSession::getVar('sess_challenge'))) 
	  	{
				$oxBasket = $this->getBasket();
				
				$orderValue = $oxBasket->getPrice()->getNettoPrice();
				//$oxOrder->getOrderDeliveryPrice()->getNettoPrice();
				
				$oxCurrency = $this->getConfig()->getActShopCurrencyObject();
	      $currency = $oxCurrency->name;

	      $orderNumber = $oxBasket->getOrderId();
	      
				$checksum = 'v04' . md5($this->secretCode . $orderNumber . $orderValue);
	      
				/*
				 * For feature reportInfo
				$basketArticles = $oxBasket->getBasketArticles();
				foreach ($basketArticles as $article) {
					$articles .= print_r($article, true) . ":";
					$articles .= $article->oxarticles__oxtitle->rawValue  . ":";
				}
				*/
				
	      $trackbackImage = "https://tbs.tradedoubler.com/report?" .
					"organization=" . $this->orgId .
					"&amp;event=" . $this->eventId .
					"&amp;orderNumber=" . $orderNumber .
					"&amp;checksum=" . $checksum .
					"&amp;tduid=" . $tduid .
					"&amp;reportInfo=" . "" .
					"&amp;orderValue=" . $orderValue .
					"&amp;currency=" . $currency;

	      $fp = fopen ('/tmp/oxid.log','a+');
	      fputs($fp, date("Y-m-d H:i:s") . " -- " . $trackbackImage . "\n");
	      
	  	}

  	}
  	return parent::_getNextStep($iSuccess);
  }

}
