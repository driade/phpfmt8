<?php

namespace {
    class ProgressBar
    {
        public function b(callable $fn)
        {
        }

    }
}

namespace B {
    class ProgressBar
    {
        public function b(callable $fn)
        {
        }

    }
}

namespace Symfony\Component\Console\Formatter{
    class ProgressBar
    {
        public function b(callable $fn)
        {
        }

    }
}

namespace {
    function b(callable $fn)
    {
    }
    function a(callable $fn)
    {
    }
}

namespace C {
    function b(callable $fn)
    {
    }
    function a(callable $fn)
    {
    }
}

namespace Symfony\Component\Console\Formatter{
    function b(callable $fn)
    {
    }
}