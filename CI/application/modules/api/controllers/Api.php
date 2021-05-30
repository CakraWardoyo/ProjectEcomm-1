<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */


	function __construct(){
		parent::__construct();
		$this->load->model('ApiModel');
		}
		

	public function index(){
		print_r($this->session->userdata());
		date_default_timezone_set("Asia/Jakarta");
		// $tanggal = date('m-d-Y H:i:s');
		echo '<br>';
		// echo $tanggal;
		$x = 'INV/20210527/0000000';
		// echo $x;
		echo '<br>';
		// echo date('Ymd');
		$next =  substr($x, 8, 4);
		// $ne = $next + 1;
		// echo $ne;
		// echo sprintf('%04s', $ne);
		// $time_now = date_create("07:10");
		// $t = time('H i s');
		// echo($t . "<br /> Sekarang: ");
		// echo $t;

		$day    = date('d');
		$month    = date('m');
		$year    = date('Y');
		// echo '<br>';
		// $days    = (int)((mktime (0,0,0,$month,$day,$year) - time())/(60 * 60 * 24));
		// echo "Masih ada <b>$days</b> hari lagi, sampai tanggal $day/$month/$year";

		$target = mktime(11, 15, 0, $month,$day,$year) ;
		$hari_ini = time();
		$rentang = $target-$hari_ini ;
		$detik = (int) ($rentang) ;
		// echo $detik;
		// $this->load->view('admin toko online/header');
		// $this->load->view('admin toko online/header_seller');
		// $this->load->view('admin toko online/sidebar');
		// $this->load->view('TambahProdukView');
		// $this->load->view('admin toko online/footer');
	}

	public function getAllProducts(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET'){
			$limit = $_GET['limit'];
			$resultResponse = array();
			$resultProducts = $this->ApiModel->getAllProducts($limit);
	
			foreach ($resultProducts as $key => $value) {
				$resultToko = $this->ApiModel->getTokoByIdToko($value['id_toko']);
				$resultImage = $this->ApiModel->getAllProductImagesByIdBarang($value['id_toko'],$value['id_barang']);
				$resultHarga = $this->ApiModel->getAllProductUkuranByIdBarang($value['id_toko'],$value['id_barang']);
				array_push($resultResponse, array(
					'domain' => $resultToko[0]['domain_toko'],
					'fotoBarang' => $resultImage,
					'namaBarang' => $value['nama_barang'],
					'slug' => $value['slug'],
					'hargaBarang' => $resultHarga[0]['harga'],
					'namaToko' => $resultToko[0]['nama_toko'],
					'kotaToko' => $resultToko[0]['kota_toko']
				));
			}
			echo json_encode($resultResponse);
		}
	}

	public function getAllProductsByName(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$resultResponse = array();
			$namaBarang = $_GET['ss'];
			$awalData = 0;
			$jumlahDataPerHalaman = 10;
	
			$resultProducts = $this->ApiModel->getAllProductsByName($namaBarang,	$awalData,$jumlahDataPerHalaman);
			foreach ($resultProducts as $key => $value) {
				$resultToko = $this->ApiModel->getTokoByIdToko($value['id_toko']);
				$resultProductImages = $this->ApiModel->getProductImagesByIdBarang($value['id_barang']);
				array_push($resultResponse, array(
					'idBarang' => $value['id_barang'],
					'namaBarang' => $value['nama_barang'],
					'slug' => $value['slug'],
					'toko' => array(
						'domainToko' => $resultToko[0]['domain_toko']
					),
					'foto' => $resultProductImages
				));
			}
			echo json_encode($resultResponse);
		}
	}

	public function search(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$resultProducts = array();
			$resultResponse = array();
	
			if (isset($_GET['nama_barang']) && !isset($_GET['page'])) {
				$namaBarang = $_GET['nama_barang'];
				$awalData = 0;
				$halamanAktif = 1;
				$jumlahDataPerHalaman = 50;
			} else if (isset($_GET['nama_barang']) && isset($_GET['page'])) {
				$namaBarang = $_GET['nama_barang'];
				$halamanAktif = $_GET['page'] == 0 ? 1: $_GET['page'];
				$jumlahDataPerHalaman = 50;
				$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
			} else {
				$namaBarang = '';
				$halamanAktif = isset($_GET['page']) ? $_GET['page'] == 0 ? 1: $_GET['page'] : 1;
				$jumlahDataPerHalaman = 50;
				$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
			}

			$resultProductsDb = $this->ApiModel->getAllProductsByName($namaBarang,	$awalData,$jumlahDataPerHalaman);
				foreach ($resultProductsDb as $key => $value) {
					$resultToko = $this->ApiModel->getTokoByIdToko($value['id_toko']);
					$resultImages = $this->ApiModel->getProductImagesByIdBarang($value['id_barang']);
					$resultHarga = $this->ApiModel->getProductHargaByIdBarang($value['id_barang']);
					array_push($resultProducts, array(
						'idBarang' => $value['id_barang'],
						'namaBarang' => $value['nama_barang'],
						'slug' => $value['slug'],
						'toko' => array(
							'namaToko' => $resultToko[0]['nama_toko'],
							'domainToko' => $resultToko[0]['domain_toko'],
							'kotaToko' => $resultToko[0]['kota_toko']
						),
						'foto' => $resultImages,
						'harga' => $resultHarga
					));
				}

				$jumlahHalaman = $this->ApiModel->getJumlahHalaman($namaBarang, $jumlahDataPerHalaman);

				array_push($resultResponse, array(
					'namaPencarian' => $namaBarang,
					'halamanAktif' => (int) $halamanAktif,
					'jumlahHalaman' => $jumlahHalaman,
					'products' => $resultProducts
				));

				echo json_encode($resultResponse[0]);
		}
	}

	public function getAllToko(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$total = 0;
			$resultResponse = array();
			$t = $this->ApiModel->getAllToko();
			foreach ($t as $key => $value) {
				$rD = $this->ApiModel->getAllProductsByIdToko($value['id_toko']);
				foreach ($rD as $key => $v) {
					$total++;
				}
				array_push($resultResponse, array(
					'namaToko' => $value['nama_toko'],
					'domainToko' => $value['domain_toko'],
					'kotaToko' => $value['kota_toko'],
					'gambarToko' => $value['gambar_toko'],
					'produkToko' => $total
				));
				$total = 0;
			}
			echo json_encode($resultResponse);
		}
	}

	public function getAllProductsByIdToko(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$idToko = $this->session->userdata('id_toko');
			$resultResponse = array();
			$resultProductsData = $this->ApiModel->getAllProductsByIdToko($idToko);
			
			foreach ($resultProductsData as $key => $value) {
				$resultProductImages = $this->ApiModel->getAllProductImagesByIdBarang($idToko,$value['id_barang']);
				$resultProductSize = $this->ApiModel->getAllProductUkuranByIdBarang($idToko,$value['id_barang']);
				array_push($resultResponse, array(
					'idToko' => $value['id_toko'],
					'idBarang' => $value['id_barang'],
					'namaBarang' => $value['nama_barang'],
					'kondisiBarang' => $value['kondisi_barang'],
					'deskripsiBarang' => $value['deskripsi_barang'],
					'dataFoto' => $resultProductImages,
					'dataUkuran' => $resultProductSize
				));
			}
			echo json_encode($resultResponse);
		}
	}

	public function getProductByIdBarang(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$idToko = $this->session->userdata('id_toko');
			$idBarang = $this->uri->segment(3);
			$resultResponse = array();
			$resultProduct = $this->ApiModel->getProductByIdBarang($idToko,$idBarang);
	
			foreach ($resultProduct as $key => $value) {
				$resultProductImages = $this->ApiModel->getAllProductImagesByIdBarang($idToko,$value['id_barang']);
				$resultProductSize = $this->ApiModel->getAllProductUkuranByIdBarang($idToko,$value['id_barang']);
				array_push($resultResponse, array(
					'idToko' => $value['id_toko'],
					'idBarang' => $value['id_barang'],
					'namaBarang' => $value['nama_barang'],
					'slug' => $value['slug'],
					'kondisiBarang' => $value['kondisi_barang'],
					'deskripsiBarang' => $value['deskripsi_barang'],
					'dataFoto' => $resultProductImages,
					'dataUkuran' => $resultProductSize
				));
			}
			echo json_encode($resultResponse);
		}
	}

	public function getProductBySlug(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$domain = $this->uri->segment(3);
			$slug = $this->uri->segment(4);
			$resultResponse = array();

			$infoToko = $this->ApiModel->getTokoByDomain($domain);
			$idToko = $infoToko[0]['id_toko'];

			$resultProductsData = $this->ApiModel->getProductBySlug($idToko,$slug);

			foreach ($resultProductsData as $key => $value) {
				$resultProductImages = $this->ApiModel->getAllProductImagesByIdBarang($idToko,$value['id_barang']);
				$resultProductSize = $this->ApiModel->getAllProductUkuranByIdBarang($idToko,$value['id_barang']);
				array_push($resultResponse, array(
					'idToko' => $value['id_toko'],
					'namaToko' => $infoToko[0]['nama_toko'],
					'domain' => $infoToko[0]['domain_toko'],
					'idBarang' => $value['id_barang'],
					'namaBarang' => $value['nama_barang'],
					'kondisiBarang' => $value['kondisi_barang'],
					'deskripsiBarang' => $value['deskripsi_barang'],
					'dataFoto' => $resultProductImages,
					'dataUkuran' => $resultProductSize
				));
			}
			echo json_encode($resultResponse);
		}
	}

	public function getProductsByDomain(){
		// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$resultResponse = array();
			$products = array();
			$t = $this->ApiModel->getTokoByDomain($this->uri->segment(3))[0];
			$p = $this->ApiModel->getAllProductsByIdToko($this->ApiModel->getTokoByDomain($this->uri->segment(3))[0]['id_toko']);
			foreach ($p as $key => $value) {
				$resultImage = $this->ApiModel->getAllProductImagesByIdBarang($value['id_toko'],$value['id_barang']);
				$resultHarga = $this->ApiModel->getAllProductUkuranByIdBarang($value['id_toko'],$value['id_barang']);
				array_push($products, array(
						'namaBarang' => $value['nama_barang'],
						'slug' => $value['slug'],
						'foto' => $resultImage,
						'harga' => $resultHarga[0]['harga']
					));
			}
			array_push($resultResponse, array(
				'toko' => array(
					'namaToko' => $t['nama_toko'],
					'domainToko' => $t['domain_toko'],
					'gambarToko' => $t['gambar_toko'],
					'kotaToko' => $t['kota_toko']
				),
				'products' => $products
			));

			echo json_encode($resultResponse[0]);
		// }
	}

	public function getDataUkuranByIdUkuran(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$idUkuran = $this->uri->segment(3);
			$resultDataUkuran = $this->ApiModel->getDataUkuranByIdUkuran($idUkuran);
			echo json_encode($resultDataUkuran);
		}
	}

	public function login(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$rL = $this->ApiModel->login($email);

			if ($rL -> num_rows() == 1) {
				$res = $rL->result_array()[0];
				$passwordDb = $res['password'];
				if (password_verify($password, $passwordDb)) {
					$rT = $this->ApiModel->getTokoData($res['id_user'])->result_array();
					$this->session->set_userdata('id_user', $res['id_user']);
        	$this->session->set_userdata('username', $res['username']);
        	$this->session->set_userdata('id_toko', $rT != null ? $rT[0]['id_toko'] : '');
        	$this->session->set_userdata('nama_toko', $rT != null ? $rT[0]['nama_toko'] : '');

					echo json_encode(array(
						'statusLogin' => true,
						'message' => 'Login berhasil'
					));
				}else{
					echo json_encode(array(
						'statusLogin' => false,
						'message' => 'Password salah'
					));
				}
			} else {
				echo json_encode(array(
					'statusLogin' => false,
					'message' => 'Ups.. email belum terdaftar'
				));
			}
		}
	}

	public function logout(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->ApiModel->logout();
			echo json_encode(array(
				'statusLogout' => true
			));
		}
	}

	public function checkUsername(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username =  $this->input->post('username');
			$isAvailabel = $this->ApiModel->isAvailableUsername($username);
	
			if ($isAvailabel != null) {
				echo json_encode(array(
					'availabel' => false,
					'status' => 'error',
					'message' => 'Username sudah terdaftar'
				));
			} else {
				echo json_encode(array(
					'availabel' => true,
					'status' => 'success',
					'message' => 'Username tersedia'
				));
			}
		}
	}

	public function checkEmail(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = $this->input->post('email');
			$isAvailabel = $this->ApiModel->isAvailableEmail($email);

			if ($isAvailabel != null) {
				echo json_encode(array(
					'availabel' => false,
					'status' => 'error',
					'message' => 'Email sudah terdaftar'
				));
			} else {
				echo json_encode(array(
					'availabel' => true,
					'status' => 'success',
					'message' => 'Email tersedia'
				));
			}
		}
	}

	public function signUpVerification(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['username'] = $this->input->post('username');
			$data['email'] = $this->input->post('email');
			$data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
			$data['nomer_hp'] = 0;
			$data['code_otp'] = rand(9999, 1111);
			$data['status_email'] = 'Verifikasi';

			if ($this->ApiModel->createUser($data)) {
				$subject = "Email Verification Code";
				$message = "QShoes - JANGAN MEMBERI TAHU KODE INI KE SIAPAPUN termasuk pihak QShoes " . $data['code_otp'];
				$sender = "From: kurohikoblacknight5601@gmail.com";
				if(mail($data['email'], $subject, $message, $sender)){
					echo json_encode(array(
						'statusSended' => true,
						'status' => 'success',
						'message' => 'Kode telah dikirim melalui email ke '.$data['email']
					));
					}else{
						$this->ApiModel->deleteAccountBeforeVerification($data['email']);

							echo json_encode(array(
								'statusSended' => false,
								'status' => 'error',
								'message' => 'Gagal saat mengirim kode'
							));
					}
			} else {
				echo json_encode(array(
					'statusSended' => false,
					'status' => 'error',
					'message' => 'Gagal mendaftar'
				));
			}
		}
	}

	public function resendCode(){
		$email = $this->input->post('email');
		$data['code_otp'] = rand(9999, 1111);
		$this->ApiModel->resendCode($data,$email);

		$subject = "Email Verification Code";
		$message = "QShoes - JANGAN MEMBERI TAHU KODE INI KE SIAPAPUN termasuk pihak QShoes " . $data['code_otp'];
		$sender = "From: kurohikoblacknight5601@gmail.com";

		if(mail($email, $subject, $message, $sender)){
			echo json_encode(array(
				'statusSended' => true,
				'status' => 'success',
				'message' => 'Kode baru telah dikirim melalui email ke '.$email
			));
			}else{
				echo json_encode(array(
					'statusSended' => false,
					'status' => 'error',
					'message' => 'Gagal saat mengirim kode'
				));
			}

	}

	public function checkOtpCodeByEmail(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = $this->input->post('email');
			$code = $this->input->post('code');
			$resultOtp = $this->ApiModel->checkOtpCodeByEmail($email,$code);

			if ($resultOtp != null) {
				$data['code_otp'] = 0;
				$data['status_email'] = 'Terverifikasi';
				$this->ApiModel->updateVerificationEmail($email,$data);
				$this->session->set_userdata('id_user', $resultOtp[0]['id_user']);
				$this->session->set_userdata('username', $resultOtp[0]['username']);
				$this->session->set_userdata('id_toko', '');

				echo json_encode(array(
					'status' => 'success',
					'message' => 'Email '. $email .' sudah Terverifikasi'
				));
			} else {
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Anda memasukkan kode yang salah'
				));
			}
		}
	}

	public function deleteAccountBeforeVerification(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = $this->input->post('email');
			
			if ($this->ApiModel->deleteAccountBeforeVerification($email)) {
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Email '. $email .' tidak jadi mendaftar'
				));
			} else {
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Email '. $email .' gagal dihapus'
				));
			}
			
		}
	}

	public function getcity(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=".$this->input->get('province'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: 276589ca70c66fd10d6d11715bce70ca"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
	}

	
}
