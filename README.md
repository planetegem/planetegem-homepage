# Introduction
This is the newest version of planetegem.be, running SysInvAdmin in the background as rudimentary CMS. I'm laying some groundwork here to make a template for websites running SysInvAdmin, taking a lot of inspiration from Laravel and Blade.
This will also be the basis for other websites.

# Template structure & features
The /public directory is treated as your root folder as far as any visitor is concerned. It contains all css, js, resources and custom pages.

## Core Components
The index.php in the public folder points to bootstrap.php, which initializes all of the core components. These are:
- The App  functions as a service manager. You register other components, which can then be accessed as a static service.
- The Environment load the .env file and parses it. When bootstrapping, it is registered as a service with the App.
- The Logger handles error logging. If debug is enabled in the .env file (DEBUG_LOCAL=true), PHP actions are logged either as a .txt (if LOG_FUNCTION=TXT_LOG) or as a JS console.log (if LOG_FUNCTION=JS_DUMP). A shortcut is set to the main method (__message()).
- The Localizer enables using localization files in a very similar way as in Laravel. The localization files are placed in /localization/{locale_name}. The class provides logic to fetch and cache these files, and replace variables within the localization strings.
    <ins>TO DO</ins>: add support for pluralization.
- The API Client handles calls to API's. It provides a fetch method to execute a simple GET, as well as a caching mechanism where API responses are saved in .cache. The cache has an expiration term that can be customized, but if the API is updated, it's recommended that you remove the .cache folder to force a refresh. 
- The API Client is extended into a specialized SysInvAdminClient class, specifically directed at integrating with the SysInvAdmin API. 
- The Loader class offers some utility functions to include php files in large quantities.

## Routing
The Router class is responsible for directing requests to specific routes. It does this this by:
1) fetching a list of all classes that extend the Route class
2) creating an array of Route instances
3) dispatching to the correct route based on uri & method

You add new routes by creating an extension of the Route class. 
Add a public string property called $path to indicate the path leading to the route. You can also add a public string property $method to include a method. If you don't, it defaults to 'GET'. Finally, implement the go() method to define what a specific route needs to do. Typically, this will be an API call or a view being instantiated.

``` php
class HomeRoute extends Route
{
    public string $path = '/';
    public string $method = 'GET';


    public function go(array $params): void
    {
        echo new Page()->Render();
    }
}
```

## Views
Views contains all PHP files that generate functional HTML. Here, you create view components that consist of 2 files:
1) a class file that handles most (or ideally all) logic (for example item.php which contains the class Item). The class needs to extend the View class.
2) a view file that contains the HTML to be rendered, with the same name as the item class, but ending in view.php (for example item.view.php)

Properties from the class file are accessed in the view file through the associative $props array. You can then render the view file by calling the Render method on an instance of the class.

<ins>How does it work?</ins>
Everything is powered by the View class that's extended by a component class. The View class constructor provides logic to figure out where the view file is located. It does this by using reflection to obtain the path to the current class file, which is then amended by the .view.php extension. This path is saved as the $template property.
The View class also provides the Render method that's used by all view components. This method pushes all class properties into the $props array and then output buffering on. It then includes the view file (buffered) and returns the contents of the buffer as a string. 

<ins>VS Code recommendation</ins>
To get a clean file structure in VS, I recommend adding the follwing block to your settings.json:
```
{
    "explorer.fileNesting.enabled": true,
    "explorer.fileNesting.expand": true,
    "explorer.fileNesting.patterns": {
        "*.php": "${capture}.view.php"
    }
}
```
This way, the view.php files will be nested under there corresponding class files, giving a similar presentation as for XAML files when working with the WPF framework in Visual Studio.

## Helpers
Helper functions used in sites using SysInvAdmin.
