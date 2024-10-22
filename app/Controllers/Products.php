<?php
namespace App\Controllers;

// use App\Models\ProductModel;

use App\Models\ProductModel;
use CodeIgniter\RESTful\ResourceController;

class Products extends ResourceController
{
    // protected $model;
    protected $modelName = ProductModel::class;
    // protected $format = 'json';

    // public function __construct(){
    //     $this->model = new ProductModel();
    // }
    public function index()
    {
        $products = $this->model->findAll();
        if (empty($products)) {
            return $this->failNotFound('No products found');
        }
        return $this->respond($products);
    }

    public function show($id = null)
    {
        $product = $this->model->find($id);
        if ($product) {
            return $this->respond($product);
        } else {
            return $this->failNotFound('Product not found');
        }
    }

    public function create()
    {
        $data = $this->request->getJSON(true); 
      
        if (!$this->validate([
            'name'        => 'required',
            'price'       => 'required|numeric'
        ])) {
            return $this->fail($this->validator->getErrors());
        }
        $this->model->insert($data);
        return $this->respondCreated($data);
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON(); // Get PUT data
        if (!$this->validate([
            'name'  => 'required',
            'price' => 'required|numeric'
        ])) {
            return $this->fail($this->validator->getErrors());
        }
        $product = $this->model->find($id);
        if ($product) {
            $this->model->update($id, $data);
            return $this->respond($data);
        } else {
            return $this->failNotFound('Product not found');
        }
    }

    public function updateProduct($id = null)
{
    $data = $this->request->getJSON();

    $product = $this->model->find($id);

    if (!$product) {
        return $this->failNotFound('Product not found');
    }

    if (!$data) {
        return $this->failValidationError('No data to update');
    }

    $updatedData = array_merge($product, (array) $data);

    $validationRules = [];

    if (isset($data->name)) {
        $validationRules['name'] = 'permit_empty|string';
    }

    if (isset($data->description)) {
        $validationRules['description'] = 'permit_empty|string';
    }

    if (isset($data->price)) {
        $validationRules['price'] = 'permit_empty|numeric';
    }

    if (!empty($validationRules) && !$this->validate($validationRules)) {
        return $this->fail($this->validator->getErrors());
    }
    $this->model->update($id, $updatedData);

    return $this->respond($updatedData);
}


    public function delete($id = null)
    {
        $product = $this->model->find($id);
        if ($product) {
            $this->model->delete($id);
            return $this->respondDeleted('Product deleted');
        } else {
            return $this->failNotFound('Product not found');
        }
    }
}