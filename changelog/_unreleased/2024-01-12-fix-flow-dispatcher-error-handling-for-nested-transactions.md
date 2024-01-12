---
title: Fix flow dispatcher error handling for nested transactions
issue: NEXT-33086
---
# Core
* Added a new interface `\Shopware\Core\Content\Flow\Dispatching\TransactionalAction` which flow actions can implement to flag that they should be executed within a database transaction.
* Added `\Shopware\Core\Content\Flow\Dispatching\TransactionFailedException` so that transactional actions can force a rollback. 
* Changed `\Shopware\Core\Content\Flow\Dispatching\FlowDispatcher` to rethrow exceptions which occur during a nested transaction if save points are not enabled.
