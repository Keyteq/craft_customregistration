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
                  $email->subject = 'New user registred on Skyss Profil Bok';
                  $email->body    = 'New User registred: '.$user->firstname.' '.$user->lastname.'.<br>You can activate this account in the site administration panel.';

                  craft()->email->sendEmail($email);
                }
                
                $email = new EmailModel();
                $email->toEmail = $user->email;
                $email->subject = 'You have registred on Skyss Profil Bok';
                $email->body    = 'You have succesfully registred on Skyss Profil Bok.<br>To continue, your account must be activated by an administrator.';

                craft()->email->sendEmail($email);
            }
        });
        
        craft()->on('users.activateUser', function(Event $event)
        {
          $user = $event->params['user'];
          
          $email = new EmailModel();
          $email->toEmail = $user->email;
          $email->subject = 'Your account on Skyss Profil Bok has been activated';
          $email->body    = 'Your account has been activated by the administrator. You can log in.';
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
    
}