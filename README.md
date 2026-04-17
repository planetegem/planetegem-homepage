# Introduction
This is the newest version of planetegem.be, running SysInvAdmin in the background as rudimentary CMS. I'm also laying some groundwork here to make a robust template for websites running SysInvAdmin, taking a lot of inspiration from Laravel and Blade.

# Project Structure
## Php
PHP is used for the main logic of the application, such as API fetching and HTML rendering. 

### Core
These are the PHP scripts called in index.php, the heart of the application. Ideally, you should not have to touch these to create new pages or components. There are currently 3 core scripts:
1) Logger: provides logic to do error logging. Called first in index.php.
2) Loader: goes through the php folder and includes every php file encountered once, with the exeption of files in the core subfolder (which are included manually in index.php) and files ending in .view.php (which are only included when Render is called on the view class)
3) Router: handles routing to dynamic pages

### Views
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

### Routes
<ins>TO DO</ins>: get Route class here, which can be extended. The router uses class reflection to find all extensions of the Route class and determine what to do with them. Cleaner than doing everything in the router.

### Helpers
Contains handy helper functions, that are loaded by the loader and consequentially readily available.

## Other folders
### Css
Contains all css-files. <ins>TO DO</ins>: might add option to minify, and to isolate code by component.

### Js
Contains js-files. <ins>TO DO</ins>: see css.

### Resources
Contains all resources, such as images, icons, and fonts.

### Content
This is where custom pages live: pages that were not generated dynamically with the CMS and which have a fixed path. When linking to these pages, you don't have to include the '/content' part: the .htaccess file includes a rule to take care of this.

For clarity, the rerouting logic imposed by .htaccess:
1) if path exists, simply return path
2) if it doesn't, try same path but preceded by '/content' (so '/example/test' actually points to '/content/example/test')
3) if that path doesn't exist, redirect to index.php
4) index.php includes the router, which dynamically generates an appropriate page based on the url

<ins>TO DO</ins>: there is currently a bug where folders that don't have a trailing slash reveal the content folder in the path. To be fixed.


