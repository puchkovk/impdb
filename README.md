# ImpDB

Be careful! This code is in early alpha, so it can contain bugs and vulnerabilities.

Current version: 0.0.4

Release date: unknown. I`m working on it in holidays, sometimes.

It`s small and simple database abstraction library,
which allows you to build DB queries in symple syntax.

For example:

```php
<?
$result = $db->select(['*'])->from('table')->where('a','=','1')->execute();
while($row = $result->fetchRowAssoc()) {
   var_dump($row);
}
```

__Output:__
```html
array(3) { ["id"]=> string(1) "1" ["title"]=> string(10) "Foo" }
array(3) { ["id"]=> string(2) "1" ["title"]=> string(10) "Bar" }
```

### Current progress:
- Simple SELECT queries work good;
- SELECTS with GROUP BY - working, but no tests;
- Simple UPDATE`s working;
- Simple INSERT`s working;
- INSERT`s with ON DUPLICATE KEY UPDATE - not working at all;
- DELETE`s working;
- Conditions-builder working;
- MySQL driver - working, but no tests;
- Mock DB driver - working, but need improvements;
- Unit testing - need more good tests;

### Long-term plans:

- to finish basic functionality;
- improve tests coverage;
- setup automatic code coverage reports;
- write more good docs;
- make a composer package and publish it.

Notice: Imp is small and unambitious worker, so this library will stay small and simple.

Published under Apache License 2.0. 

Author: Konstantin Puchkov.

Once you want to help - fell free to make a PR. 

### Some codestyle rules:

- use CamelCase;
- use true or false, not TRUE or FALSE;
- simple code is better then cool code;
- use tabs for intendation. Yes, spaces are better for intendation, but we use tabs;
- use spaces for vertical alignment;
- hacks and dummy tricks not allowed at all.


