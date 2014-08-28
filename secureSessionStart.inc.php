<?php
 
/***** 1. Start off with some php.ini setup *****/
 
// Increases the randomness of the session ids.
// NOTE: This feature is supported on Windows since PHP 5.3.3.
// In Windows, setting session.entropy_length to a non zero value will make PHP use the Windows Random API as entropy source.
ini_set('session.entropy_file', '/dev/urandom');
 
// Sets how many bytes or characters are used from the above line's entropy file. 512 is pretty damn solid (27 August 2014)
// Also ensures increased entropy on Windows
ini_set('session.entropy_length', '512');
 
// Forces sessions to only use cookies.
ini_set('session.use_only_cookies', 1);
 
 
 
/***** 2. Set your session name *****/
 
// Sets the session name. Must be called before session_set_cookie_params() if you want, for example, to set the session timeout
// NOTE: The session name can't consist of digits only, 
// at least one letter must be present. Otherwise a new session id is generated every time.
// Also, keep in mind that periods in your session name will be converted to underscores.
session_name("mySecureSession");
 
 
 
/***** 3. Set your cookie parameters *****/
 
// set the timeout limit, in second, for the session. 0 means until the browser is closed and is the default.
$lifetime = 0;
 
// Set path in the session cookie. Defaults to /
// Setting this to "/somePath" would render the session valid only for paths under example.com/somePath
$path = "/";
 
// Set the domain in the session cookie
// Putting a period in front of the domain will set the domain to include all subdomains in addition to the current domain
$domain = "example.com"; // use ".example.com" to include all subdomains
 
// Set to true if using HTTPS, otherwise just leave as false.
// In production, set this to true and use SSL
$secure = false;
 
// When this is set to true, JavaScript cannot access the session ID.
// This setting prevents cookies from being stolen by JavaScript injection.
$httponly = true;
 
// set the cookie parameters
session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
 
 
 
/***** 4. Start the session *****/
 
session_start();
 
 
 
/***** 5. Prevent session hijacking based on IP and user agent *****/
 
// check for a populated $_SESSION['hashedIp']
if (empty($_SESSION['hashedIp']) || ($_SESSION['hashedIp'] != hash('sha512', $_SERVER['REMOTE_ADDR']))) {
    
    // The following is a snippet from Robert Hafner's solution from 2009, link below
    // https://code.google.com/p/phpsessionmanager/source/browse/trunk/Session.class.php
    
    // reset the session
    $_SESSION = array();
    // save the hashed IP address to session (hashed in order to prevent it from being a liability if it gets out in the wild)
    $_SESSION['hashedIp'] = hash('sha512', $_SERVER['REMOTE_ADDR']);
    // save the user agent to session
    $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
    // Create new session without destroying the old one
    session_regenerate_id(false);
    // Grab current session ID and close both sessions to allow other scripts to use them
    $newSession = session_id();
    session_write_close();
    // Set session ID to the new one, and start it back up again
    session_id($newSession);
    session_start();
}
 
// check for a populated $_SESSION['userAgent']
if (empty($_SESSION['userAgent']) || ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])) {
    
    // The following is a snippet from Robert Hafner's solution from 2009, link below
    // https://code.google.com/p/phpsessionmanager/source/browse/trunk/Session.class.php
    
    // reset the session
    $_SESSION = array();
    // save the hashed IP address to session (hashed in order to prevent it from being a liability if it gets out in the wild)
    $_SESSION['hashedIp'] = hash('sha512', $_SERVER['REMOTE_ADDR']);
    // save the user agent to session
    $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
    // Create new session without destroying the old one
    session_regenerate_id(false);
    // Grab current session ID and close both sessions to allow other scripts to use them
    $newSession = session_id();
    session_write_close();
    // Set session ID to the new one, and start it back up again
    session_id($newSession);
    session_start();
}
 
?>
