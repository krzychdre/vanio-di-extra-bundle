<?php
namespace Vanio\VanioDiExtraBundle\Tests\DependencyInjection\Metadata;

use Vanio\TypeParser\CachingParser;
use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\CachingMetadataFactory;
use Vanio\VanioDiExtraBundle\Tests\Fixtures\AutowiredServices;
use Vanio\VanioDiExtraBundle\Tests\Fixtures\Foo;
use Vanio\VanioDiExtraBundle\Tests\KernelTestCase;

class AutowiringTest extends KernelTestCase
{
    function test_autowiring()
    {
        $this->assertInstanceOf(CachingParser::class, $this->autowiredServices()->typeParser);
        $this->assertInstanceOf(CachingMetadataFactory::class, $this->autowiredServices()->metadataFactory);
        $this->assertInstanceOf(Foo::class, $this->autowiredServices()->foo);
    }

    private function autowiredServices(): AutowiredServices
    {
        return $this->container()->get('vanio_di_extra.tests.autowired_services');
    }
}
