# Shane's Pokedex
Shane's Pokedex is a fun little Laravel application that uses the [PokeApi](https://pokeapi.co) to search for data and return results.

The application currently allows for the searching of any Pokemon, attack or item by name or ID.

# Requirements
- PHP 7.1.3 or greater.
- Laravel 5.7 or greater.

# Installation

Installation is carried out via Composer. Simply clone the repository and run ```composer install```

# Caching
Be nice to the PokeApi, speed up the application and save on bandwidth by enabling Redis.
Caching is disabled by default but can easily be enabled:
- Ensure that Redis is installed and configured on your server.
- Ensure the settings in ```config/database.php``` match to your required Redis configuration.
- Ensure the setting in the ```.env``` file is set to ```CACHE_WITH_REDIS=true```

# Have fun!
