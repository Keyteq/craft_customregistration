<?php
namespace Craft;

class CustomRegistrationService extends BaseApplicationComponent 
{

    function registerUser($firstname, $lastname, $password, $email, $business, $reason)
    {
      $lNewUser = new UserModel();
      $lNewUser->getContent()->setAttributes(array(
          'firstname' => $firstname,
          'lastname' => $lastname,
          'business' => $business,
          'reason' => $reason,
      ));
      $lNewUser->pending = true;
      $lNewUser->suspended = false;
      $lNewUser->email = $email;
      $lNewUser->username = $email;
      $lNewUser->password = $password;

      return craft()->users->saveUser($lNewUser);
    }
}
