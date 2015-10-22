<?php

    /*
    *   PRIVACY NOTE
    *   for cookies and user tracking
    *
    *   This library of functions is a sad try to create "fancy" messenges
    *   based on the strange EU guidlines for cookies and the tricky wishes
    *   of the privacy groups.
    *
    *   NOTE: I cannot say if this is a legal way of doing these notes according
    *   to the law and juristication of the EU and germany!
    *
    *   --- USE AT OWN RISK ---
    *
    *
    *
    *   Changelog:
    *   -----------------------
    *   v0.1
    *   - basic functions for showing the notice, accept and reject user tracking
    *   - no deeper error handling yet
    *
    *
    *   
    */
    
    
    # VERSION 0.1
    # released 16.09.2014
    
    
    /**
    * function PN_ShowNotice()
    * @param string $privacypoliceurl full url to the site privacy police
    * @param string $sessionname name of the session var to store results
    */
    function PN_ShowNotice($privacypoliceurl='?p=privacy', $sessionname='pn_ckeck'){
        $html = '';

        // check if the user accepted the use of analytic software or removed the approval
        if(isset($_GET['pn_note']) AND $_GET['pn_note'] == 'checked'){
            // if the use approved the use of analyitcs software ... 
            $_SESSION[$sessionname] = 'checked';
          //  echo "<div class='pn_notebox pn_approved'>\n";
          //  echo "Vielen Dank. Details zur Art und Umfang der Datenerhebung findest Du in unserer <a href='".$privacypoliceurl."'>Datenschutzerkl&auml;rung</a>.\n";
          //  echo "</div>\n";
        }
        elseif(isset($_GET['pn_note']) AND $_GET['pn_note'] == 'removed'){
            // if the user rejected/removed the use of analytics software ...
            unset($_SESSION[$sessionname]);
            $html .= "<div class='pn_notebox pn_removed'>\n";
            $html .= "Du hast Deine Genehmigung zur Nutung von Analyse-Software zur&uuml;ckgezogen.";
            $html .= "</div>\n";
        } else {
            // check if privacy note was allready accepted, if not we show the note
            if(!isset($_SESSION[$sessionname]) OR $_SESSION[$sessionname] != 'checked'){
                $html .=  "<div class='pn_notebox'>\n";
                $html .=  "<center><small>Der Gebrauch von Cookies erlaubt uns dieses Helfersystem für Euch anzubieten. Durch Fortfahren auf unserer Webseite stimmst Du der Verwendung von Cookies zu. Mehr über Cookies erfährst Du in unserer <a href='".$privacypoliceurl."'>Datenschutzerkl&auml;rung</a>.</small> <br><a href='".newURL('pn_note=checked')."' class='pn_approval'>Verstanden!</a></center> \n";
                $html .=  "</div>\n";
            }
        }
        
        return $html;
    }
    
    
    
    /**
    * function PN_GetAnalyticsCode()
    * @param string $file filename of the html file containing analytics code to include
    * @param string $sessionname name of the session var to store results
    */
    function PN_GetAnalyticsCode($file='trackingcode.html', $sessionname='pn_ckeck'){
        if(isset($_SESSION[$sessionname])){
            if($_SESSION[$sessionname] == 'checked'){
                // reading the file containing the tracking code
                readfile($file);
            }
        }   
    }
    
    
    
    /**
    * function PN_ApprovalState()
    * @param string $sessionname name of the session var to store results
    */
    function PN_ApprovalState($sessionname='pn_ckeck'){
       if(isset($_SESSION[$sessionname])){
            if($_SESSION[$sessionname] == 'checked'){
                echo "<div class='pn_state'>\n";
                echo "Sie haben der Nutzung von Analyse-Software zugestimmt. <a href='".newURL('pn_note=removed')."'>Klicken Sie hier um Ihre Zustimmung zu widerrufen.</a>\n";
                echo "</div>\n";
            }
        } else{
            echo "<div class='pn_state'>\n";
            echo "Sie haben der Nutzung von Analyse-Software nicht zugestimmt. <a href='".newURL('pn_note=checked')."'>Klicken Sie hier um Ihre Zustimmung zu erteilen.</a>\n";
            echo "</div>\n";
        }
    }
    
    
    
    /**
    * function PN_IsApproved()
    * @param string $sessionname name of the session var to store results
    */
    function PN_IsApproved($sessionname='pn_ckeck'){
       if(isset($_SESSION[$sessionname])){
            if($_SESSION[$sessionname] == 'checked'){
                return true;
            }
        } else{
            return false;
        }
    }
    
    
    
    
    
    
    
    
    
    
    /**
     * Helper functions for getting the current URL with query string
     * Source: http://stackoverflow.com/questions/6768793/get-the-full-url-in-php
     */
    function url_origin($s, $use_forwarded_host=false){
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }
    function full_url($s, $use_forwarded_host=false){
        return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
    }
    
    /**
     * Helper function for getting the correct new URL with coorect query string separator
     * based on the current URL gotten from full_url() function above. If there are already
     * query strings attached, the pn_note query strings will be applied using '&'.
     * Else they will be added to the current URL using '?'.
     * Source: http://stackoverflow.com/questions/5215684/append-query-string-to-any-form-of-url
     */
    function newURL($queryStringToAttach='lorem=ipsum'){
        $absolute_url = full_url($_SERVER);
        $separator = (parse_url($absolute_url, PHP_URL_QUERY) == NULL) ? '?' : '&';
        $newurl = $absolute_url . $separator . $queryStringToAttach;
        return $newurl;  
    };
    
    
    
?>