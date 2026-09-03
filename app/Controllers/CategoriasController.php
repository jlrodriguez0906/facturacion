<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\HTTP\ResponseInterface;

class CategoriasController extends BaseController
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        return view('categorias/index');
    }

    public function getCategorias()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $categorias = $this->categoriaModel->findAll();
        return $this->response->setJSON(['data' => $categorias]);
    }

   public function guardar()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(404);
    }

    $id = $this->request->getPost('id_categoria');
    $nombre = trim($this->request->getPost('nombre'));

    // Definición dinámica de la regla is_unique según exista un ID o no
    $reglaNombre = 'required|min_length[3]|max_length[50]';
    if (!empty($id)) {
        $reglaNombre .= "|is_unique[categoria.nombre,id_categoria,{$id}]";
    } else {
        $reglaNombre .= '|is_unique[categoria.nombre]';
    }

    $validationRules = [
        'nombre' => [
            'label' => 'Nombre',
            'rules' => $reglaNombre,
            'errors' => [
                'required'   => 'El nombre de la categoría es obligatorio.',
                'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                'max_length' => 'El nombre no puede exceder los 50 caracteres.',
                'is_unique'  => 'Esta categoría ya existe.',
            ]
        ]
    ];

    if (!$this->validate($validationRules)) {
        return $this->response->setJSON([
            'status' => 'error',
            'errors' => $this->validator->getErrors()
        ]);
    }

    $data = ['nombre' => $nombre];

    if (!empty($id)) {
        $this->categoriaModel->update($id, $data);
        $message = 'Categoría actualizada correctamente.';
    } else {
        $this->categoriaModel->insert($data);
        $message = 'Categoría registrada con éxito.';
    }

    return $this->response->setJSON([
        'status'  => 'success',
        'message' => $message
    ]);
}

    public function obtener($id)
    {
        $categoria = $this->categoriaModel->find($id);
        if (!$categoria) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Categoría no encontrada.']);
        }
        return $this->response->setJSON(['status' => 'success', 'data' => $categoria]);
    }

    public function eliminar($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        try {
            if ($this->categoriaModel->delete($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Categoría eliminada con éxito.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se puede eliminar la categoría porque tiene productos asociados.'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Ocurrió un error al intentar eliminar.']);
    }
}