<?php
/**
 * @author hollodotme
 */

namespace IceHawk\Session\Interfaces;

/**
 * Interface MapsSessionData
 * @package IceHawk\Session\Interfaces
 */
interface MapsSessionData
{
	public function toSessionData( $value );

	public function fromSessionData( $sessionData );
}