<?php

namespace Craft;

class CustomRegistrationController extends BaseController {

  protected $allowAnonymous = true;

  public function actionRegisterUser() {
    
    $fields = craft()->request->getRequiredParam('fields');

    $firstname = $fields['firstname'];
    $lastname = $fields['lastname'];

    $password = craft()->request->getRequiredParam('password');

    $email = craft()->request->getRequiredParam('email');

    $business = $fields['business'];
    $reason = $fields['reason'];

    $user = craft()->customRegistration->registerUser($firstname, $lastname, $password, $email, $business, $reason);

    if ($user) {
      $this->redirectToPostedUrl($user);
    } 
    else {
      craft()->userSession->setError(Craft::t('Couldn’t save user.'));
    }
    // Send the account back to the template
    craft()->urlManager->setRouteVariables(array(
        'account' => $user
    ));
  }

}
