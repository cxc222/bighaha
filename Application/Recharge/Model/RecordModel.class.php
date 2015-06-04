<?php
namespace Recharge\Model;
use Think\Model;


class RecordModel extends Model
{
    protected $tableName='recharge_record_alipay';


    public function getRecord($id,$method){
        $record = S('recharge_record_'.$method.'_'.$id);
        if(is_bool($record)){
            $record = D('recharge_record_'.$method)->where(array('id'=>$id))->find();
            S('recharge_record_'.$method.'_'.$id,$record,60*60);
        }
        return $record;
    }




}