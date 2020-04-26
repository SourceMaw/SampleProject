<?php

namespace Models;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\ParameterContainer;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;

class Base {

    const RESULTS_TO_ARRAY = 1;

    public $adapter;
    public $sql;

    public $tableName;
    public $params;

    public function getConfig(){

        if(!file_exists(__DIR__ . '/../../../config/autoload/local.php')){

            return include __DIR__ . '/../../../config/autoload/global.php';
        }

        return include __DIR__ . '/../../../config/autoload/local.php';
    }

    public function __construct($params = []){

        $config = $this->getConfig();

        if(!empty($config['db'])){

            if(empty($this->adapter)){

                $this->adapter = new Adapter(

                    $config['db']
                );
            }
        }

        $this->sql = new Sql($this->adapter);
        $this->setParams($params);
    }

    public function setParams($params){

        $this->params = $params;
    }

    public function setWhere(&$query, $wheres){

        if(is_numeric($wheres)){

            $wheres = [ $this->pkId => $wheres ];
        }

        if(!empty($wheres)){

            foreach ($wheres as $k => $where){

                if(is_array($where)){

                    $query->where($where);
                }
                else {

                    $query->where([$k => $where]);
                }
            }
        }

    }

    public function runQueryBuilt($sql){

        $queryString = $this->sql->buildSqlString($sql);

        if(get_class($sql) == Select::class){

            $results = $this->adapter->query($queryString, $this->adapter::QUERY_MODE_EXECUTE);
            return $this->resultsToArray($results);
        }

        if(get_class($sql) == Delete::class){

            $results = $this->adapter->query($queryString, $this->adapter::QUERY_MODE_EXECUTE);
            return $results;
        }

    }

    public function query($sql, $vars = []){

        $results = $this->adapter->query($sql, new ParameterContainer($vars));
        return $results->toArray();

    }

    public function getSelectObject($table = ''){

        if(empty($table)){

            $table = $this->tableName;
        }

        $queryObject = $this->sql->select($table);
        return $queryObject;

    }

    public function getDeleteObject(){

        $queryObject = $this->sql->delete($this->tableName);
        return $queryObject;

    }


    public function select($wheres = [], $returnType = self::RESULTS_TO_ARRAY){

        $returnSingleRow = false;

        if(is_numeric($wheres)){

            $wheres = [$this->pkId => $wheres];
            $returnSingleRow = true;
        }

        $select = $this->sql->select($this->tableName);
        $this->setWhere($select, $wheres);
        $this->setLimit($select);


        $selectString = $this->sql->buildSqlString($select);
        $results = $this->adapter->query($selectString, $this->adapter::QUERY_MODE_EXECUTE);

        if($returnType == self::RESULTS_TO_ARRAY){



            $results =  $this->resultsToArray($results);


        }

        if($returnSingleRow && !empty($results[0])){

            $results = $results[0];
        }


        return $results;


    }

    public function resultsToArray(ResultSet $results){


        return $results->toArray();
    }

    public function insert($data){

        $insert = $this->sql->insert($this->tableName);

        $insert->values($data);
        $sqlString = $this->sql->buildSqlString($insert);

        $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);

        $id = $this->adapter->getDriver()->getLastGeneratedValue();

        return $id;



    }

    public function update($data, $where){

        if(empty($where)){

            return false;
        }

        $update = $this->sql->update($this->tableName);
        $update->set($data);

        if(is_numeric($where)){

            $where = [$this->pkId => $where];
        }
        $this->setWhere($update, $where);

        $sqlString = $this->sql->buildSqlString($update);

        $results = $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
        return $results;


    }

    public function delete($id){

        $delete = $this->getDeleteObject();
        $delete->where([$this->pkId => $id]);
        $this->runQueryBuilt($delete);
    }

    public function setLimit(&$select){

        if(!empty($this->params['rows'])){

            $select->limit($this->params['rows']);
            $select->offset(0);
        }
    }

    public function setOrder(&$select){

        if(!empty($this->params['order'])){

            $select->order($this->params['order']);
        }
        else {

            $select->order($this->pkId);
        }

    }

    public function __destruct()
    {

        if(!empty($this->adapter)){

            $this->adapter->getDriver()->getConnection()->disconnect();
        }

    }

}