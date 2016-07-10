<?php
/**
 * @package browsebox
 * @version 0.1
 */
/*
Plugin Name: Browsebox
Plugin URI: http://browsebox.io
Description: this a demo version of browsebox
Version: 0.1
Author URI: barent.me
*/

    function initiate_the_crawl($content){

        $affArray = create_affiliate_array();

        $content .= '<table>';
        $content .= '<tr>';
        $content .= '<th><h3>Vendor</h3></th>';
        $content .= '<th><h3>Price</h3></th>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th><h4>Bestbuy</h4></th>';
        $content .= '<th>' . crawl_for_price($affArray[1], $affArray[2]) . '</th>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th><h4>Amazon</h4></th>';
        $content .= '<th>' . crawl_for_price($affArray[3], $affArray[4]) . '</th>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th><h4>Gamestop</h4></th>';
        $content .= '<th>' . crawl_for_price($affArray[5], $affArray[6]) . '</th>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th><h4>Ebay</h4></th>';
        $content .= '<th>' . crawl_for_price($affArray[7], $affArray[8]) . '</th>';
        $content .= '</table>';

        return $content;
    }
   
          

    function crawl_for_price($url, $regex){
        include_once('simple_html_dom.php');
        
        // create curl resource
        $ch = curl_init();
        
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13 Chrome/24.0.1312.52 Safari/537.17');
        curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $html_base = new simple_html_dom();

        if(!$output){
            return "No Price";
        }

        $html_base->load($output);

        //$html = str_get_html($output);
        if(empty($html_base)){
            return "No HTML";
        }
        else{
            $elem = $html_base->find($regex, 0);
            return $elem;
        }
        
    }
    
    
    
    function create_affiliate_array(){
        $affArray = array(
            1 => 'http://www.bestbuy.com/site/absolute-zombies-dvd-2015/29399177.p?skuId=29399177&ref=199&loc=2VP6ghPkFbA&acampID=29399177&siteID=2VP6ghPkFbA-9RV5ikbDbpEqtNTy9xYzNQ',
            2 => '//*[@id="priceblock-wrapper-wrapper"]/div[1]/div[1]/div[2]/div[1]/text()',
            3 => 'https://www.amazon.com/LEGO-Batman-Beyond-Gotham-Xbox-One/dp/B00KJ8UPC6/ref=as_li_ss_tl?ie=UTF8&dpID=51Lc+ZjAnML&dpSrc=sims&preST=_AC_UL320_SR234,320_&psc=1&refRID=CYXRSR66DKS79JHF3E2F&linkCode=sl1&tag=affdojo-20&linkId=b4de29e2d26352d8a0fab017051e7c19',
            4 => '//*[@id="olp_feature_div"]/div/span[1]/span',
            5 => 'http://click.linksynergy.com/link?id=2VP6ghPkFbA&offerid=271758.176087&type=2&murl=http%3A%2F%2Fwww.gamestop.com%2FCatalog%2FProductDetails.aspx%3Fsku%3D960996',
            6 => '//*[@id="mainContentPlaceHolder_dynamicContent_ctl00_RepeaterRightColumnLayouts_RightColumnPlaceHolder_0_ctl00_0_LayoutStandardPanel_0"]/div[5]/div[2]/div[2]/div[2]/h3/span',
            7 => 'http://rover.ebay.com/rover/1/711-53200-19255-0/1?icep_ff3=2&pub=5575159564&toolid=10001&campid=5337836705&customid=&icep_item=291693106316&ipn=psmain&icep_vectorid=229466&kwid=902099&mtid=824&kw=lg',
            8 => '//*[@id="prcIsum"]'
            
        );
        return $affArray;
    }
    

    add_filter('the_content', 'initiate_the_crawl' );

?>
