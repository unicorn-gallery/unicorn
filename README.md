unicorn
=======

A Dropbox gallery for your website.

* Host your own your pictures on your own site
* Rocket fast using local caching
* Custom templates
* Automatic, realtime updates using delta API

Setup
-----

1. Due to Dropbox restrictions I'm not allowed to share my application
   key. So you have to create a new Dropbox app at https://www.dropbox.com/developers/apps
   and put the keys into `config.php`. You could name your app `unicorn`, for
   instance.

2. Run `composer install` inside the unicorn folder to install all dependencies.

3. Upload the whole unicorn directory to your webserver.

4. Open your browser, type `www.example.com/path/to/unicorn` and follow the instructions.

5. Put some pictures into `Dropbox/Apps/<your_app_name>` and enjoy your gallery.

Pro tip: You can also enable pretty URLs.
See the [Slim documentation] on how to enable them for your server.

Customization / Themes
----------------------

The gallery uses Twig and is fully customizable via html and css.
Look into `templates` for inspiration.

Copyright
---------

Copyright (C) 2013 Matthias Endler
http://www.matthias-endler.de

License
-------

GNU General Public License version 3.
See LICENSE.txt for details.

Credits
-------

Unicorn makes use of the following projects.

* [Slim-Framework][http://www.slimframework.com/] - A PHP micro framework
* [Slim-MVC][https://github.com/revuls/SlimMVC] - A skeleton MVC schema for slim.
* [Dropbox][https://github.com/BenTheDesigner/Dropbox] - An alternative PHP 5.3 SDK for the Dropbox REST API
* [Twig][http://twig.sensiolabs.org/] - The flexible, fast, and secure template engine for PHP
* [Responsive Layout][http://www.dwuser.com/education/content/creating-responsive-tiled-layout-with-pure-css/] - - A fine tutorial on responsive design
* [Composer][http://getcomposer.org/] - The PHP package manager

Thanks!

[Slim documentation]: https://github.com/codeguy/Slim
