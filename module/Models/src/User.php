<?php

namespace Models;

use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;

class User extends Base{

    public $tableName = 'users';
    public $pkId = 'user_id';

    public function getUserById($id){

        $select = $this->getSelectObject();
        $select->where(
            [
                'user_id' => $id
            ]
        );

        $results = $this->runQueryBuilt($select);

        if(!empty($results[0])){

            return $results[0];
        }

        return $results;
    }

    public function getByEmailAndPassword($email, $password){

        $select = $this->getSelectObject();
        $select->where(
            [
                'user_email' => $email,
                'user_password' => $password
            ]
        );

        $results = $this->runQueryBuilt($select);

        if(!empty($results[0])){

            return $results[0];
        }

        return $results;

    }
}