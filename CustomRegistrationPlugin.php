<?php
namespace Craft;

class CustomRegistrationPlugin extends BasePlugin
{
  
  //http://buildwithcraft.com/classreference/services/UsersService#activateUser-detail
  //http://craftcms.stackexchange.com/questions/5216/user-registration-keep-as-inactive-until-verified-by-admin
    public function init()
    {
        parent::init();

        craft()->on('users.saveUser', function(Event $event)
        {
            $user = $event->params['user'];
            $isNewUser = $event->params['isNewUser'];

            if ($isNewUser) {
                // assign to group
                craft()->userGroups->assignUserToGroups($user->id, 1);
                
                // Get all users from UserGroup
                $user_criteria = craft()->elements->getCriteria(ElementType::User);
                $user_criteria->groupId = '3';
                $users = $user_criteria->find();

                // Send mail to each Administrator
                foreach ($users as $admin) {
                    $email = new EmailModel();
                    $email->toEmail = $admin->email;
                    $email->subject = $this->parseMessage($this->getSettings()->adminMessageSubject, $user->firstname, $user->lastname, $user->business, $user->reason);
                    $email->body    = $this->parseMessage($this->getSettings()->adminMessage, $user->firstname, $user->lastname, $user->business, $user->reason);

                    craft()->email->sendEmail($email);
                }
                
                $email = new EmailModel();
                $email->toEmail = $user->email;
                $email->subject = $this->parseMessage($this->getSettings()->registrationMessageSubject, $user->firstname, $user->lastname, $user->business, $user->reason);
                $email->body    = $this->parseMessage($this->getSettings()->registrationMessage, $user->firstname, $user->lastname, $user->business, $user->reason);

                craft()->email->sendEmail($email);
            }
        });
        
        craft()->on('users.activateUser', function(Event $event)
        {
          $user = $event->params['user'];
          
          $email = new EmailModel();
          $email->toEmail = $user->email;
          $email->subject = $this->parseMessage($this->getSettings()->activationMessageSubject, $user->firstname, $user->lastname, $user->business, $user->reason);
          $email->body    = $this->parseMessage($this->getSettings()->activationMessage, $user->firstname, $user->lastname, $user->business, $user->reason);
          craft()->email->sendEmail($email);
        });
    }
    
    function getName()
    {
        return Craft::t('Custom Registration');
    }

    function getVersion()
    {
        return '1.0.0';
    }

    function getDeveloper()
    {
        return 'Mateusz Madej';
    }

    function getDeveloperUrl()
    {
        return 'http://www.smartmobilehouse.pl';
    }
    
    protected function defineSettings()
    {
        return array(
            'registrationMessage' => array(AttributeType::String, 'required' => false),
            'registrationMessageSubject' => array(AttributeType::String, 'required' => false),
            'adminMessage' => array(AttributeType::String, 'required' => false),
            'adminMessageSubject' => array(AttributeType::String, 'required' => false),
            'activationMessage' => array(AttributeType::String, 'required' => false),
            'activationMessageSubject' => array(AttributeType::String, 'required' => false)
        );
    }
    
    public function getSettingsHtml()
    {
        return craft()->templates->render('customRegistration/_settings', array(
            'settings' => $this->getSettings()
       ));
    }
    
    public function parseMessage($message, $firstname, $lastname, $business, $reason)
    {
        $message = str_replace("{{firstName}}", $firstname, $message);
        $message = str_replace("{{lastName}}", $lastname, $message);
        $message = str_replace("{{business}}", $business, $message);
        $message = str_replace("{{reason}}", $reason, $message);
        
        return $message;
    }
}
