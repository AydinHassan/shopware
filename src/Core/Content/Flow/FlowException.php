<?php declare(strict_types=1);

namespace Shopware\Core\Content\Flow;

use Doctrine\DBAL\Exception as DBALException;
use Shopware\Core\Content\Flow\Dispatching\TransactionFailedException;
use Shopware\Core\Framework\HttpException;
use Shopware\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

#[Package('services-settings')]
class FlowException extends HttpException
{
    final public const METHOD_NOT_COMPATIBLE = 'METHOD_NOT_COMPATIBLE';
    final public const FLOW_ACTION_TRANSACTION_COMMIT_FAILED = 'FLOW_ACTION_TRANSACTION_COMMIT_FAILED';

    public static function methodNotCompatible(string $method, string $class): FlowException
    {
        return new self(
            Response::HTTP_BAD_REQUEST,
            self::METHOD_NOT_COMPATIBLE,
            'Method {{ method }} is not compatible for {{ class }} class',
            ['method' => $method, 'class' => $class]
        );
    }

    public static function transactionCommitFailed(TransactionFailedException|DBALException $previous): self
    {
        return new self(
            Response::HTTP_BAD_REQUEST,
            self::FLOW_ACTION_TRANSACTION_COMMIT_FAILED,
            'Flow action transaction could not be committed. An exception occurred: ' . $previous->getMessage(),
            [],
            $previous,
        );
    }
}
