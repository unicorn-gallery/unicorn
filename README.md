unicorn
=======

A Dropbox gallery for your website.

* Host your own your pictures on your own site
* Rocket fast, local caching.
* Custom templates
* Automatic, realtime updates using Delta API.

Setup
-----

1. Due to Dropbox restrictions I'm not allowed to share my application
   key. [Create a new Dropbox app](https://www.dropbox.com/developers/apps/create)
   with the settings in the screenshot and put the keys into `config_sample.php`.
   Afterwards rename `config_sample.php` to `config.php` to activate it.
   ![Dropbox App settings](https://raw.github.com/mre/unicorn/master/assets/img/create_app.png)

2. Run `composer install` inside the unicorn folder to install all dependencies.

3. Upload the whole unicorn directory to your webserver.

4. Open your browser, type `www.example.com/path/to/unicorn` and follow the instructions.

5. Put some pictures into `Dropbox/Apps/<your_app_name>`. Each directory is a gallery.
   Now go ahead and enjoy your gallery.

Pro tip: You can also enable pretty URLs.
See the [Slim documentation] on how to enable them for your server.

Customization / Themes
----------------------

The gallery uses Twig and is fully customizable via html and css.
Look into `templates` for inspiration.

Troubleshooting
---------------

### What if my pictures don't show up?

By default, the gallery is updated every five minutes. This is a tradeoff
between Dropbox API restrictions and keeping the gallery up-to-date.
You can set the gallery to update more frequently in the config file, but this is
discouraged.

After uploading unicorn to the server, make sure that all directories are writeable by unicorn.
Otherwise the pictures can not be cached and won't show up.

Some hosters don't allow long running background processes (longer than 30 seconds or so).
If your pictures don't show up in the gallery, the update process might have
been terminated by the server. Try adding only a few albums at a time to work
around this issue.


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

* [Slim-Framework](http://www.slimframework.com/) - A PHP micro framework
* [Slim-MVC](https://github.com/revuls/SlimMVC) - A skeleton MVC schema for slim.
* [Dropbox](https://github.com/BenTheDesigner/Dropbox) - An alternative PHP 5.3 SDK for the Dropbox REST API
* [Twig](http://twig.sensiolabs.org/) - The flexible, fast, and secure template engine for PHP
* [Responsive Layout](http://www.dwuser.com/education/content/creating-responsive-tiled-layout-with-pure-css/) - A fine tutorial on responsive design
* [Composer](http://getcomposer.org/) - The PHP package manager

Thanks!

[Slim documentation]: https://github.com/codeguy/Slim
