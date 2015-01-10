<?php

namespace spec\Laracasts\Behat\ServiceContainer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LaravelFactorySpec extends ObjectBehavior
{

    function it_is_a_behat_driver_factory()
    {
        $this->shouldHaveType('Laracasts\Behat\ServiceContainer\LaravelFactory');
    }

    function it_does_not_support_javascript()
    {
        $this->supportsJavaScript()->shouldReturn(false);
    }

    function it_creates_the_driver_definition()
    {
        $definition = $this->buildDriver([]);

        $definition->shouldHaveType('Symfony\Component\DependencyInjection\Definition');
        $definition->getClass()->shouldBe('Laracasts\Behat\Driver\KernelDriver');

        $arguments = $definition->getArguments();

        $arguments[0]->shouldBeAnInstanceOf('Symfony\Component\DependencyInjection\Reference');
    }

}

