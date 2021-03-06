<?php
namespace Vanio\DiExtraBundle\Tests\DependencyInjection\Metadata;

use Doctrine\Common\Cache\ArrayCache;
use PHPUnit\Framework\TestCase;
use Vanio\DiExtraBundle\DependencyInjection\Metadata\CachingMetadataFactory;
use Vanio\DiExtraBundle\DependencyInjection\Metadata\ClassMetadata;
use Vanio\DiExtraBundle\DependencyInjection\Metadata\ClassMetadataFactory;
use Vanio\DiExtraBundle\Tests\Fixtures\Bar;
use Vanio\DiExtraBundle\Tests\Fixtures\Foo;

class CachingMetadataFactoryTest extends TestCase
{
    /** @var ClassMetadata */
    private $classMetadata;

    /** @var ArrayCache|\PHPUnit_Framework_MockObject_MockObject */
    private $cache;

    /** @var ClassMetadataFactory|\PHPUnit_Framework_MockObject_MockObject */
    private $classMetadataFactory;

    protected function setUp()
    {
        $this->classMetadata = new ClassMetadata(Foo::class);
        $this->classMetadataFactory = $this->createMock(ClassMetadataFactory::class);
        $this->cache = $this->getMockBuilder(ArrayCache::class)->enableProxyingToOriginalMethods()->getMock();
    }

    function test_metadata_for_class_can_be_obtained()
    {
        $this->classMetadataFactory->expects($this->once())
            ->method('getMetadataForClass')
            ->with(Foo::class)
            ->willReturn($this->classMetadata);
        $cacheIdPattern = sprintf(
            '~%s\[.*\Foo\.php\]\[\d+\]\[%s]$~',
            preg_quote(CachingMetadataFactory::class),
            preg_quote(Foo::class)
        );
        $this->cache->expects($this->any())
            ->method('fetch')
            ->with($this->matchesRegularExpression($cacheIdPattern));

        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));

        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));

        $this->assertSame(1, $this->cache->getStats()['hits']);
    }

    function test_metadata_for_child_class_can_be_obtained()
    {
        $this->classMetadataFactory->expects($this->once())
            ->method('getMetadataForClass')
            ->with(Bar::class)
            ->willReturn($this->classMetadata);
        $cacheIdPattern = sprintf(
            '~%s\[.*\Bar\.php\]\[\d+,\d+\]\[%s]$~',
            preg_quote(CachingMetadataFactory::class),
            preg_quote(Bar::class)
        );
        $this->cache->expects($this->any())
            ->method('fetch')
            ->with($this->matchesRegularExpression($cacheIdPattern));

        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Bar::class));

        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Bar::class));
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Bar::class));

        $this->assertSame(1, $this->cache->getStats()['hits']);
    }

    function test_metadata_for_class_can_be_obtained_without_cache_invalidation()
    {
        $this->classMetadataFactory->expects($this->once())
            ->method('getMetadataForClass')
            ->with(Foo::class)
            ->willReturn($this->classMetadata);
        $this->cache->expects($this->any())
            ->method('fetch')
            ->with(sprintf('%s[%s]', CachingMetadataFactory::class, Foo::class));

        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache, false);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));

        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache, false);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));

        $this->assertSame(1, $this->cache->getStats()['hits']);
    }
}
