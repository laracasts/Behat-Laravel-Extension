<?php

namespace Laracasts\Behat\Driver;

use Illuminate\Container\Container;
use Laravel\Lumen\Concerns\RoutesRequests;
use Laravel\Lumen\Testing\Concerns\MakesHttpRequests;
use Symfony\Component\BrowserKit\Client as BaseClient;
use Symfony\Component\BrowserKit\Request as DomRequest;
use Symfony\Component\BrowserKit\Response as DomResponse;
use Symfony\Component\BrowserKit\Cookie as DomCookie;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class LumenClient extends BaseClient
{
    use MakesHttpRequests;

    /**
     * The Lumen application instance
     *
     * @var Container
     */
    protected $app;

    public function __construct(Container $app, array $server = array(), History $history = null, CookieJar $cookieJar = null)
    {
        $this->app = $app;

        parent::__construct($server, $history, $cookieJar);
    }

    /**
     * {@inheritdoc}
     *
     * @return Request|null A Request instance
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    /**
     * {@inheritdoc}
     *
     * @return Response|null A Response instance
     */
    public function getResponse()
    {
        return parent::getResponse();
    }

    /**
     * Makes a request.
     *
     * @param Request $request A Request instance
     * @return Response A Response instance
     * @throws \Exception
     */
    protected function doRequest($request)
    {
        $uses = array_flip(class_uses_recursive(get_class($this->app)));

        if (isset($uses[RoutesRequests::class])) {
            return $this->response = $this->app->prepareResponse(
                $this->app->handle($request)
            );
        } else {
            throw new \Exception(sprintf(
                'The application is not using the %s trait.',
                RoutesRequests::class
            ));
        }
    }

    /**
     * Converts the BrowserKit request to a HttpKernel request.
     *
     * @param DomRequest $request A DomRequest instance
     * @return Request A Request instance
     */
    protected function filterRequest(DomRequest $request)
    {
        $httpRequest = Request::create($request->getUri(), $request->getMethod(), $request->getParameters(), $request->getCookies(), $request->getFiles(), $request->getServer(), $request->getContent());

        foreach ($this->filterFiles($httpRequest->files->all()) as $key => $value) {
            $httpRequest->files->set($key, $value);
        }

        return $httpRequest;
    }

    /**
     * Converts the HttpKernel response to a BrowserKit response.
     *
     * @param Response $response A Response instance
     * @return DomResponse A DomResponse instance
     */
    protected function filterResponse($response)
    {
        $headers = $response->headers->all();
        if ($response->headers->getCookies()) {
            $cookies = array();
            foreach ($response->headers->getCookies() as $cookie) {
                /** @var Cookie $cookie */
                $cookies[] = new DomCookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
            }
            $headers['Set-Cookie'] = $cookies;
        }

        // this is needed to support StreamedResponse
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        return new DomResponse($content, $response->getStatusCode(), $headers);
    }

    /**
     * Filters an array of files.
     *
     * This method created test instances of UploadedFile so that the move()
     * method can be called on those instances.
     *
     * If the size of a file is greater than the allowed size (from php.ini) then
     * an invalid UploadedFile is returned with an error set to UPLOAD_ERR_INI_SIZE.
     *
     * @see UploadedFile
     *
     * @param array $files An array of files
     * @return array An array with all uploaded files marked as already moved
     */
    protected function filterFiles(array $files)
    {
        $filtered = array();
        foreach ($files as $key => $value) {
            if (is_array($value)) {
                $filtered[$key] = $this->filterFiles($value);
            } elseif ($value instanceof UploadedFile) {
                if ($value->isValid() && $value->getSize() > UploadedFile::getMaxFilesize()) {
                    $filtered[$key] = new UploadedFile(
                        '',
                        $value->getClientOriginalName(),
                        $value->getClientMimeType(),
                        0,
                        UPLOAD_ERR_INI_SIZE,
                        true
                    );
                } else {
                    $filtered[$key] = new UploadedFile(
                        $value->getPathname(),
                        $value->getClientOriginalName(),
                        $value->getClientMimeType(),
                        $value->getClientSize(),
                        $value->getError(),
                        true
                    );
                }
            }
        }

        return $filtered;
    }
}
