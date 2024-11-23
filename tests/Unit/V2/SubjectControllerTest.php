<?php

namespace Tests\Unit\V2;

use PHPUnit\Framework\TestCase;

use Tests\Unit\V1\SubjectControllerTest as V1SubjectControllerTest;


class SubjectControllerTest extends V1SubjectControllerTest
{
    protected $baseEndpoint = '/api/v2/subject/';

    protected function getEndpoint(string $path = ''): string
    {
        return $this->baseEndpoint . $path;
    }


        
}

