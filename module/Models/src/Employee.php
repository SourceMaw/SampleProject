<?php

namespace Models;

use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;

class Employee extends Base{

    public $tableName = 'employees';
    public $pkId = 'emp_id';

    public function addEmployee($name, $position, $office, $age, $salary, $email){

        $existingEmployee = $this->getEmployeeByEmail($email);
        $wasInserted = false;

        $fields = [

            'emp_name' => $name,
            'emp_position' => $position,
            'emp_office' => $office,
            'emp_age' => $age,
            'emp_salary' => $salary,
            'emp_email' => $email

        ];

        if(empty($existingEmployee)){

            $this->insert($fields);
            $wasInserted = true;

        }

        return [

            'wasInserted' => $wasInserted

        ];

    }

    public function getEmployeeByEmail($email){

        $select = $this->getSelectObject();
        $select->where(
            [
                'emp_email' => $email
            ]
        );

        $results = $this->runQueryBuilt($select);

        if(!empty($results[0])){

            return $results[0];
        }

        return $results;
    }

    public function getEmployeeById($id){

        $select = $this->getSelectObject();
        $select->where(
            [
                'emp_id' => $id
            ]
        );

        $results = $this->runQueryBuilt($select);

        if(!empty($results[0])){

            return $results[0];
        }

        return $results;
    }

    public function getAllEmployees(){

        $select = $this->getSelectObject();


        return $this->runQueryBuilt($select);

    }

}