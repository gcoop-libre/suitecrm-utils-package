<?php

namespace Gcoop\Log;

use GuzzleLogMiddleware\LogMiddleware;
use GuzzleLogMiddleware\Handler\MultiRecordArrayHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

final class LogMiddlewareFactory
{

    public static function get(string $wsFileName, string $current_user, int $truncateSize = 3500): LogMiddleware
    {
        $logger = new Logger('GCA-webservices');
        $stream = new StreamHandler(
            $wsFileName,
            Logger::DEBUG
        );
        $format = "[%datetime%] [{$current_user}] %message% %context%\n";
        $stream->setFormatter(new LineFormatter($format));
        $logger->pushHandler($stream);
        $handler = new MultiRecordArrayHandler(null, $truncateSize);
        $middleware = new LogMiddleware($logger, $handler);

        return $middleware;
    }
}
