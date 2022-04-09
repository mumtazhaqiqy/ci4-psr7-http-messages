<?php 

namespace MumtazHaqiqy\Codeigniter4Psr7\Interfaces;

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Response;

interface HttpPsr7FactoryInterface
{
    public function createRequest(IncomingRequest $codeigniterRequest);

    public function createResponse(Response $codeigniterResponse);

}

