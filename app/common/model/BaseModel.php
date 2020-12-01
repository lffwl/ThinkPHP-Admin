<?php
declare (strict_types=1);

namespace app\common\model;

use think\Model;

class BaseModel extends Model
{
    //启用自动时间戳
    protected $autoWriteTimestamp = true;
    // 关闭自动写入update_time字段
    protected $updateTime = false;

    /**
     * 全部键值对
     * @param $field
     * @param string $key
     * @return array
     */
    public function allKvp($field, $key = '')
    {
        return $this->column($field, $key);
    }

    /**
     * 新增，主键修改
     * @param $data
     * @param null $id
     * @return bool
     */
    public function pkSave($data, $id = null)
    {
        if (!empty($id)) {
            return $this->where($this->getPk(), $id)->save($data);
        }
        return $this->save($data);
    }

    /**
     * 检查字段是否存在
     * @param $field
     * @param $value
     * @return int
     */
    public function checkFieldIsExist($field, $value)
    {
        return $this->where($field, $value)->count();
    }

    /**
     * 根据字段获取详情
     * @param $field
     * @param $value
     * @return array|Model
     */
    public function fieldFind($field, $value)
    {
        return $this->where($field, $value)->findOrEmpty();
    }

    /**
     * 根据主键获取详情
     * @param $value
     * @return array|Model
     */
    public function pkFind($value)
    {
        return $this->fieldFind($this->getPk(), $value);
    }

    /**
     * 根据字段获取详情中的某个字段
     * @param $field
     * @param $value
     * @param $getField
     * @return mixed
     */
    public function fieldValue($field, $value, $getField)
    {
        return $this->where($field, $value)->value($getField);
    }

    /**
     * 根据主键获取详情中的某个字段
     * @param $value
     * @param $getField
     * @return mixed
     */
    public function pkValue($value, $getField)
    {
        return $this->fieldValue($this->getPk(), $value, $getField);
    }

    /**
     * 给表字段增加前缀
     * @param $prefix
     * @param $data
     * @return mixed
     */
    public function addFieldPrefix($prefix, $data)
    {
        foreach ($data as $key => $val) {
            $data[$key] = $prefix . '.' . $val;
        }
        return $data;
    }

    /**
     * 检查字段是否存在，可携带附加条件
     * @param $field
     * @param $value
     * @param null $otherValue
     * @param null $otherField
     * @param string $op
     * @return int
     */
    public function checkFieldIsExistOther($field, $value, $otherValue = null, $otherField = null, $op = '<>')
    {
        //是否有其他条件
        if (!empty($otherValue)) {
            //其他条件的字段为空就获取主键
            if (empty($otherField)) {
                $otherField = $this->getPk();
            }
            return $this
                ->where($field, $value)
                ->where(function ($q) use ($otherField, $otherValue, $op) {
                    if (!empty($otherValue)) {
                        $q->where($otherField, $op, $otherValue);
                    }
                })
                ->count();
        }
        return $this->checkFieldIsExist($field, $value);
    }

    /**
     * 主键删除
     * @param $id
     * @return bool
     */
    public function pkDelete($id)
    {
        return $this->where($this->getPk(), $id)->delete();
    }

    /**
     * 获取id数量是否真实存在
     * @param $ids
     * @return int
     */
    public function getIdsCount($ids)
    {
        return $this->whereIn($this->getPk(), $ids)->count();
    }
}