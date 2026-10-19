<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\SimpleCaptcha;

class CaptchaFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (strtolower($request->getMethod()) !== 'post') {
            return;
        }

        if (! (new SimpleCaptcha())->check($request->getPost('captcha'))) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['captcha' => 'Wrong answer, please try again.']);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if (strtolower($request->getMethod()) !== 'get') {
            return;
        }

        $body = $response->getBody();

        if (! is_string($body) || ! str_contains($body, '</form>')) {
            return;
        }

        $field = view('partials/captcha_field', [
            'question' => (new SimpleCaptcha())->generate(),
        ]);

        // Find the submit button and insert CAPTCHA before it.
        $pattern = '/(<button\b[^>]*\btype=["\']submit["\'][^>]*>.*?<\/button>)/is';

        $updatedBody = preg_replace(
            $pattern,
            $field . '$1',
            $body,
            1,
            $count
        );

        if ($count > 0 && is_string($updatedBody)) {
            $response->setBody($updatedBody);
        }
    }
}
