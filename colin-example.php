<?php
use deswerve\colin\CommandLineInterface;

require_once 'vendor/autoload.php';

// use the name of the script file or symlink as app name <-.
//                                          .---------------'
//                                 .--------'-------.
$colin = (new CommandLineInterface(basename($argv[0]), ['OPTIONS']))
    ->addOption('redis', 'Host (and port if not 6379) to Redis server, e. g. "example.com:6379"', ['HOSTPORT'], 'r')
    ->addOption('redis-db-index', 'Redis database index, default 0', ['NUM'], 'i', true, [0])
    ->addOption(
        'redis-secret',     // long name, given as --redis-secret on the command line
        'Redis secret key', // description of this switch
        ['PASSWORD'],       // placeholders for the number of expected values <-.
        's',                // short name, given as -s on the command line      |
        true,               // is this switch optional?                         |
        [null]              // default values, must match the length of --------'
    )
    ->addOption('hexastore', 'Key of sorted set for hexastore, default "hexastore"', ['NAME'], 'h', true, ['hexastore'])
    ->addOption('username', 'Market user', ['NAME'], 'u')
    ->addOption('phrase', 'Market password', ['PASSWORD'], 'p')
    ->addOption('verbose', 'Increase verbosity', [], 'v', true)
    ->addOption('vector', '3D vector', ['X', 'Y', 'Z']);

// print usage info; usually you would only do this only, when the config is incomplete or --help is used
echo $colin, PHP_EOL;

// show the actual input... (just for demonstration)
echo basename($argv[0]), ' ', implode(' ', array_slice($argv, 1)), PHP_EOL;

// ... in comparison with the generated data structure
echo preg_replace(
    '<\\n {8}(?:(})| {4,})>',
    ' \\1',
    json_encode($colin->processCommandLine($argv), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);
__halt_compiler();

Usage:
  colin-example.php OPTIONS

Options (required):
  -r  --redis HOSTPORT   Host (and port if not 6379) to Redis server, e. g. "example.com:6379"
  -u  --username NAME    Market user
  -p  --phrase PASSWORD  Market password
      --vector X Y Z     3D vector

Options (optional):
  -i  --redis-db-index NUM     Redis database index, default 0
  -s  --redis-secret PASSWORD  Redis secret key
  -h  --hexastore NAME         Key of sorted set for hexastore, default "hexastore"
  -v  --verbose                Increase verbosity

colin-example.php -r localhost:16379 -vv --vector 2 3.14 19 -u=alice extra params --phrase secret_passphrase --verbose
{
    "options": {
        "redis": { "values": [ "localhost:16379" ], "count": 1 },
        "redis-db-index": { "values": [ 0 ], "count": 0 },
        "redis-secret": { "values": [ null ], "count": 0 },
        "hexastore": { "values": [ "hexastore" ], "count": 0 },
        "username": { "values": [ "alice" ], "count": 1 },
        "phrase": { "values": [ "secret_passphrase" ], "count": 1 },
        "verbose": { "values": [], "count": 3 },
        "vector": { "values": [ "2", "3.14", "19" ], "count": 1 }
    },
    "params": [
        "extra",
        "params"
    ]
}
