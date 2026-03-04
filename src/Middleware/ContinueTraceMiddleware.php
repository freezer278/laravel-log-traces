<?php

namespace VMorozov\LaravelLogTraces\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use VMorozov\LaravelLogTraces\LogTracesServiceProvider;
use VMorozov\LaravelLogTraces\Tracing\TraceStorage;

class ContinueTraceMiddleware
{
    private TraceStorage $traceStorage;

    public function __construct(TraceStorage $traceIdStorage)
    {
        $this->traceStorage = $traceIdStorage;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $traceId = $this->getTraceIdFromGetParams($request) ?? $this->getTraceIdFromTraceparentHeader($request);

        if (!$traceId) {
            $this->traceStorage->startNewTrace();
        } else {
            $this->traceStorage->setTraceId($traceId);
        }

        if ($this->requestsLogEnabled()) {
            Log::log(
                $this->requestsLogLevel(),
                'Request started',
                [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                ],
            );
        }

        $response = $next($request);

        if ($this->requestsLogEnabled()) {
            Log::log(
                $this->requestsLogLevel(),
                'Request ended',
                [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                ],
            );
        }

        return $response;
    }

    /**
     * @param Request $request
     */
    private function getTraceIdFromGetParams($request): ?string
    {
        return $request->get('trace_id');
    }

    /**
     * @param Request $request
     */
    private function getTraceIdFromTraceparentHeader($request): ?string
    {
        if (!$request->hasHeader('traceparent') && !$request->hasHeader('X-REQUEST-ID')) {
            return null;
        }

        $traceId = $request->header('traceparent') ?? $request->header('X-REQUEST-ID') ?? '';

        return $this->parseTraceparentValue($traceId);
    }

    private function parseTraceparentValue(string $headerValue): ?string
    {
        if (substr_count($headerValue, '-') !== 3) {
            return null;
        }

        [$version, $traceId, $spanId, $flags] = explode('-', $headerValue);
        if ($version !== '00') {
            return null;
        }

        return $traceId;
    }

    private function requestsLogEnabled(): bool
    {
        return config(LogTracesServiceProvider::CONFIG_KEY . '.requests.enabled', true);
    }

    private function requestsLogLevel(): string
    {
        return config(LogTracesServiceProvider::CONFIG_KEY . '.requests.log_level', 'debug');
    }
}
