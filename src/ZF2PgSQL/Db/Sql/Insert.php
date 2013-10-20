<?php
/**
 * Insert Returning for PostgreSQL
 *
 * @link      https://github.com/devpreview/ZF2PgSQL
 * @copyright Copyright (c) 2013 Alexey Savchuk. (http://www.devpreview.ru)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZF2PgSQL\Db\Sql;

use Zend\Db\Sql\Insert as ZendInsert;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\Adapter\Platform\Sql92;
use Zend\Db\Adapter\StatementContainerInterface;

class Insert extends ZendInsert
{
    /**#@+
     * Constants
     *
     * @const
     */
    const SPECIFICATION_INSERT_EXTENDS = 'insert_extends';
    
    /**
     * @var array
     */
    protected $returning = array();

    /**
     * Constructor
     *
     * @param  null|string|TableIdentifier $table
     */
    public function __construct($table = null)
    {
        $this->specifications[self::SPECIFICATION_INSERT_EXTENDS] = '%1$s RETURNING %2$s';
        parent::__construct($table);
    }
    
    /**
     * Specify returning columns
     *
     * @param  array $returning
     * @return Insert
     */
    public function returning(array $returning)
    {
        $this->returning = $returning;
        return $this;
    }
    
    public function getRawState($key = null)
    {
        $rawState = parent::getRawState();
        $rawState['returning'] = $this->returning;
        return (isset($key) && array_key_exists($key, $rawState)) ? $rawState[$key] : $rawState;
    }
    
    /**
     * Prepare statement
     *
     * @param  AdapterInterface $adapter
     * @param  StatementContainerInterface $statementContainer
     * @return void
     */
    public function prepareStatement(AdapterInterface $adapter, StatementContainerInterface $statementContainer)
    {
        parent::prepareStatement($adapter, $statementContainer);
        $platform = $adapter->getPlatform();
        
        $returning = array();
        foreach ($this->returning as $columnIndexOrAs => $column) {
            if (is_string($columnIndexOrAs)) {
                $returning[] = $platform->quoteIdentifier($column)
                . ' as ' . $platform->quoteIdentifier($columnIndexOrAs);
            } else {
                $returning[] = $platform->quoteIdentifier($column);
            }
        }
        if(!$this->returning) {
            $returning[] = 'NULL';
        }
        
        $sql = sprintf(
            $this->specifications[self::SPECIFICATION_INSERT_EXTENDS],
            $statementContainer->getSql(),
            implode(', ', $returning)
        );
        
        $statementContainer->setSql($sql);
    }
    
    /**
     * Get SQL string for this statement
     *
     * @param  null|PlatformInterface $adapterPlatform Defaults to Sql92 if none provided
     * @return string
     */
    public function getSqlString(PlatformInterface $adapterPlatform = null)
    {
        $sql = parent::getSqlString($adapterPlatform);
        $adapterPlatform = ($adapterPlatform) ?: new Sql92;
        $returning = array();
        
        foreach ($this->returning as $columnIndexOrAs => $column) {
            if (is_string($columnIndexOrAs)) {
                $returning[] = $adapterPlatform->quoteIdentifier($column)
                . ' as ' . $adapterPlatform->quoteIdentifier($columnIndexOrAs);
            } else {
                $returning[] = $adapterPlatform->quoteIdentifier($column);
            }
        }
        
        if(!$this->returning) {
            $returning[] = 'NULL';
        }
        
        $sql = sprintf(
            $this->specifications[self::SPECIFICATION_INSERT_EXTENDS],
            $sql,
            implode(', ', $returning)
        );
        
        return $sql;
    }
}