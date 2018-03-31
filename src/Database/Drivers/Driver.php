<?php

namespace DrMVC\Database\Drivers;

use DrMVC\Config\ConfigInterface;

abstract class Driver implements DriverInterface, QueryInterface
{
    /**
     * @var \PDO
     */
    protected $_connection;

    /**
     * @var ConfigInterface
     */
    private $_config;

    /**
     * Name of collection
     * @var string
     */
    private $_collection;

    /**
     * Driver constructor.
     *
     * @param   ConfigInterface $config object with current configuration
     * @param   string $collection name of collection
     */
    public function __construct(ConfigInterface $config, string $collection)
    {
        $this->setConfig($config);
        $this->setCollection($this->getParam('prefix') . $collection);
    }

    /**
     * Initiate connection with database
     *
     * @return  DriverInterface
     */
    abstract public function connect(): DriverInterface;

    /**
     * Close database connection
     *
     * @return  DriverInterface
     */
    public function disconnect(): DriverInterface
    {
        $this->setConnection(null);
        return $this;
    }

    /**
     * Generate DSN by parameters in config
     *
     * @param $config
     * @return string
     */
    abstract protected function genDsn($config): string;

    /**
     * Generate DSN by data in config
     *
     * @return  string
     */
    public function getDsn(): string
    {
        // Get all parameters
        $config = $this->getConfig()->get();

        // Generate DSN by parameters in config
        return $this->genDsn($config);
    }

    /**
     * @param   string $collection
     * @return  DriverInterface
     */
    public function setCollection(string $collection): DriverInterface
    {
        $this->_collection = $collection;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->_collection;
    }

    /**
     * Save current config
     *
     * @param   ConfigInterface $config
     * @return  DriverInterface
     */
    public function setConfig(ConfigInterface $config): DriverInterface
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * Return config object
     *
     * @return  ConfigInterface
     */
    public function getConfig(): ConfigInterface
    {
        return $this->_config;
    }

    /**
     * Get some parameter from config by keyname
     *
     * @param   string $param
     * @return  mixed
     */
    public function getParam(string $param)
    {
        $result = $this->getConfig()->get($param);
        return $result ?? null;
    }

    /**
     * Save connection with database via driver
     *
     * @param   mixed $connection
     * @return  DriverInterface
     */
    public function setConnection($connection): DriverInterface
    {
        $this->_connection = $connection;
        return $this;
    }

    /**
     * Get current connection
     *
     * @return  \PDO
     */
    public function getConnection(): \PDO
    {
        return $this->_connection;
    }
}
