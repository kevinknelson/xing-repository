<?php

    namespace Xing\Mvc {
        interface IRouter {
            public function setDefaultNamespace($ns);
            public function run();
        }
    }