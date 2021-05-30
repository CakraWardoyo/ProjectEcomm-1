<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  class ApiModel extends CI_Model{

    public function login($email){
      return $this->db->get_where('user',array('email' => $email));
    }

    public function logout(){
      $this->session->unset_userdata('username');
      $this->session->unset_userdata('id_user');
      $this->session->unset_userdata('id_toko');
      $this->session->unset_userdata('nama_toko');
    }

    public function getTokoData($idUser){
      return $this->db->get_where('toko',array('id_user' => $idUser));
    }

    public function resendCode($data,$email){
      $this->db->update('user', $data, array('email' => $email));
    }

    public function deleteAccountBeforeVerification($email){
      return $this->db->delete('user', array('email' => $email));
    }

    public function updateVerificationEmail($email,$data){
      $this->db->update('user', $data, array('email' => $email));
    }

    public function checkOtpCodeByEmail($email,$code){
      $query = $this->db->query("SELECT * FROM user WHERE email = '$email' AND code_otp = '$code'");
      return $query->result_array();
    }

    public function createUser($data){
      return $this->db->insert('user', $data);
    }

    public function isAvailableEmail($email){
      $query = $this->db->query("SELECT * FROM user WHERE email = '$email'");
      return $query->result_array();
    }

    public function isAvailableUsername($username){
      $query = $this->db->query("SELECT * FROM user WHERE username = '$username'");
      return $query->result_array();
    }

    function getDataUkuranByIdUkuran($idUkuran){
      $query = $this->db->query("SELECT * FROM ukuran WHERE id_ukuran = '$idUkuran'");
      return $query->result_array()[0];
    }

    function getTokoByDomain($domain){
      $query = $this->db->query("SELECT * FROM toko WHERE domain_toko = '$domain'");
      return $query->result_array();
    }

    function getProductBySlug($idToko,$slug){
      $result = $this->db->query("SELECT * FROM barang WHERE id_toko = '$idToko' AND slug = '$slug'");
      return $result->result_array();
    }

    function getTokoByIdToko($idToko){
      $query = $this->db->query("SELECT * FROM toko WHERE id_toko = '$idToko'");
      return $query->result_array();
    }

    public function getAllToko(){
      return $this->db->get('toko')->result_array();
    }

    function getAllProducts($limit){
      $query = $this->db->query("SELECT * FROM barang LIMIT 0,$limit");
      return $query->result_array();
    }

    public function getAllProductsByName($namaBarang,$strt,$limit){
      return $this->db->query("SELECT * FROM barang WHERE nama_barang LIKE '%$namaBarang%' LIMIT $strt,$limit")->result_array();
    }

    public function getJumlahHalaman($namaBarang, $jumlahDataPerHalaman){
      return ceil($this->db->query("SELECT * FROM barang WHERE nama_barang LIKE '%$namaBarang%'")->num_rows() / $jumlahDataPerHalaman);
    }

    function getProductByIdBarang($idToko,$idBarang){
      $result = $this->db->query("SELECT * FROM barang WHERE id_toko = '$idToko' AND id_barang = '$idBarang'");
      return $result->result_array();
    }

    function getAllProductsByIdToko($idToko){
      $result = $this->db->query("SELECT * FROM barang WHERE id_toko = '$idToko'");
      return $result->result_array();
    }
    
    function getAllProductImagesByIdBarang($idToko,$idBarang){
      $result = $this->db->query("SELECT id_foto,nama_foto FROM foto WHERE id_toko = '$idToko' AND id_barang = '$idBarang'");
      return $result->result_array();
    }

    public function getProductImagesByIdBarang($idBarang){
      return $this->db->get_where('foto',array('id_barang' => $idBarang))->result_array();
    }

    public function getProductHargaByIdBarang($idBarang){
      return $this->db->get_where('ukuran',array('id_barang' => $idBarang))->result_array();
    }
    
    function getAllProductUkuranByIdBarang($idToko,$idBarang){
      $result = $this->db->query("SELECT id_ukuran,ukuran,harga,stok FROM ukuran WHERE id_toko = '$idToko' AND id_barang = '$idBarang'");
      return $result->result_array();
    }
  }
?>