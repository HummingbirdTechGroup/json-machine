# master
## New features
- [Custom decoder](README.md#custom-decoder)
- `ext-json` is not required in `composer.json` anymore, because custom decoder might not need it.
However **built-in decoders depend on it** so it must be present if you use them.
## BC breaks
- Function `httpClientChunks()` is **deprecated** so that compatibility with Symfony HttpClient
is not on the maintainer of JSON Machine. The code is simple and everyone can make their own
function and maintain it. The code was moved to [examples](src/examples/symfonyHttpClient.php).
- Function `objects()` is **deprecated**. The way `objects()` works is that it casts decoded arrays
to objects. It brings some unnecessary overhead and risks on huge datasets.
Alternative is to configure `ExtJsonDecoder` to decode items as objects.
```php
<?php

use JsonMachine\JsonDecoder\ExtJsonDecoder;
use JsonMachine\JsonMachine;

$jsonMachine = new JsonMachine::fromFile('path/to.json', '', new ExtJsonDecoder);
```
Therefore no additional casting is required.
- Invalid json object keys will now throw and won't be ignored anymore.
## Fixed bugs
- Decoding of json object keys checks for errors and does not silently ignore them.
