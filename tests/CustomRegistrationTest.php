<?php
namespace Craft;

class CustomRegistrationTest extends BaseTest 
{
    
    public function setUp()
    {
    
        // Load plugins
        $pluginsService = craft()->getComponent('plugins');
        $pluginsService->loadPlugins();
    
    } 
    
    public function testAction() 
    {
    

        
    }
    
}