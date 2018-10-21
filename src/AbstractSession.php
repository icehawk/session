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

namespace IceHawk\Session;

use IceHawk\Session\Interfaces\MapsSessionData;
use function in_array;

/**
 * Class AbstractSession
 * @package IceHawk\Session
 */
abstract class AbstractSession
{
	/** @var array */
	private $sessionData;

	/** @var array */
	private $keyDataMappers;

	/** @var array */
	private $globalDataMappers;

	public function __construct( array &$sessionData )
	{
		$this->sessionData       = &$sessionData;
		$this->keyDataMappers    = [];
		$this->globalDataMappers = [];
	}

	final public function addDataMapper( MapsSessionData $dataMapper, array $keys = [] )
	{
		if ( empty( $keys ) )
		{
			$this->addGlobalDataMapper( $dataMapper );
		}
		else
		{
			foreach ( array_unique( $keys ) as $key )
			{
				$this->addKeyDataMapper( $dataMapper, $key );
			}
		}
	}

	private function addGlobalDataMapper( MapsSessionData $dataMapper )
	{
		if ( !in_array( $dataMapper, $this->globalDataMappers, false ) )
		{
			$this->globalDataMappers[] = $dataMapper;
		}
	}

	private function addKeyDataMapper( MapsSessionData $dataMapper, string $key )
	{
		if ( isset( $this->keyDataMappers[ $key ] ) )
		{
			if ( !in_array( $dataMapper, $this->keyDataMappers[ $key ], false ) )
			{
				$this->keyDataMappers[ $key ][] = $dataMapper;
			}
		}
		else
		{
			$this->keyDataMappers[ $key ] = [$dataMapper];
		}
	}

	final protected function set( string $key, $value )
	{
		$this->sessionData[ $key ] = $this->mapValueToSessionData( $key, $value );
	}

	private function mapValueToSessionData( string $key, $value )
	{
		$keyDataMappers = $this->keyDataMappers[ $key ] ?? [];

		/** @var MapsSessionData $keyDataMapper */
		foreach ( $keyDataMappers as $keyDataMapper )
		{
			$value = $keyDataMapper->toSessionData( $value );
		}

		foreach ( $this->globalDataMappers as $globalDataMapper )
		{
			$value = $globalDataMapper->toSessionData( $value );
		}

		return $value;
	}

	final protected function get( string $key )
	{
		return $this->mapValueFromSessionData( $key );
	}

	private function mapValueFromSessionData( string $key )
	{
		if ( $this->isset( $key ) )
		{
			$value             = $this->sessionData[ $key ];
			$globalDataMappers = array_reverse( $this->globalDataMappers );
			$keyDataMappers    = array_reverse( $this->keyDataMappers[ $key ] ?? [] );

			/** @var MapsSessionData $globalDataMapper */
			foreach ( $globalDataMappers as $globalDataMapper )
			{
				$value = $globalDataMapper->fromSessionData( $value );
			}

			/** @var MapsSessionData $keyDataMapper */
			foreach ( $keyDataMappers as $keyDataMapper )
			{
				$value = $keyDataMapper->fromSessionData( $value );
			}

			return $value;
		}

		return null;
	}

	final protected function isset( string $key ) : bool
	{
		return isset( $this->sessionData[ $key ] );
	}

	final protected function unset( string $key )
	{
		unset( $this->sessionData[ $key ] );
	}

	public function clear()
	{
		$this->sessionData = [];
	}
}
