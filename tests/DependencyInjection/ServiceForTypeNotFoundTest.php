<?php
namespace Vanio\VanioDiExtraBundle\Tests\DependencyInjection;

use Vanio\VanioDiExtraBundle\DependencyInjection\ServiceForTypeNotFound;

class ServiceForTypeNotFoundTest extends \PHPUnit_Framework_TestCase
{
    function test_message_can_be_obtained()
    {
        $message = (new ServiceForTypeNotFound('type'))->getMessage();
        $this->assertSame('You have requested a service for non-resolvable type "type".', $message);
    }

    function test_type_can_be_obtained()
    {
        $this->assertSame('type', (new ServiceForTypeNotFound('type'))->type());
    }
}