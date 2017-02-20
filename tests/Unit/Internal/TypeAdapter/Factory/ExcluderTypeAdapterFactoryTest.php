<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Gson\Test\Unit\Internal\TypeAdapter\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\VoidCache;
use PHPUnit_Framework_TestCase;
use Tebru\Gson\Internal\Data\AnnotationCollectionFactory;
use Tebru\Gson\Internal\Excluder;
use Tebru\Gson\Internal\MetadataFactory;
use Tebru\Gson\Internal\DefaultPhpType;
use Tebru\Gson\Internal\TypeAdapter\ExcluderTypeAdapter;
use Tebru\Gson\Internal\TypeAdapter\Factory\ExcluderTypeAdapterFactory;
use Tebru\Gson\Internal\TypeAdapterProvider;
use Tebru\Gson\Test\Mock\ChildClass;
use Tebru\Gson\Test\Mock\ExcluderExcludeSerializeMock;
use Tebru\Gson\Test\Mock\ExcluderExposeMock;

/**
 * Class ExcluderTypeAdapterFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 * @covers \Tebru\Gson\Internal\TypeAdapter\Factory\ExcluderTypeAdapterFactory
 */
class ExcluderTypeAdapterFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Excluder
     */
    private $excluder;
    
    /**
     * @var ExcluderTypeAdapterFactory
     */
    private $excluderTypeAdapterFactory;

    /**
     * Set up test dependencies
     */
    public function setUp()
    {
        $this->excluder = new Excluder();
        $metadataFactory = new MetadataFactory(new AnnotationCollectionFactory(new AnnotationReader(), new VoidCache()));
        $this->excluderTypeAdapterFactory = new ExcluderTypeAdapterFactory($this->excluder, $metadataFactory);
    }
    
    public function testValidSupportsOnlySerialization()
    {
        self::assertTrue($this->excluderTypeAdapterFactory->supports(new DefaultPhpType(ExcluderExcludeSerializeMock::class)));
    }

    public function testValidSupportsOnlyDeserialization()
    {
        $this->excluder->setRequireExpose(true);

        self::assertTrue($this->excluderTypeAdapterFactory->supports(new DefaultPhpType(ExcluderExposeMock::class)));
    }

    public function testValidSupportsBoth()
    {
        $this->excluder->setRequireExpose(true);

        self::assertTrue($this->excluderTypeAdapterFactory->supports(new DefaultPhpType(ChildClass::class)));
    }

    public function testValidSupportsFalse()
    {
        self::assertFalse($this->excluderTypeAdapterFactory->supports(new DefaultPhpType(ChildClass::class)));
    }

    public function testValidSupportsNonObject()
    {
        self::assertFalse($this->excluderTypeAdapterFactory->supports(new DefaultPhpType('string')));
    }

    public function testCreate()
    {
        $this->excluder->setRequireExpose(true);

        $phpType = new DefaultPhpType(ChildClass::class);
        $typeAdapterProvider = new TypeAdapterProvider([], new ArrayCache());
        $adapter = $this->excluderTypeAdapterFactory->create($phpType, $typeAdapterProvider);

        self::assertInstanceOf(ExcluderTypeAdapter::class, $adapter);
        self::assertAttributeSame($phpType, 'type', $adapter);
        self::assertAttributeSame($typeAdapterProvider, 'typeAdapterProvider', $adapter);
        self::assertAttributeSame(true, 'skipSerialize', $adapter);
        self::assertAttributeSame(true, 'skipDeserialize', $adapter);
    }
}
