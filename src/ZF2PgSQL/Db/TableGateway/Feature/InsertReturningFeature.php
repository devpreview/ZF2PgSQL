<?php
/**
 * Insert Returning feature for PostgreSQL
 *
 * @link      https://github.com/devpreview/ZF2PgSQL
 * @copyright Copyright (c) 2013 Alexey Savchuk. (http://www.devpreview.ru)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZF2PgSQL\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\AbstractFeature;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\Driver\StatementInterface;

class InsertReturningFeature extends AbstractFeature
{
    /**
     *
     * @var array
     */
    protected $returning = Array();

    public function postInsert(StatementInterface $statement, ResultInterface $result)
    {
        $this->returning = $result->current();
    }

    public function getReturning()
    {
        return $this->returning;
    }
}