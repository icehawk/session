<?php
/**
 * @author h.woltersdorf
 */

namespace IceHawk\Session;

use IceHawk\Session\Interfaces\MapsSessionData;

/**
 * Class SessionRegistry
 * @package IceHawk\Session
 */
class SessionRegistry
{
	/** @var array */
	private $sessionData;

	/** @var array|MapsSessionData[] */
	private $dataMappers;

	public function __construct( array &$sessionData )
	{
		$this->sessionData = &$sessionData;
		$this->dataMappers = [ ];
	}

	final public function addDataMapper( MapsSessionData $dataMapper, array $keys = [ ] )
	{
		if ( empty($keys) )
		{
			$keys = [ '*' ];
		}

		foreach ( array_unique( $keys ) as $key )
		{
			if ( isset($this->dataMappers[ $key ]) )
			{
				if ( !in_array( $dataMapper, $this->dataMappers[ $key ] ) )
				{
					$this->dataMappers[ $key ][] = $dataMapper;
				}
			}
			else
			{
				$this->dataMappers[ $key ] = [ $dataMapper ];
			}
		}
	}

	final protected function set( string $key, $value )
	{
		$this->sessionData[ $key ] = $this->mapValueToSessionData( $key, $value );
	}

	private function mapValueToSessionData( string $key, $value )
	{
		$mappers = $this->getDataMappersForKey( $key );

		foreach ( $mappers as $mapper )
		{
			$value = $mapper->toSessionData( $value );
		}

		return $value;
	}

	/**
	 * @param string $key
	 *
	 * @return array|MapsSessionData[]
	 */
	private function getDataMappersForKey( string $key ) : array
	{
		$mappers = $this->dataMappers[ $key ] ?? [ ];
		$mappers = array_merge( $mappers, $this->dataMappers['*'] ?? [ ] );

		return $mappers;
	}

	final protected function get( string $key )
	{
		return $this->mapValueFromSessionData( $key );
	}

	private function mapValueFromSessionData( string $key )
	{
		if ( $this->isset( $key ) )
		{
			$value   = $this->sessionData[ $key ];
			$mappers = $this->getDataMappersForKey( $key );

			/** @var MapsSessionData $mapper */
			foreach ( array_reverse( $mappers ) as $mapper )
			{
				$value = $mapper->fromSessionData( $value );
			}

			return $value;
		}

		return null;
	}

	final protected function isset( string $key ) : bool
	{
		return isset($this->sessionData[ $key ]);
	}

	final protected function unset( string $key )
	{
		unset($this->sessionData[ $key ]);
	}
}