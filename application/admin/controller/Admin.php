<?php


namespace app\admin\controller;


use app\admin\logic\Auth;
use app\common\exception\Failure;
use app\common\exception\Success;
use jack\Random;
use app\common\model\Admin as AdminModel;

class Admin extends Base
{

    public function initialize() {
        parent::initialize();
        $this->model = new AdminModel();
    }


    public function add() {
        if ($this->request->isAjax()) {
            $params = $this->request->param();
            $salt = Random::alnum(4);
            $params['join_ip'] = $this->request->ip(1);
            $params['avatar'] = '';
            $params['password'] = Auth::instance()->createPassword($params['password'], $salt);
            $params['salt'] = $salt;
            $result = AdminModel::create($params, true);
            if ($result) {
                throw new Success('添加成功');
            } else {
                throw new Failure('添加失败');
            }
        }
        return $this->fetch();
    }

    public function edit($id = '') {
        $row = AdminModel::get($id);
        if (empty($row)) {
            throw new Failure('数据不存在或已被删除');
        }
        $this->assignConfig('ids', $id);
        if ($this->request->isAjax()) {
            $params = $this->request->param();
            $params['avatar'] = '';
            if (empty($params['password'])) {
                unset($params['password']);
                unset($params['repass']);
            } else {
                $salt = Random::alnum(4);
                $params['password'] = Auth::instance()->createPassword($params['password'], $salt);
                $params['salt'] = $salt;
            }
            $result = $row->save($params);
            if ($result) {
                throw new Success('更新成功');
            }
            throw new Failure('更新失败');

        }

        $this->assign('row', $row);


        return $this->fetch();
    }
}