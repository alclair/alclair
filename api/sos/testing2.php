<?php
/*
Client ID
9d0b9caf1a7e4bcb9c88578bbd37d187

Client Secret
lhPQfCzCyCEzYmR4yEY3VZkvxNugsUKQngLE

Application Type
developed for one or more specific SOS customers
*/
$username = 'OTIS';
$client_ID = '03d5e3ea1a064693b23e8548d9348b26';
$client_SECRET = '6nVgqdp3EeJ7OoQxT1VJdkwEg39fJYBx8bMF';


https://api.sosinventory.com/oauth2/authorize?response_type=code&client_id=[03d5e3ea1a064693b23e8548d9348b26]&redirect_uri=https://www.google.com/
$token = '5235995ec9954959a69e377b6c2ce2475ad7ef75169e4af6ad9daa238b099a03';

POST https://api.sosinventory.com/oauth2/5235995ec9954959a69e377b6c2ce2475ad7ef75169e4af6ad9daa238b099a03

Content-Type: application/x-www-form-urlencoded
Host: api.sosinventory.com

grant_type=authorization_code
 &client_id=[$client_ID]
 &client_secret=[$client_SECRET];
 &code=[authorization code]
 &redirect_uri=[redirect uri from authorization call]
 
 {
 "access_token": "JdqRERTTeJ86Cisg...",
 "token_type": "bearer",
 "expires_in": 7775999,
 "refresh_token": "vk1yx21bf0ZgvLJj7G..."
}

GET https://api.sosinventory.com/api/v2/item
?>