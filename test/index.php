<?php
require dirname(__DIR__).'/vendor/autoload.php';

use carry0987\Cony\Cony;

echo Cony::toNumeric('test', 3); // 4540899
echo '<br />';
echo Cony::toAlphanumeric(4540899, 3); // test
echo '<br />';
echo '<br />';
echo Cony::toAlphanumeric(11282993, 3, 'heuh2ui12'); // test
echo '<br />';
echo Cony::toNumeric('test', 3, 'heuh2ui12'); // 11282993
echo '<br />';
echo '<br />';
echo Cony::toAlphanumeric(21663528, 0, null, Cony::TRANSFORM_UPPERCASE); // B2TFK
echo '<br />';
echo Cony::toAlphanumeric(21663528, 0, null, Cony::TRANSFORM_LOWERCASE); // b2tfk
