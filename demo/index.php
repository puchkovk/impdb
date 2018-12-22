<?php
require_once __DIR__ .'/../src/autoload.php';

// Instancing and creating connection
$db = new \ImpDB\DB(new \ImpDB\Driver\MySQL('foo', '', 'impdb'));

// Selecting all fields
$result1 = $db->select(['*'])
    ->from('impdb_demo1')
    ->execute();

// Selecting `id` and `category_id` fields
$result2 = $db->select(['id', 'category_id'])
	->from('impdb_demo1')
	->execute();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Imp DB demo.</title>
</head>
<body>
<h1>ImpDB Demo</h1>
<p>See source code of this page for demo :)</p>
<h2>Demo results:</h2>
<p>Selecting all fields, rows:</p>
<pre>
    <?php
    while($row = $result1->fetchRowAssoc()) {
        var_dump($row);
    }
    ?>
</pre>
<p>Selecting `id` and `category_id` fields, rows:</p>
<pre>
    <?php
    while($row = $result2->fetchRowAssoc()) {
	    var_dump($row);
    }
    ?>
</pre>
</body>
</html>