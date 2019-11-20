<?php

    use Restserver \Libraries\REST_Controller;

    class LayKendaraan extends REST_Controller {
        public function __construct() {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE');
            header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');

            parent::__construct();
            $this->load->model('LayananKendModel');
            $this->load->library('form_validation');
        }

        
        public function index_get() {
            return $this->returnData($this->db->get('services')->result(), false);
        }


        public function index_post($id = null) {
            $validation = $this->form_validation;
            $rule = $this->LayananKendModel->rules();

            if ($id == null) {
                array_push($rule, [
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'required'
                ],
                [
                    'field' => 'price',
                    'label' => 'price',
                    'rules' => 'required'    
                ]);
            } else {
                array_push($rule, [
                    'field' => 'price',
                    'label' => 'price',
                    'rules' => 'required'
                ]);
            }

            $validation->set_rules($rule);

            if (!$validation->run()) 
                return $this->returnData($this->form_validation->error_array(), true);
            
            $LayKendaraan = new LayKendaraanData();
            $LayKendaraan->name = $this->post('name');
            $LayKendaraan->price = $this->post('price');
            $LayKendaraan->type = $this->post('type');
            $LayKendaraan->created_at = $this->post('created_at');


            if ($id == null) 
                $response = $this->LayananKendModel->store($LayKendaraan);
            else 
                $response = $this->LayananKendModel->update($LayKendaraan, $id);

            return $this->returnData($response['msg'], $response['error']);
        }


        public function index_delete($id = null) {
            if ($id == null)
                return $this->returnData('Parameter ID Tidak Ditemukan', true);

            $response = $this->LayananKendModel->destroy($id);
            return $this->returnData($response['msg'], $response['error']);
        }

        public function returnData($msg, $error) {
            $response['error'] = $error;
            $response['message'] = $msg;

            return $this->response($response);
        }
    }


    class LayKendaraanData {
        public $name;
        public $price;
        public $type;
        public $created_at;
    }

?>