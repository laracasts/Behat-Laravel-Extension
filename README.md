This extension offers an incredibly simple (and fast) way to begin testing and driving your Laravel applications with Behat. Some benefits include:

- **Fast:** It doesn't depend on anything like Goutte, so it offers a super-fast way to test your UI. You don't even need to setup a host to run your tests.
- **Refresh:** Laravel is automatically rebooted before each scenario (so nothing like user sessions will be persisted).
- **Environments:** Specifying custom environment files (like the `.env` one) for different app environments is a little tricky in Laravel 5. This extension handles that for you automatically. By default, it'll look for a `.env.behat` file in your project root.
- **Access Laravel:** You instantly have access to Laravel (things like facades and such) from your `FeatureContext` file.
- **Workflow:** A number of useful traits are available, which will speed up your workflow.

# 1. Install Dependencies

As always, we need to pull in some dependencies through Composer.

    composer require behat/behat behat/mink behat/mink-extension cevinio/behat-laravel-extension --dev

This will give us access to Behat, Mink, and, of course, the Laravel extension.

# 2. Create the Behat.yml Configuration File

Next, within your project root, create a `behat.yml` file, and add:

```
default:
    extensions:
        Cevinio\Behat:
            # env_path: .env.behat
        Behat\MinkExtension:
            default_session: laravel
            laravel: ~
```

Here, is where we reference the Laravel extension, and tell Behat to use it as our default session. You may pass an optional parameter, `env_path` (currently commented out above) to specify the name of the environment file that should be referenced from your tests. By default, it'll look for a `.env.behat` file.

This file should, like the standard `.env` file in your project root, contain any special environment variables
for your tests (such as a special acceptance test-specific database).

# 3. Setting up FeatureContext

Run, from the root of your app

~~~
vendor/bin/behat --init 
~~~

It should set 

~~~
features/bootstrap/FeatureContext.php
~~~ 

At this point you should set it to extend MinkContext. 

~~~

<?php

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
#This will be needed if you require "behat/mink-selenium2-driver"
#use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{

~~~ 


# 4. Write Some Features

You're all set to go! Start writing some features.

> Note: if you want to leverage some of the Mink helpers in your `FeatureContext` file, then be sure to extend `Behat\MinkExtension\Context\MinkContext`.

## FAQ

### I'm getting a "PHP Fatal error: Maximum function nesting level of '100' reached, aborting!" error.

Sounds like you're using Xdebug. [Increase the max nesting level](http://xdebug.org/docs/all_settings#max_nesting_level).
