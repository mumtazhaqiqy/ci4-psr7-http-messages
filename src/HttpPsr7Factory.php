<?php 

namespace MumtazHaqiqy\Codeigniter4Psr7;

use CodeIgniter\HTTP\DownloadResponse;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Response;
use MumtazHaqiqy\Codeigniter4Psr7\Interfaces\HttpPsr7FactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;

class HttpPsr7Factory implements HttpPsr7FactoryInterface
{

    protected $serverRequestFactory;

    protected $streamFactory;

    protected $uploadedFileFactory;

    protected $responseFactory;

    public function __construct(
        ServerRequestFactoryInterface $serverRequestFactory,
        StreamFactoryInterface $streamFactory,
        UploadedFileFactoryInterface $uploadedFileFactory,
        ResponseFactoryInterface $responseFactory
    )
    {
        $this->serverRequestFactory = $serverRequestFactory;
        $this->streamFactory = $streamFactory;
        $this->uploadedFileFactory = $uploadedFileFactory;
        $this->responseFactory = $responseFactory;
    }

    public function createRequest(IncomingRequest $codeigniterRequest)
    {
        $uri = $codeigniterRequest->getServer('app.baseURL').$codeigniterRequest->uri->getPath().'?'.$codeigniterRequest->uri->getQuery();

        $request = $this->serverRequestFactory->createServerRequest(
            $codeigniterRequest->getMethod(),
            $uri,
            $codeigniterRequest->getServer()
        );

        foreach ($codeigniterRequest->getHeaders() as $name => $value) {
            $request = $request->withHeader($name, $value->getValue());
        }

        $body = $codeigniterRequest->getBody() === null
            ? $this->streamFactory->createStreamFromResource('php://memory', 'wb+')
            : $this->streamFactory->createStreamFromResource($codeigniterRequest->getBody());
        
        $request = $request
            ->withBody($body)
            ->withCookieParams($codeigniterRequest->getCookie())
            ->withQueryParams($codeigniterRequest->uri->getSegments())
            ->withParsedBody($codeigniterRequest->getVar());

        return $request;
    }

    public function createResponse(Response $codeigniterResponse)
    {
        $response = $this->responseFactory->createResponse(
            $codeigniterResponse->getStatusCode(),
            $codeigniterResponse->getReason()
        );

        if ($codeigniterResponse instanceof DownloadResponse) {
            $stream = $this->streamFactory->createStreamFromFile('php://temp', 'wb+');
            $stream->write($codeigniterResponse->sendBody());

            $response->withBody($stream);
        }

        foreach ($codeigniterResponse->getHeaders() as $name => $value) {
            $response = $response->withHeader($name, $value->getValue());
        }

        $response = $response->withProtocolVersion($codeigniterResponse->getProtocolVersion());

        return $response;
    }

}