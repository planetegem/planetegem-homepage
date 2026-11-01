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
Views contains all PHP files that generate functional HTML. There are currently 2 types of views:
1) DocumentView: expects a [class_name].view.php file when you extend the class. This file should contain all html. Class props can just be invoked as normal variables. Rendering a DocumentView includes a http status code (default = 200).
2) SingleFileView: HTML comes immediately after the class clode in the same files (split by __halt_compiler()). Used for simple components, with multiple per page.

Both types of view depend on their Render method, but calling the class as a string is enough to to call the Render method, so you don't need to explicitly call it.

<ins>VS Code recommendation</ins>
To get a clean file structure in VS, you can add the following block to your settings.json (works for the 2-file views like DocumentView):
```
{
    "explorer.fileNesting.enabled": true,
    "explorer.fileNesting.expand": true,
    "explorer.fileNesting.patterns": {
        "*.php": "${capture}.view.php"
    }
}
```
This allows view.php files to be nested under corresponding class files: a similar presentation as XAML files when working with the WPF framework in Visual Studio.

## Helpers an traits
Helper functions used in sites using SysInvAdmin.
