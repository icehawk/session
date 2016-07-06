<?php
/**
 * @author hollodotme
 */

namespace IceHawk\Session\Tests\Unit\Fixtures;

use IceHawk\Session\AbstractSession;

/**
 * Class Session
 * @package IceHawk\Session\Tests\Unit\Fixtures
 */
final class Session extends AbstractSession
{
	const UNIT_TEST_KEY = 'unitTestKey';

	public function getUnitTestValue()
	{
		return $this->get( self::UNIT_TEST_KEY );
	}

	public function setUnitTestValue( $value )
	{
		$this->set( self::UNIT_TEST_KEY, $value );
	}

	public function issetUnitTestValue() : bool
	{
		return $this->isset( self::UNIT_TEST_KEY );
	}

	public function unsetUnitTestValue()
	{
		$this->unset( self::UNIT_TEST_KEY );
	}
}