<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\FormTypeGuesserChain;
use Symfony\Component\Form\Tests\Fixtures\TestExtension;
use Symfony\Component\Form\Tests\Fixtures\FooSubTypeWithParentInstance;
use Symfony\Component\Form\Tests\Fixtures\FooSubType;
use Symfony\Component\Form\Tests\Fixtures\FooTypeBazExtension;
use Symfony\Component\Form\Tests\Fixtures\FooTypeBarExtension;
use Symfony\Component\Form\Tests\Fixtures\FooType;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FormRegistryTest extends TestCase
{
    /**
     * @var FormRegistry
     */
    private $registry;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $resolvedTypeFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $guesser1;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $guesser2;

    /**
     * @var TestExtension
     */
    private $extension1;

    /**
     * @var TestExtension
     */
    private $extension2;

    protected function setUp()
    {
        $this->resolvedTypeFactory = $this->getMockBuilder('Symfony\Component\Form\ResolvedFormTypeFactory')->getMock();
        $this->guesser1 = $this->getMockBuilder('Symfony\Component\Form\FormTypeGuesserInterface')->getMock();
        $this->guesser2 = $this->getMockBuilder('Symfony\Component\Form\FormTypeGuesserInterface')->getMock();
        $this->extension1 = new TestExtension($this->guesser1);
        $this->extension2 = new TestExtension($this->guesser2);
        $this->registry = new FormRegistry(array(
            $this->extension1,
            $this->extension2,
        ), $this->resolvedTypeFactory);
    }

    public function testGetTypeFromExtension()
    {
        $type = new FooType();
        $resolvedType = $this->getMockBuilder('Symfony\Component\Form\ResolvedFormTypeInterface')->getMock();

        $this->extension2->addType($type);

        $this->resolvedTypeFactory->expects($this->once())
            ->method('createResolvedType')
            ->with($type)
            ->will($this->returnValue($resolvedType));

        $resolvedType->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $resolvedType = $this->registry->getType('foo');

        $this->assertSame($resolvedType, $this->registry->getType('foo'));
    }

    public function testGetTypeWithTypeExtensions()
    {
        $type = new FooType();
        $ext1 = new FooTypeBarExtension();
        $ext2 = new FooTypeBazExtension();
        $resolvedType = $this->getMockBuilder('Symfony\Component\Form\ResolvedFormTypeInterface')->getMock();

        $this->extension2->addType($type);
        $this->extension1->addTypeExtension($ext1);
        $this->extension2->addTypeExtension($ext2);

        $this->resolvedTypeFactory->expects($this->once())
            ->method('createResolvedType')
            ->with($type, array($ext1, $ext2))
            ->will($this->returnValue($resolvedType));

        $resolvedType->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $this->assertSame($resolvedType, $this->registry->getType('foo'));
    }

    public function testGetTypeConnectsParent()
    {
        $parentType = new FooType();
        $type = new FooSubType();
        $parentResolvedType = $this->getMockBuilder('Symfony\Component\Form\ResolvedFormTypeInterface')->getMock();
        $resolvedType = $this->getMockBuilder('Symfony\Component\Form\ResolvedFormTypeInterface')->getMock();

        $this->extension1->addType($parentType);
        $this->extension2->addType($type);

        $this->resolvedTypeFactory->expects($this->at(0))
            ->method('createResolvedType')
            ->with($parentType)
            ->will($this->returnValue($parentResolvedType));

        $this->resolvedTypeFactory->expects($this->at(1))
            ->method('createResolvedType')
            ->with($type, array(), $parentResolvedType)
            ->will($this->returnValue($resolvedType));

        $parentResolvedType->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $resolvedType->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo_sub_type'));

        $this->assertSame($resolvedType, $this->registry->getType('foo_sub_type'));
    }

    public function testGetTypeConnectsParentIfGetParentReturnsInstance()
    {
        $type = new FooSubTypeWithParentInstance();
        $parentResolvedType = $this->getMockBuilder('Symfony\Component\Form\ResolvedFormTypeInterface')->getMock();
        $resolvedType = $this->getMockBuilder('Symfony\Component\Form\ResolvedFormTypeInterface')->getMock();

        $this->extension1->addType($type);

        $this->resolvedTypeFactory->expects($this->at(0))
            ->method('createResolvedType')
            ->with($this->isInstanceOf('Symfony\Component\Form\Tests\Fixtures\FooType'))
            ->will($this->returnValue($parentResolvedType));

        $this->resolvedTypeFactory->expects($this->at(1))
            ->method('createResolvedType')
            ->with($type, array(), $parentResolvedType)
            ->will($this->returnValue($resolvedType));

        $parentResolvedType->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $resolvedType->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo_sub_type_parent_instance'));

        $this->assertSame($resolvedType, $this->registry->getType('foo_sub_type_parent_instance'));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\InvalidArgumentException
     */
    public function testGetTypeThrowsExceptionIfTypeNotFound()
    {
        $this->registry->getType('bar');
    }

    public function testHasTypeAfterLoadingFromExtension()
    {
        $type = new FooType();
        $resolvedType = $this->getMockBuilder('Symfony\Component\Form\ResolvedFormTypeInterface')->getMock();

        $this->resolvedTypeFactory->expects($this->once())
            ->method('createResolvedType')
            ->with($type)
            ->will($this->returnValue($resolvedType));

        $resolvedType->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $this->assertFalse($this->registry->hasType('foo'));

        $this->extension2->addType($type);

        $this->assertTrue($this->registry->hasType('foo'));
    }

    public function testGetTypeGuesser()
    {
        $expectedGuesser = new FormTypeGuesserChain(array($this->guesser1, $this->guesser2));

        $this->assertEquals($expectedGuesser, $this->registry->getTypeGuesser());

        $registry = new FormRegistry(
            array($this->getMockBuilder('Symfony\Component\Form\FormExtensionInterface')->getMock()),
            $this->resolvedTypeFactory);

        $this->assertNull($registry->getTypeGuesser());
    }

    public function testGetExtensions()
    {
        $expectedExtensions = array($this->extension1, $this->extension2);

        $this->assertEquals($expectedExtensions, $this->registry->getExtensions());
    }
}
