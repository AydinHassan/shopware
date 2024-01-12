<?php declare(strict_types=1);

namespace Shopware\Tests\Unit\Core\Content\Flow;

use Doctrine\DBAL\Driver\PDO\Exception as DbalPdoException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Flow\FlowException;
use Shopware\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
#[Package('core')]
#[CoversClass(FlowException::class)]
class FlowExceptionTest extends TestCase
{
    public function testMethodNotCompatible(): void
    {
        $e = FlowException::methodNotCompatible('myMethod', 'myClass');

        static::assertEquals(Response::HTTP_BAD_REQUEST, $e->getStatusCode());
        static::assertEquals(FlowException::METHOD_NOT_COMPATIBLE, $e->getErrorCode());
        static::assertEquals('Method myMethod is not compatible for myClass class', $e->getMessage());
    }

    public function testTransactionCommitFailed(): void
    {
        $previous = new TableNotFoundException(
            new DbalPdoException('Table not found', null, 1146),
            null
        );

        $e = FlowException::transactionCommitFailed($previous);

        static::assertEquals(Response::HTTP_BAD_REQUEST, $e->getStatusCode());
        static::assertEquals(FlowException::FLOW_ACTION_TRANSACTION_COMMIT_FAILED, $e->getErrorCode());
        static::assertEquals('Flow action transaction could not be committed. An exception occurred: An exception occurred in the driver: Table not found', $e->getMessage());
        static::assertSame($previous, $e->getPrevious());
    }
}
