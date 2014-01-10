<?php

use Example\Search\UserSearch;
use Xing\System\Locator;
use Xing\Repository\Sql\SqlRepository;

require_once('src/config.php');

/** @var SqlRepository $repo */
$repo       = Locator::get('IRepository');
$userSearch = UserSearch::instance()->Id->isIn(array(1,2,3))
    ->andThe()->Username->Email->is('johndoe@example.com')
    ->allPreviousOr()->Email->is('ninja@example.com');

$list       = $repo->search( $userSearch );

echo \Xing\System\Serialization\JsonSerializer::encode($list);