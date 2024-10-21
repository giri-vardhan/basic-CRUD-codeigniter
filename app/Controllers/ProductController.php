<?php

namespace App\Controllers;

// use App\Models\ProductModel;

use App\Models\ProductModel;
use CodeIgniter\RESTful\ResourceController;

class ProductController extends ResourceController
{
    protected $model;
    protected $format = 'json';

    public function __construct(){
        $this->model = new ProductModel();
    }
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
        $data = $this->request->getPost(); // Get POST data
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
        $data = $this->request->getRawInput(); // Get PUT data
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

    public function patch($id = null)
    {
        $data = $this->request->getRawInput();

        $product = $this->model->find($id);

        if (!$product) {
            return $this->failNotFound('Product not found');
        }

        // Merge the existing data with the new PATCH data
        $updatedData = array_merge($product, $data);

        // Optional: You can validate only the fields that are passed
        if (!$this->validate([
            'name'  => 'permit_empty|string',
            'price' => 'permit_empty|numeric'
        ])) {
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