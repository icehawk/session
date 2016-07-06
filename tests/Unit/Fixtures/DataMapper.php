<?php
/**
 * @author hollodotme
 */

namespace IceHawk\Session\Tests\Unit\Fixtures;

use IceHawk\Session\Interfaces\MapsSessionData;

/**
 * Class DataMapper
 * @package IceHawk\Session\Tests\Unit\Fixtures
 */
final class DataMapper implements MapsSessionData
{
	/** @var string */
	private $prefix;

	public function __construct( string $prefix )
	{
		$this->prefix = $prefix;
	}

	public function toSessionData( $value )
	{
		return $this->prefix . $value;
	}

	public function fromSessionData( $sessionData )
	{
		$prefixQuoted = preg_quote( $this->prefix, '#' );

		return preg_replace( "#^{$prefixQuoted}#", '', $sessionData );
	}
}