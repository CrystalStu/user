# Crystal User System &nbsp; クリスタルユーサーシステム

Production: https://user.cstu.gq

## License
Copyright &copy; Crystal Web Service Development Team
```text
MIT License
Copyright (c) 2018 Crystal Web Service

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
```

## Credit
- TURX: Back-end

## Format
### .env
```.env
# This file is a "template" of which env vars need to be defined for your application
# Copy this file to .env file for development, create environment variables when deploying to production
# https://symfony.com/doc/current/best_practices/configuration.html#infrastructure-related-configuration

###> symfony/framework-bundle ###
# For this line, you can set 'prod' for production, or 'dev' for development
APP_ENV=
# You can set your App Secret here
APP_SECRET=
# These two following are optional parameters for Reverse Proxy
#TRUSTED_PROXIES=127.0.0.1,127.0.0.2
#TRUSTED_HOSTS=localhost,example.com
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# Format described at http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# For an SQLite database, use: "sqlite:///%kernel.project_dir%/var/data.db"
# Configure your db driver and server_version in config/packages/doctrine.yaml
# You can configure like "mysql://{username}:{password}@{host}:{port}/{db}"
DATABASE_URL=""
###< doctrine/doctrine-bundle ###

###> symfony/swiftmailer-bundle ###
# For Gmail as a transport, use: "gmail://username:password@localhost"
# For a generic SMTP server, use: "smtp://localhost:25?encryption=&auth_mode="
# Delivery is disabled by default via "null://localhost"
# You can configure like "smtp://{host}:{port}?encryption=ssl&auth_mode=login&username={mail_address}&password={smtp_password}"
MAILER_URL=""
###< symfony/swiftmailer-bundle ###
```

## Command
Production
```bash
composer install # Install this system
```
Development
```bash
php -S localhost:8000 -t public # Open debugging server
php bin/console doctrine:schema:update --force # Update/Create the database with configuration in User entity
php bin/console make:auth # Generate login form
```