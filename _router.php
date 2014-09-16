<?php
    namespace {
        use Xing\Mvc\IRouter;
        use Xing\System\Locator;

        require_once('src/config.php');
        /** @var IRouter $router */
        $router             = Locator::get('IRouter');
        $router->setDefaultNamespace('\Ui\Controller\\');
        $router->run();
    }