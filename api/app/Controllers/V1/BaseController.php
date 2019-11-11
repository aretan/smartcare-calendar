<?php
namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class BaseController extends ResourceController
{
    use ResponseTrait;
    protected $helpers = [];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    /**
     * テーブル全部返す関数 (要ページング機能)
     * GET /api/v1/{controller}
     */
    public function index()
    {
        $model = $this->_getModel();
        $parents = $this->_getParentId();
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
        $model = $this->_getModel();
        $data = array_merge($this->_getParentId(), $this->request->getJSON(true));
        $this->_getModel()->insert($this->_getEntity($data));
        return $this->respondCreated();
    }

    /**
     * レコードを取得する関数
     * GET /api/v1/{controller}/show/{id}
     * GET /api/v1/{controller}/{id}
     */
    public function show($id = null)
    {
        return $this->respond($this->_getModel()->find($id), 200);
    }

    /**
     * レコードを更新する関数
     * POST /api/v1/{controller}/update/{id}
     */
    public function update($id = null)
    {
        $data = array_merge($this->_getParentId(), $this->request->getJSON(true));
        $data['id'] = $id;
        $this->_getModel()->update($this->_getEntity($data));
        return $this->respond();
    }

    /**
     * レコードを削除する関数
     * POST /api/v1/{controller}/delete/{id}
     */
    public function delete($id = null)
    {
        $this->_getModel()->delete($id);
        return $this->respondDeleted();
    }

    /**
     * 内部関数：クラス名そのままのモデルを返す
     */
    protected function _getModel()
    {
        $model = '\\App\\Models' . strrchr(get_class($this), '\\') . "Model";
        return new $model();
    }

    /**
     * 内部関数：クラス名そのままのエンティティを返す
     */
    protected function _getEntity($data = null)
    {
        $entity = '\\App\\Entities' . strrchr(get_class($this), '\\');
        return new $entity($data);
    }

    /**
     * 内部関数：URLから親のリソースIDを取得する
     */
    protected function _getParentId()
    {
        $segments = $this->request->uri->getSegments();
        $parents = [];

        if (isset($segments[1]) && isset($segments[2])) {
            $parents["{$segments[1]}_id"] = $segments[2];
        }

        if (isset($segments[3]) && isset($segments[4])) {
            $parents["{$segments[3]}_id"] = $segments[4];
        }

        return $parents;
    }
}
