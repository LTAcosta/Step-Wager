<?php

// A function that checks to see if
// an email is valid
function validEmail($email)
{
    $isValid = true;
    $atIndex = strrpos($email, "@");
    if (is_bool($atIndex) && !$atIndex)
    {
        $isValid = false;
    }
    else
    {
        $domain = substr($email, $atIndex+1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64)
        {
            // local part length exceeded
            $isValid = false;
        }
        else if ($domainLen < 1 || $domainLen > 255)
        {
            // domain part length exceeded
            $isValid = false;
        }
        else if ($local[0] == '.' || $local[$localLen-1] == '.')
        {
            // local part starts or ends with '.'
            $isValid = false;
        }
        else if (preg_match('/\\.\\./', $local))
        {
            // local part has two consecutive dots
            $isValid = false;
        }
        else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
        {
            // character not valid in domain part
            $isValid = false;
        }
        else if (preg_match('/\\.\\./', $domain))
        {
            // domain part has two consecutive dots
            $isValid = false;
        }
        else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
            str_replace("\\\\","",$local)))
        {
            // character not valid in local part unless
            // local part is quoted
            if (!preg_match('/^"(\\\\"|[^"])+"$/',
                str_replace("\\\\","",$local)))
            {
                $isValid = false;
            }
        }
        if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
        {
            // domain not found in DNS
            $isValid = false;
        }
    }
    return $isValid;
}

function Calculate_Wins($fitbit_id){
    global $isUserLoggedIn, $dbLink;

    if (!$fitbit_id || !$isUserLoggedIn || !$dbLink)
        return;

    $wins = 0;
    $losses = 0;
    $ties = 0;

    $query  = 'SELECT * FROM wagers WHERE (creator_id = "'.mysqli_real_escape_string($dbLink, $fitbit_id).'" OR opponent_id = "'.mysqli_real_escape_string($dbLink, $fitbit_id).'") AND winner IS NOT NULL';
    $result = mysqli_query($dbLink, $query);

    if($result && mysqli_num_rows($result) > 0){
        while ($wager = mysqli_fetch_array($result)){
            if ($wager['winner'] == $fitbit_id){
                $wins += 1;
            } else if ($wager['winner'] == 'both') {
                $ties += 1;
            } else {
                $losses += 1;
            }
        }
    }

    $query = 'UPDATE users SET wins="'. $wins .'", losses="'. $losses .'", ties="'. $ties .'" WHERE fitbit_id = "' . mysqli_real_escape_string($dbLink, $fitbit_id) . '"';
    mysqli_query($dbLink, $query);
}

?>