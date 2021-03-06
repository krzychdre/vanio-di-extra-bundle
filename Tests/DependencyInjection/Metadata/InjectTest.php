<?php
namespace Vanio\DiExtraBundle\Tests\DependencyInjection\Metadata;

use PHPUnit\Framework\TestCase;
use Vanio\DiExtraBundle\DependencyInjection\Metadata\Inject;

class InjectTest extends TestCase
{
    function test_id_can_be_obtained()
    {
        $this->assertNull((new Inject)->id());
        $this->assertSame('id', (new Inject(['value' => 'id']))->id());
        $this->assertSame('id', Inject::byId('id')->id());
    }

    function test_type_can_be_obtained()
    {
        $this->assertNull((new Inject)->type());
        $this->assertSame('type', Inject::byType('type')->type());
    }

    function test_parameter_can_be_obtained()
    {
        $this->assertNull((new Inject)->parameter());
        $this->assertSame('%parameter%', (new Inject(['value' => '%parameter%']))->parameter());
        $this->assertSame('%parameter%/foo', Inject::byParameter('%parameter%/foo')->parameter());
    }

    function test_it_can_be_required()
    {
        $this->assertTrue((new Inject(['value' => 'id', 'required' => true]))->isRequired());
        $this->assertTrue(Inject::byId('id', true)->isRequired());
        $this->assertTrue(Inject::byType('type', true)->isRequired());
    }

    function test_it_does_not_have_to_be_required()
    {
        $this->assertTrue((new Inject(['value' => 'id']))->isRequired());
        $this->assertTrue(Inject::byId('id')->isRequired());
        $this->assertTrue(Inject::byType('type')->isRequired());
    }
}
