<?php
/**
 * @Author: CraspHB彬
 * @Date:   2018-08-11 11:33:51
 * @Email:   646054215@qq.com
 * @Last Modified time: 2018-09-17 16:20:18
 */
namespace Crasphb;
class Tree{
     
    //目标数组
    protected $target = [];
    // $target = [
    //        ['id' => 1 , 'name' => '1111' , 'pid' => 0],
    //        ['id' => 2 , 'name' => '2222' , 'pid' => 0],
    //        ['id' => 3 , 'name' => '3333' , 'pid' => 1],
    //        ['id' => 4 , 'name' => '4444' , 'pid' => 3],
    //        ['id' => 5 , 'name' => '5555' , 'pid' => 2]
    // ];
    /**
     * 配置数组
     * @var [type]
     */
    protected $config = [
        'id'  => 'id' ,  //id
        'pid' => 'pid',  //父id
        'name' => 'name',
        'child' => 'child'
    ];
    
    /**
     * 实例化配置参数
     * @param [type] $array  [目标数组]
     * @param [type] $config [配置]
     */
    public function __construct($target = [] , $config = []){
        
        $this->target = array_merge($this->target , $target );
        $this->config = array_merge($this->config , $config );

    }
    /**
     * 获得数组结构列表
     * @param  integer $id   [description]
     * @param  integer $level [description]
     * @return [type]         [description]
     */
    public function getArrayList($id = 0){
        
        $array = [];
        foreach($this->target as $k => $v){
            if($v[$this->config['pid']] == $id){
                
                $v[$this->config['child']] = $this->getArrayList($v[$this->config['id']]);
                $array[] = $v;
            }
        }
        return $array;
    }
    /**
     * 获得所有的子
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getChilds($id){
        
        $array = [];
        foreach($this->target as $k => $v){
            if($v[$this->config['pid']] == $id){
                  $array[] = $v[$this->config['id']];
                  array_merge($array , $this->getChilds($v[$this->config['id']]));
            }
        }
        return $array;
    }
    /**
     * 获取id的所有父，包含自己
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getParents($id){
        static $ids = [];
        foreach($this->target as $k => $v){
            if($v[$this->config['id']] == $id){
                array_unshift($ids , $id);
                if($v['pid'] == 0){
                    break;
                }
                $this->getParents($v[$this->config['pid']]);
            }
        }
       return $ids;
    }
    /**
     * 得到子的同级的数组
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getChildBros($id){

        $array = [];
        foreach($this->target as $k => $v){
            if($v[$this->config['pid']] == $id){
                  $array[] = $v[$this->config['id']];
            }
        }
        return $array;
    }
    /**
     * 得到同级数组
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getBros($id){

        $array = [];
        $pid = '';
        //获取pid，这个
        foreach($this->target as $k => $v){
            if($v[$this->config['id']] == $id){
                 $pid = $v[$this->config['pid']];
                 break  1; //退出两层的循环
            }
        }
        if($pid === ''){   //该id不存在，返回空

            return [];
        }
        foreach($this->target as $k => $v){
            if($v[$this->config['pid']] == $pid){
                  $array[] = $v[$this->config['id']];
            }
        }
        return $array;        
    }
    /**
     * 得到父级的同级数组
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getParentBro($id){

        $array = [];
        $pid = '';
        //获取pid，这个
        foreach($this->target as $k => $v){
            if($v[$this->config['id']] == $id){
                 $pid = $v[$this->config['pid']];
                 break  1; //退出两层的循环
            }
        }  
        if($pid === ''){   //该id不存在，返回空

            return [];
        }      
        //获取父级的pid
        foreach($this->target as $k => $v){
            if($v[$this->config['id']] == $pid){
                 $pid = $v[$this->config['pid']];
                 break  1; //退出两层的循环
            }
        }  
        if($pid === ''){   //该id不存在，返回空

            return [];
        }
        foreach($this->target as $k => $v){
            if($v[$this->config['pid']] == $pid){
                  $array[] = $v[$this->config['id']];
            }
        }
        return $array;      
    }
    /**
     * 得到树形结构
     * @param  [type] $data  [description]
     * @param  string $pid   [description]
     * @param  string $level [description]
     * @return [type]        [description]
     */
    public function getTree($pid = '0'){
         static $array = [];
         foreach($this->target as $k => $v){
            if($v[$this->config['pid']] == $pid){
                if($v['level'] == '2'){
                    $v['left'] = str_repeat("&nbsp;&nbsp;",$v['level'])."|--";
                }elseif($v['level'] != '1'){
                    $v['left'] = str_repeat("&nbsp;&nbsp;",$v['level'])."|--".str_repeat('-',$v['level']);
                }else{
                    $v['left'] = '';
                }
                $array[] = $v;
                $this->getTree($v[$this->config['id']],++$v['level']);
            }
         }
         return $array;
    }
}