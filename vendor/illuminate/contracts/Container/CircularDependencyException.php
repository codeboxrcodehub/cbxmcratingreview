<?php

namespace CBXMCRatingReviewScoped\Illuminate\Contracts\Container;

use Exception;
use CBXMCRatingReviewScoped\Psr\Container\ContainerExceptionInterface;
class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
