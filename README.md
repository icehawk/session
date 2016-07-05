[![Build Status](https://travis-ci.org/icehawk/session.svg?branch=master)](https://travis-ci.org/icehawk/session)
[![Coverage Status](https://coveralls.io/repos/github/icehawk/session/badge.svg?branch=master)](https://coveralls.io/github/icehawk/session?branch=master)
[![Latest Stable Version](https://poser.pugx.org/icehawk/session/v/stable)](https://packagist.org/packages/icehawk/session) 
[![Total Downloads](https://poser.pugx.org/icehawk/session/downloads)](https://packagist.org/packages/icehawk/session) 
[![Latest Unstable Version](https://poser.pugx.org/icehawk/session/v/unstable)](https://packagist.org/packages/icehawk/session) 
[![License](https://poser.pugx.org/icehawk/session/license)](https://packagist.org/packages/icehawk/session)

# IceHawk\Session

Session registry component for IceHawk framework

## Intention

This component is intended to wrap the super-global `$_SESSION` variable and give 
access to values by explicitly user-defined keys and value/return types 
and is therefor declared abstract.

Furthermore it provides the registration and interfaces for data mappers on all, several or single keys, e.g. to reduce data overhead stored in session.

## Usage

### Extend the class `AbstractSession`
 
```php
<?php declare(strict_types=1);

namespace Vendor\Project;

use IceHawk\Session\AbstractSession;

final class Session extends AbstractSession
{
    const KEY_SOME_STRING_VALUE = 'someStringValue';
    
    public function getSomeStringValue() : string
    {
        return $this->get( self::KEY_SOME_STRING_VALUE );
    }

    public function setSomeStringValue( string $value )
    {
        $this->set( self::KEY_SOME_STRING_VALUE, $value );
    }

    public function isSomeStringValueSet() : bool
    {
        return $this->isset( self::KEY_SOME_STRING_VALUE );
    }

    public function unsetSomeStringValue()
    {
        $this->unset( self::KEY_SOME_STRING_VALUE );
    }
}
```

### Work with values

```php
<?php declare(strict_types=1);

namespace Vendor\Project;

$session = new Session( $_SESSION );

# Set
$session->setSomeStringValue( 'Hello world' );

# Isset
if ( $session->isSomeStringValueSet() )
{
    # Get
    $someString = $session->getSomeStringValue();
    
    echo $someString . ' was set.';
}

# Unset 
$session->unsetSomeStringValue();
```