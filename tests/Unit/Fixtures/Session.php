<?php declare(strict_types = 1);
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
