## Journal Blogging Platform

Journal is an another blogging platform because [Ghost](http://ghost.org) came in first and we both focused on one thing, publishing. Journal attempts to ease out publishing and sharing your thoughts to the world with our easy-to-use platform. All you need to do is write, and click publish.

Journal is easy to configure and you can do whatever you want because it is powered by one of PHP's popular and powerful framework, [Laravel](http://laravel.com). So if you're a developer and you wanted to tinker or modify some of its functionality, you're free to go.

### How to install this shit?

First of all you need the following:
- Apache/Nginx Server
- PHP version >= 5.5.9
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- Node JS
- [Composer](https://getcomposer.org/)

Once you have those things installed you're good to go!

1. Download [Journal](https://github.com/ricomonster/journal-blogging-platform) from its Github repository.
1. Unzip it in the location you want.
1. Open up your terminal
1. Download [Composer](https://getcomposer.org/). To download Composer, type in the terminal `curl -sS https://getcomposer.org/installer | php`
1. Install Journal's/Laravel dependencies, `composer install`
1. Once Composer finished downloading, setup your database connection. You can copy `.env.example` in the root directory to `.env` and input your database credentials or open `app/config.php`.
1. Start Journal! `http://localhost:8000`

** NOTE: Make sure that the `storage` folder and its contents are writable. Please create also an `upload` folder inside the `public` folder because all of your contents that will uploaded will be stored in this location. Make sure also that `upload` folder is writable.

#### I'm a developer and I want to use Git because your repository is hosted in Github
We assume at this point that you have installed PHP, Node JS. Just follow the steps and problems won't come to you.

Clone Journal
```bash
git clone git://github.com/ricomonster/journal-blogging-platform.git
cd journal-blogging-platform
```

Install Journal/Laravel's dependency using Composer. If you don't have Composer, type in the terminal `curl -sS https://getcomposer.org/installer | php` to download it.

```bash
# PHP things
composer install

# Node JS things
npm install 
```

Install Gulp and Bower
```bash
npm install -g gulp bower
```

Build it!
```bash
gulp build
```

Do you want this thing to run like in production?
```bash
gulp build-prod
```

Fire it up!
```bash
php artisan serve
# or
php -S <localhost or your public IP>:8080 -t /public
```

Yay, You're now good to go!

### License
The Journal Blogging Platform is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
