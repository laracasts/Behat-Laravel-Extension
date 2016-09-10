This extension offers an incredibly simple (and fast) way to begin testing and driving your Laravel or Lumen applications with Behat. Some benefits include:

- **Fast:** It doesn't depend on anything like Goutte, so it offers a super-fast way to test your UI. You don't even need to setup a host to run your tests.
- **Refresh:** Laravel is automatically rebooted after each scenario (so nothing like user sessions will be persisted).
- **Environments:** Specifying custom environment files (like the `.env` one) for different app environments is a little tricky in Laravel 5. This extension handles that for you automatically. By default, it'll look for a `.env.behat` file in your project root.
- **Access Laravel:** You instantly have access to Laravel (things like facades and such) from your `FeatureContext` file.
- **Workflow:** A number of useful traits are available, which will speed up your workflow.


## Sections
  * [Laravel](#laravel)
  * [Lumen](#lumen)


# Laravel 
To get started, you only need to follow a few steps:

> Prefer a video walk-through? [See this lesson from Laracasts](https://laracasts.com/lessons/laravel-5-and-behat-bffs).

# 1. Install Dependencies

As always, we need to pull in some dependencies through Composer.

    composer require behat/behat behat/mink behat/mink-extension laracasts/behat-laravel-extension --dev

This will give us access to Behat, Mink, and, of course, the Laravel extension.

# 2. Create the Behat.yml Configuration File

Next, within your project root, create a `behat.yml` file, and add:

```
default:
    extensions:
        Laracasts\Behat:
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

![example](https://dl.dropboxusercontent.com/u/774859/Work/BehatLaravelExtension/example.png)

You're all set to go! Start writing some features. If you want a quick dummy example to get you started, refer to [this project](https://github.com/laracasts/Behat-Laravel-Extension-Example-App).

> Note: if you want to leverage some of the Mink helpers in your `FeatureContext` file, then be sure to extend `Behat\MinkExtension\Context\MinkContext`.

## Feature Context Traits

As a convenience, this package also includes a number of traits to streamline common tasks, such as migrating your database, or using database transactions, or even testing mail.

### Migrator

Often, you'll find yourself in situations where you want to migrate your test database before a scenario. Easy! Just pull in the `Laracasts\Behat\Context\Migrator` trait into your `FeatureContext`, like so:

```php
// ...

use Laracasts\Behat\Context\Migrator;

class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    use Migrator;

}
```

That's it! The trait will do the rest. Before each scenario runs, if your database needs to be migrated, it will be!

### Database Transactions

On the other hand, you might prefer to run all of your tests through database transactions. You'll get a nice speed boost out of the deal, as your data will never actually be saved to the database. To take advantage of this, once again, pull in the `Laracasts\Behat\Context\DatabaseTransactions` trait, like so:

```php
// ...

use Laracasts\Behat\Context\DatabaseTransactions;

class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    use DatabaseTransactions;

}
```

Once you pull in this trait, before each scenario runs, it'll begin a new transaction. And when the scenario completes, we'll roll it back for you.

### Service: MailTrap

Especially when functional testing, it can be beneficial to test your mail against a real test server (rather than mocking it out, and hoping that things were formatted correctly). If you're a fan of [MailTrap](https://mailtrap.io/) (highly recommended), this extension can help you!

If you haven't already, create a quick account (free) and inbox at MailTrap.io. Next, update either your `config/mail.php` to use the settings that MailTrap provides you, or modify your `.env.behat` variables to reference them. Once you've configured your app to use MailTrap, the only other thing we need is your MailTrap API key, and the default inbox id that we should use. Makes these available in `config/services.php`. Here's an example:

```php
'mailtrap' => [
    'secret' => 'YOUR API KEY',
    'default_inbox' => 'ID OF THE MAILTRAP INBOX YOU CREATED'
]
```

That should do it! Now, just pull in the trait, and you're ready to go! Let me show you:

```php
<?php

// ...

use Laracasts\Behat\Context\Services\MailTrap;
use PHPUnit_Framework_Assert as PHPUnit;

class DmcaContext extends MinkContext implements SnippetAcceptingContext
{
    use MailTrap;

    /**
     * @Then an email should be sent to YouTube
     */
    public function anEmailShouldBeSentToYoutube()
    {
        $lastEmail = $this->fetchInbox()[0];
        $stub = file_get_contents(__DIR__ . '/../stubs/dmca-complete.txt');

        PHPUnit::assertEquals('DMCA Notice', $lastEmail['subject']);
        PHPUnit::assertContains($stub, $lastEmail['text_body']);
    }

}
```

Notice that call to `fetchInbox()`? That will send an API request to MailTrap, which will return to you an array of all the messages/emails in your inbox. As such, if you want to write some assertions against the most recently received email in your MailTrap inbox, you can do:

```php
$lastEmail = $this->fetchInbox()[0];
```

If working along, you can dump that variable to see all of the various fields that you may write assertions against. In the example above, we're ensuring that the subject was set correctly, and the body of the email matches a stub that we've created.

Even better, after each scenario completes, we'll go ahead and empty out your MailTrap inbox for convenience by adding the `@mail` tag to the scenario or right above the Feature

```
  @mail
  Scenario: User will get notified
    Given there are jobs that have been started by this user
    Then when the report is done the user will get an email to let them know it is done
```

## FAQ

### I'm getting a "PHP Fatal error: Maximum function nesting level of '100' reached, aborting!" error.

Sounds like you're using Xdebug. [Increase the max nesting level](http://xdebug.org/docs/all_settings#max_nesting_level).


# Lumen


## This version (v2.0.1) is compatible with Lumen 5.2; for older versions use [v1.0.0](https://github.com/arisro/behat-lumen-extension/releases/tag/v1.0.0)

This is written by [Aris Buzachis](https://github.com/arisro)

The original repo is [here](https://github.com/arisro/behat-lumen-extension)

It's a custom Behat / Mink driver which extends the BrowserKit driver.

This will allow you to write functional tests using Behat (boot the Lumen application in a custom environment, mock components from the FeatureContext, requests are done directly on the application - no external requests).

To get started, you only need to follow a few steps:

# 1. Install Dependencies

As always, we need to pull in some dependencies through Composer.

    composer require behat/behat behat/mink behat/mink-extension arisro/behat-lumen-extension --dev

This will give us access to Behat, Mink, and, of course, the Lumen extension.

If you want to use a custom .env file for the Behat tests you will need to modify `bootstrap/app.php` like this:

```php
try {
    (new Dotenv\Dotenv(__DIR__.'/../', isset($dotEnvFile) ?: '.env'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}
```

# 2. Create the behat.yml configuration file

Next, within your project root, create a `behat.yml` file, and add:

```yml
default:
  extensions:
    Laracasts\Behat\ServiceContainer\LumenExtension:
      # env_file: .env.behat
    Behat\MinkExtension:
      default_session: lumen
      lumen: ~
  suites:
    default:
      contexts: [ FeatureContext ]
```

Optinally, you can specify a different .env file for your functional tests (with a test DB for example).

# 3. Write Some Features

You have a very small example here https://github.com/arisro/behat-lumen-example.

Note: if you want to leverage some of the Mink helpers in your `FeatureContext` file, then be sure to extend `Behat\MinkExtension\Context\MinkContext`.

