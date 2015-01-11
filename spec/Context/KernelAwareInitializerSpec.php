<?php

namespace spec\Laracasts\Behat\Context;

use Behat\Behat\Context\Context;
use Laracasts\Behat\Context\KernelAwareContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class KernelAwareInitializerSpec extends ObjectBehavior
{

    function let(HttpKernelInterface $kernel)
    {
        $this->beConstructedWith($kernel);
    }

    function it_is_an_event_subscriber()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_is_a_context_initializer()
    {
        $this->shouldHaveType('Behat\Behat\Context\Initializer\ContextInitializer');
    }

    function it_subscribes_to_a_number_of_events()
    {
        $this->getSubscribedEvents()->shouldBeArray();
    }

    function it_sets_the_kernel_on_the_context_if_its_kernel_aware(
        KernelAwareContext $context,
        HttpKernelInterface $kernel
    ) {
        $context->setApp($kernel)->shouldBeCalled();

        $this->initializeContext($context);
    }

    function it_does_nothing_if_the_context_is_not_kernel_aware(Context $context, $kernel)
    {
        $this->initializeContext($context)->shouldBe(null);
    }

}
