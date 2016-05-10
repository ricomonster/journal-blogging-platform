# Journal Blogging Platform

Journal is an another blogging platform inspired by [Ghost](http://ghost.org) and powered by [Laravel](http://laravel.com).
Focused on publishing with easy to use tools. For developers, this can be customized easily as we leverage the power of
[Laravel](http://laravel.com).

![List of posts](http://i.imgur.com/0LKAKlF.png)
![Markdown Editor](http://i.imgur.com/Cdof6tG.png)

## How to install this shit?

First of all you need the following:
- Apache/Nginx Server
- PHP version >= 5.5.9 or 7
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- Node JS (for the build script)
- [Composer](https://getcomposer.org/)

Once you have those things installed you're good to go!

1. Download [Journal](https://github.com/ricomonster/journal-blogging-platform) from its Github repository.
1. Unzip it in the location you want.
1. Open up your terminal
1. Download [Composer](https://getcomposer.org/). To download Composer, type in the terminal
`curl -sS https://getcomposer.org/installer | php`
1. Install Journal's/Laravel dependencies, `composer install`
1. Start Journal! `http://localhost:8000`

** NOTE: Make sure that the `storage` folder and its contents are writable. Please create also an `upload` folder inside
the `public` folder because all of your contents that will uploaded will be stored in this location. Make sure also
that `upload` folder is writable.

## I'm a developer and I want to use Git because your repository is hosted in Github
We assume at this point that you have installed PHP, Node JS. Just follow the steps and problems won't come to you.

Clone Journal
```bash
git clone git://github.com/ricomonster/journal-blogging-platform.git
cd journal-blogging-platform
```

Install Journal/Laravel's dependency using Composer. If you don't have Composer, type in the terminal
`curl -sS https://getcomposer.org/installer | php` to download it.

```bash
# PHP things
composer install

# Node JS things
npm install
```

Install [Gulp](https://www.npmjs.com/package/gulp) and [Bower](https://www.npmjs.com/package/bower)
```bash
npm install -g gulp bower
```

Build it!
```bash
gulp
```

Do you want this thing to run for production?
```bash
gulp --prod
```

Fire it up!
```bash
php artisan serve
# or
php -S <localhost or your public IP>:8080 -t /public
```

Yay, You're now good to go!

## FAQ
### Why does it looks like Ghost?
Ghost is such a wonderful application and I followed it since their Kickstarter days. I believed in their goals so that created me an idea to create a another blogging platform. I also believed in this quote said by some guy that "Imitation is the sincerest form of flattery".

### The requested URL /themes/casper/assets/css/screen.css was not found on this server.
The default theme is Casper, also imitated the default theme of Ghosts. If you encounter this problem, first make sure that [Gulp](https://www.npmjs.com/package/gulp) is installed then fire up your terminal and run `gulp theme-assets`.

## License
The Journal Blogging Platform is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
