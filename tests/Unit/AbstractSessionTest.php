<?php
/**
 * @author hollodotme
 */

namespace IceHawk\Session\Tests\Unit;

use IceHawk\Session\Tests\Unit\Fixtures\DataMapper;
use IceHawk\Session\Tests\Unit\Fixtures\Session;

class AbstractSessionTest extends \PHPUnit_Framework_TestCase
{
	public function testCanSetValue()
	{
		$sessionData = [ ];
		$session     = new Session( $sessionData );

		$session->setUnitTestValue( 'Unit-Test' );

		$this->assertEquals( 'Unit-Test', $sessionData[ Session::UNIT_TEST_KEY ] );
	}

	public function testCanGetValue()
	{
		$sessionData = [ Session::UNIT_TEST_KEY => 'Unit-Test' ];
		$session     = new Session( $sessionData );

		$this->assertEquals( 'Unit-Test', $session->getUnitTestValue() );
	}

	public function testCanCheckIfKeyIsset()
	{
		$sessionData = [ Session::UNIT_TEST_KEY => 'Unit-Test' ];
		$session     = new Session( $sessionData );

		$this->assertTrue( $session->issetUnitTestValue() );
	}

	public function testCanUnsetValue()
	{
		$sessionData = [ Session::UNIT_TEST_KEY => 'Unit-Test' ];
		$session     = new Session( $sessionData );

		$session->unsetUnitTestValue();

		$this->assertFalse( $session->issetUnitTestValue() );
		$this->assertNull( $session->getUnitTestValue() );
	}

	public function testCanMapAllValues()
	{
		$sessionData = [ ];
		$session     = new Session( $sessionData );
		$session->addDataMapper( new DataMapper( 'Mapped: ' ) );

		$session->setUnitTestValue( 'Unit-Test' );

		$this->assertEquals( 'Mapped: Unit-Test', $sessionData[ Session::UNIT_TEST_KEY ] );
		$this->assertEquals( 'Unit-Test', $session->getUnitTestValue() );
	}

	public function testCanMapSpecificValues()
	{
		$sessionData = [ ];
		$session     = new Session( $sessionData );
		$session->addDataMapper( new DataMapper( 'Mapped: ' ), [ Session::UNIT_TEST_KEY ] );

		$session->setUnitTestValue( 'Unit-Test' );

		$this->assertEquals( 'Mapped: Unit-Test', $sessionData[ Session::UNIT_TEST_KEY ] );
		$this->assertEquals( 'Unit-Test', $session->getUnitTestValue() );
	}

	public function testSpecificMappingHappensFirst()
	{
		$sessionData = [ ];
		$session     = new Session( $sessionData );
		$session->addDataMapper( new DataMapper( 'Globally ' ) );
		$session->addDataMapper( new DataMapper( 'Mapped: ' ), [ Session::UNIT_TEST_KEY ] );
		$session->addDataMapper( new DataMapper( 'Mapped: ' ), [ Session::UNIT_TEST_KEY ] );
		$session->addDataMapper( new DataMapper( 'Check ' ) );

		$session->setUnitTestValue( 'Unit-Test' );

		$this->assertEquals( 'Check Globally Mapped: Unit-Test', $sessionData[ Session::UNIT_TEST_KEY ] );
		$this->assertEquals( 'Unit-Test', $session->getUnitTestValue() );
	}

	public function testCanClearSession()
	{
		$sessionData = [ Session::UNIT_TEST_KEY => 'value' ];
		$session     = new Session( $sessionData );

		$session->clear();

		$this->assertNull( $session->getUnitTestValue() );
		$this->assertEmpty( $sessionData );
	}
}
