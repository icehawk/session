<?php declare(strict_types=1);
/**
 * Copyright (c) 2016 Holger Woltersdorf & Contributors
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 */

namespace IceHawk\Session\Tests\Unit;

use IceHawk\Session\Tests\Unit\Fixtures\DataMapper;
use IceHawk\Session\Tests\Unit\Fixtures\Session;
use PHPUnit\Framework\TestCase;

class AbstractSessionTest extends TestCase
{
	/**
	 * @throws \PHPUnit\Framework\ExpectationFailedException
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 */
	public function testCanSetValue()
	{
		$sessionData = [];
		$session     = new Session( $sessionData );

		$session->setUnitTestValue( 'Unit-Test' );

		$this->assertEquals( 'Unit-Test', $sessionData[ Session::UNIT_TEST_KEY ] );
	}

	/**
	 * @throws \PHPUnit\Framework\ExpectationFailedException
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 */
	public function testCanGetValue()
	{
		$sessionData = [Session::UNIT_TEST_KEY => 'Unit-Test'];
		$session     = new Session( $sessionData );

		$this->assertEquals( 'Unit-Test', $session->getUnitTestValue() );
	}

	/**
	 * @throws \PHPUnit\Framework\ExpectationFailedException
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 */
	public function testCanCheckIfKeyIsset()
	{
		$sessionData = [Session::UNIT_TEST_KEY => 'Unit-Test'];
		$session     = new Session( $sessionData );

		$this->assertTrue( $session->issetUnitTestValue() );
	}

	/**
	 * @throws \PHPUnit\Framework\ExpectationFailedException
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 */
	public function testCanUnsetValue()
	{
		$sessionData = [Session::UNIT_TEST_KEY => 'Unit-Test'];
		$session     = new Session( $sessionData );

		$session->unsetUnitTestValue();

		$this->assertFalse( $session->issetUnitTestValue() );
		$this->assertNull( $session->getUnitTestValue() );
	}

	/**
	 * @throws \PHPUnit\Framework\ExpectationFailedException
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 */
	public function testCanMapAllValues()
	{
		$sessionData = [];
		$session     = new Session( $sessionData );
		$session->addDataMapper( new DataMapper( 'Mapped: ' ) );

		$session->setUnitTestValue( 'Unit-Test' );

		$this->assertEquals( 'Mapped: Unit-Test', $sessionData[ Session::UNIT_TEST_KEY ] );
		$this->assertEquals( 'Unit-Test', $session->getUnitTestValue() );
	}

	/**
	 * @throws \PHPUnit\Framework\ExpectationFailedException
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 */
	public function testCanMapSpecificValues()
	{
		$sessionData = [];
		$session     = new Session( $sessionData );
		$session->addDataMapper( new DataMapper( 'Mapped: ' ), [Session::UNIT_TEST_KEY] );

		$session->setUnitTestValue( 'Unit-Test' );

		$this->assertEquals( 'Mapped: Unit-Test', $sessionData[ Session::UNIT_TEST_KEY ] );
		$this->assertEquals( 'Unit-Test', $session->getUnitTestValue() );
	}

	/**
	 * @throws \PHPUnit\Framework\ExpectationFailedException
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 */
	public function testSpecificMappingHappensFirst()
	{
		$sessionData = [];
		$session     = new Session( $sessionData );
		$session->addDataMapper( new DataMapper( 'Globally ' ) );
		$session->addDataMapper( new DataMapper( 'Mapped: ' ), [Session::UNIT_TEST_KEY] );
		$session->addDataMapper( new DataMapper( 'Mapped: ' ), [Session::UNIT_TEST_KEY] );
		$session->addDataMapper( new DataMapper( 'Check ' ) );

		$session->setUnitTestValue( 'Unit-Test' );

		$this->assertEquals( 'Check Globally Mapped: Unit-Test', $sessionData[ Session::UNIT_TEST_KEY ] );
		$this->assertEquals( 'Unit-Test', $session->getUnitTestValue() );
	}

	/**
	 * @throws \PHPUnit\Framework\ExpectationFailedException
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 */
	public function testCanClearSession()
	{
		$sessionData = [Session::UNIT_TEST_KEY => 'value'];
		$session     = new Session( $sessionData );

		$session->clear();

		$this->assertNull( $session->getUnitTestValue() );
		$this->assertEmpty( $sessionData );
	}
}
