<?php

require_once('src/config.php');

/** @var SqlRepository $repo */
use Xing\Repository\Injector;
use Xing\Repository\Sql\SqlRepository;

$repo       = Injector::get('IRepository');
$userSearch = new \Example\Search\UserSearch();
$userSearch->Id->isIn(array(1,2,3))
    ->andThe()->Email->is('johndoe@example.com')
    ->allPreviousOr()->Email->is('ninja@example.com');


$list       = $repo->search( $userSearch );

echo \Xing\System\Serialization\JsonSerializer::encode($list);