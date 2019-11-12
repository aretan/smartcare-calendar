<?php namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ApiController extends ResourceController
{
    use ResponseTrait;
    protected $helpers = [];
    protected $smartcare;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->smartcare = new \App\Libraries\Smartcare();
    }

    /**
     * テーブル全部返す関数 (要ページング機能)
     * GET /api/v1/{controller}
     */
    public function index()
    {
        $model = $this->_getModel($this);
        $parents = $this->_getParentId($this);
        if (count($parents)) {
            $model->where($parents);
        }
        return $this->respond($model->findAll(), 200);
    }

    /**
     * テーブルにレコードを挿入する関数
     * POST /api/v1/{controller}/create
     * POST /api/v1/{controller}
     */
    public function create()
    {
        $request = $this->request->getJSON(true);
        if (!$request) {
            $request = $this->request->getPost();
        }
        $data = array_merge($this->_getParentId($this), $request);
        $this->_getModel($this)->insert($data);
        return $this->respondCreated();
    }

    /**
     * レコードを取得する関数
     * GET /api/v1/{controller}/show/{id}
     * GET /api/v1/{controller}/{id}
     */
    public function show($id = null)
    {
        $model = $this->_getModel($this);
        $parents = $this->_getParentId($this);
        if (count($parents)) {
            $model->where($parents);
        }
        return $this->respond($model->find($parents['id']), 200);
    }

    /**
     * レコードを更新する関数
     * POST /api/v1/{controller}/update/{id}
     */
    public function update($id = null)
    {
        $model = $this->_getModel($this);
        $parents = $this->_getParentId($this);
        $data = array_merge($parents, $this->request->getJSON(true));
        $this->_getModel($this)->update($this->_getEntity($this, $data));
        return $this->respond();
    }

    /**
     * レコードを削除する関数
     * POST /api/v1/{controller}/delete/{id}
     */
    public function delete($id = null)
    {
        $model = $this->_getModel($this);
        $parents = $this->_getParentId($this);
        if (count($parents)) {
            $model->where($parents);
        }
        $model->delete($parents['id']);
        return $this->respondDeleted();
    }

    /**
     * 内部関数：クラス名そのままのモデルを返す
     */
    protected function _getModel($class)
    {
        $model = 'App\\Models' . strrchr(get_class($class), '\\') . "Model";
        return new $model();
    }

    /**
     * 内部関数：クラス名そのままのエンティティを返す
     */
    protected function _getEntity($class, $data = null)
    {
        $entity = 'App\\Entities' . strrchr(get_class($class), '\\');
        return new $entity($data);
    }

    /**
     * 内部関数：URLから親のリソースIDを取得する
     */
    protected function _getParentId($class)
    {
        $segments = $class->request->uri->getSegments();
        $parents = [];

        for ($i=1; isset($segments[$i]) && isset($segments[$i+1]); $i=$i+2) {
            if (strtolower(substr(strrchr(get_class($class), '\\'), 1)) == $segments[$i]) {
                $parents['id'] = $segments[$i+1];
            } else {
                $parents["{$segments[$i]}_id"] = $segments[$i+1];
            }
        }

        return $parents;
    }
}
