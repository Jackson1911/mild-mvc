<?php

namespace packs\ActiveRecord;

use packs\ActiveRecord\IActiveRecord;
use system\App;

class CActiveRecord implements IActiveRecord
{
    protected static $table;

    protected $data = [];

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : false;
    }

    public function findAll(array $condition = []):array
    {
        $stm = App::$db
            ->setClass(get_called_class())
            ->select('*')
            ->from(static::$table);

        if (!empty($condition)) {
            $stm->where($condition);
        }

        return $stm->fetchAll();
    }

    public function findOne(array $condition = [])
    {
        $stm = App::$db
            ->setClass(get_called_class())
            ->select('*')
            ->from(static::$table);

        if (!empty($condition)) {
            $stm->where($condition);
        }

        return $stm->fetchRow();
    }

    protected function update()
    {
        return App::$db
            ->update($this->data)
            ->table(static::$table)
            ->where([
                'id' => $this->id,
            ])
            ->execute();
    }

    protected function insert()
    {
        return App::$db
            ->insert($this->data)
            ->table(static::$table)
            ->execute();
    }
    
    protected function delete()
    {
        return App::$db
            ->delete()
            ->from(static::$table)
            ->where([
                'id' => $this->data['id']
            ])
            ->execute();
    }

    public function save():bool
    {
        if ($this->id) {
            return $this->update();
        }

        return $this->insert();
    }
    
    public function remove():bool
    {
        return $this->delete();
    }
}
