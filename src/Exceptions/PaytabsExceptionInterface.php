<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Exceptions;

/**
 * Implemented by every exception the SDK throws, so consumers can write a single
 * catch block:
 *
 *     try {
 *         $response = $paytabs->setRequest($request)->submit();
 *     } catch (PaytabsExceptionInterface $e) {
 *         // Anything the SDK itself raised.
 *     }
 *
 * Each implementation also extends the closest-matching SPL class, so existing
 * `catch (\RuntimeException)` blocks keep working.
 */
interface PaytabsExceptionInterface extends \Throwable {}
