<?php
    session_start();
    // init configuration
    $clientID = '1038840787003-n9lfv6h5tg3pdu38c6lksvkvivl1geor.apps.googleusercontent.com';
    $clientSecret = 'n9qmB0e0811fHzW4jgMBhAh7';
    $redirectUri = 'http://localhost:8000/redirect.php';
    
    // create Client Request to access Google API
    $client = new Google_Client();
    $client->setApplicationName("Datalearn");
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");
    
    // authenticate code from Google OAuth Flow
    if (isset($_GET['code'])) {
        // $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        // $client->setAccessToken($token['access_token']);

        // // get profile info
        // $google_oauth = new \Google_Service_Oauth2($client);
        // $google_account_info = $google_oauth->userinfo->get();
        // $email =  $google_account_info->email;
        // $name =  $google_account_info->name;
        $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirectUri, FILTER_SANITIZE_URL));
  if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
  }
    echo 'berhasil';
    // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;
  echo $name;
  echo $email;
        // now you can use this profile info to create account in your website and make user logged in.
    } else {
        echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
    }
?>