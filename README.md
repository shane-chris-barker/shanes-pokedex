# Shane's Pokedex 

Shane's Pokedex is a fun little application that allows you to search for information related to Pokemon,
Items and locations from within the Pokemon universe.

It's a fan project and is in no way affiliated or endorsed by or Niantic, Nintendo or any other official Pokemon entity and I do not own any copyright related to Pokemon.

The app is written in PHP and uses the Symfony framework.

## What can it do?

Right now, the app will let you search for any Pokemon, Item or Location from within the Pokemon Universe.

I plan to add more features in the future.

The app was originally written in Laravel and had a few more features -  I am working to restore the features removed during the Symfony 7 rewrite.

# Requirements

The application runs as a Symfony 7 app requiring PHP 8.3 or greater.

Simply install Docker and run 
`docker compose up -d`

# Tests
Tests can be carried out with `PhpUnit` - Simply run `php bin/phpunit`

Test coverage and Github actions will be added in the near future.


# With Thanks

Thank you to the creators and maintainers over at [https://pokeapi.co/](https://pokeapi.co/) for providing the REST Api needed for the app to work.

