<?php
namespace Craft;

class CustomRegistrationService extends BaseApplicationComponent 
{

    function registerUser($firstname, $lastname, $password, $email, $business)
    {
      $lNewUser = new UserModel();
      $lNewUser->getContent()->setAttributes(array(
          'firstname' => $firstname,
          'lastname' => $lastname,
          'business' => $business,
      ));
      $lNewUser->pending = true;
      $lNewUser->email = $email;
      $lNewUser->username = $email;
      $lNewUser->password = $password;

      return craft()->users->saveUser($lNewUser);
    }
}