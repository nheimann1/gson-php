# Advanced Usage

Use the `GsonBuilder` to handle all configuration of how serialization
or deserialization should be accomplished.

Adding Type Adapter Factories
-----------------------------

These will be checked before most of the default type adapters.

```php
Gson::builder()
    ->addTypeAdapterFactory(new FooTypeAdapterFactory())
    ->build();
```

Adding Type Adapters, Serializers, or Deserializers
---------------------------------------------------

```php
Gson::builder()
    ->registerType('array', new MyBetterArrayTypeAdapter())
    ->build();
```

```php
Gson::builder()
    ->registerType(Foo::class, new FooSerializer())
    ->build();
```

```php
Gson::builder()
    ->registerType(Bar::class, new BarDeserializer())
    ->build();
```

Add an Instance Creator
-----------------------

```php
Gson::builder()
    ->addInstanceCreator(FooBar::class, new FooBarInstanceCreator())
    ->build();
```

Set the DateTime format
-----------------------

Formatting DateTimes defaults to using DateTime::ATOM

```php
Gson::builder()
    ->setDateTimeFormat(DateTime::RFC2822)
    ->build();
```

Set the Version
---------------

```php
Gson::builder()
    ->setVersion('1.0')
    ->build();
```

Set Excluded Modfiers
---------------------

```php
Gson::builder()
    ->setExcludedModifiers(ReflectionProperty::IS_PROTECTED)
    ->build();
```

Require the Expose Annotation
-----------------------------

```php
Gson::builder()
    ->requireExposeAnnotation()
    ->build();
```

Add Exclusion Strategy
----------------------

You must specify whether it should be enabled for serialization,
deserialization, or both.

```php
Gson::builder()
    ->addExclusionStrategy(new FooExclusionStrategy(), true, true)
    ->build();
```

Enable Serializing Nulls
------------------------

```php
Gson::builder()
    ->serializeNull()
    ->build();
```

Enable Cache
------------

To enable the cache, set a cache directory and set the enable cache flag
to true.

```php
Gson::builder()
    ->enableCache(true)
    ->setCacheDir('/tmp')
    ->build();
```

Change Property Naming
----------------------

By default, all property names are converted to snake_case during
serialization, but you can override this behavior by setting a new
PropertyNamingPolicy to the builder.

```php
Gson::builder()
    ->setPropertyNamingPolicy(PropertyNamingPolicy::IDENTITY)
    ->build();
```

Here's a list of what's supported:

- IDENTITY: Leave property name unchanged
- LOWER_CASE_WITH_DASHES: Convert camel case to lower case separated by dashes
- LOWER_CASE_WITH_UNDERSCORES: Convert camel case to lower case separated by underscores
- UPPER_CASE_WITH_DASHES: Convert camel case to upper case separated by dashes
- UPPER_CASE_WITH_UNDERSCORES: Convert camel case to upper case separated by underscores
- UPPER_CAMEL_CASE: Capitalize the first letter of a camel case property
- UPPER_CAMEL_CASE_WITH_SPACES: Converts camel case to capitalized words

You can customize this further by implementing a
`PropertyNamingStrategy` and adding it to the builder.

```php
Gson::builder()
    ->setPropertyNamingStrategy(new MyCustomPropertyNamingStrategy())
    ->build();
```

Add Method Naming Strategy
--------------------------

By default, method names will be property names prepended with `get`,
`is`, or `set`.  Override this by implementing the `MethodNamingStrategy`
and add it to the builder.

```php
Gson::builder()
    ->setMethodNamingStrategy(new SameAsPropertyNameStrategy())
    ->build();
```
