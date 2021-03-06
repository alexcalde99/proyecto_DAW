<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		//cargamos la libreria de session
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}

	public function offices()
	{
		$output = $this->grocery_crud->render();

		$this->_example_output($output);
	}

	public function index()	{
		$this->load->view('login');
	}







	//**********************FUNCION LISTAR RESTAURANTES*******************************
	public function restaurantes(){

		$crud = new Grocery_CRUD();
		$crud->set_subject('Restaurantes');
		//DEcimos que el campo imagen sera tipo file, y le decimos la ruta donde guardará el fichero
		$crud->set_field_upload('imagen','assets/images/');
		//nombre de categoria en el restaurante....enla categoria restaurantes, y me sque la descruipcion
		$crud->set_relation('id_categoria','categorias','descripcion');
		//$crud->required_fields('nombre','direccion','telefono','id_categoria');
		$crud->display_as('imagen','Imagen(373x253)');
		$datos = $crud->render();
		$this->cargarVista($datos);

	}
//**********************FUNCION LISTAR CATEGIRIAS*******************************
	public function categorias(){
		$crud = new Grocery_CRUD();
		$crud->set_subject('Categorias');
		$datos = $crud->render();
		$this->cargarVista($datos);

	}
	//**********************FUNCION USUARIOS*******************************
	public function usuarios(){

		$crud = new Grocery_CRUD();
		$crud->set_subject('Usuarios');
		//primer campo, el nombre que tenemos en la tabla en la bdd
		//$crud->set_field_upload('imagen','assets/images/');
		//nombre de categoria en el restaurante....enla categoria restaurantes, y me sque la descruipcion
		//$crud->set_relation('id_categoria','categorias','descripcion');
		$datos = $crud->render();
		$this->cargarVista($datos);

	}
	//**********************FUNCION PRODUCTOS*******************************
	public function productos(){

		$crud = new Grocery_CRUD();
		$crud->set_table('productos');
		//Subject.  lo que saldra al lado del add-> add Restaurates
		$crud->set_subject('Productos');
		//display as_,le decimos que el campo id_restaurante nos lo saque como Restaurante
		$crud->display_as('id_restaurante','Restaurante');
		//DEcimos que el campo imagen sera tipo file, y le decimos la ruta donde guardará el fichero
		$crud->set_field_upload('imagen','assets/images/');
		$crud->display_as('imagen','Imagen(950x550)');
		//Id de la tabla, tabla, y campo que qeremos que sea selecionado en el desplegable
		$crud->set_relation('id_restaurante','restaurantes','nombre');
		$datos = $crud->render();
		$this->cargarVista($datos);

	}

	//**********************FUNCION QUE CARGA LOS RESULTADOS**********************
	public function cargarVista($datos){
		//cargamos la vista ejemplo y le pasamos los datos recogidos
		$this->load->view('admin.php',$datos);
	}


	//*********************FUNCION VALIDAR USUARIO*************************
	public function validar_Usuario() {
		//cargamos el modelo
		$this->load->model('usuario_model');
		//recogemos los valores del formulario
		$usuario = $this->input->post('usuario');
		$password = $this->input->post('password');
		//Llamamos a la funcion validar usuario del modelo, le pasamos los dos datos, nos devolerá true si es correcto,
		//devolverá false si el usuario no existe
		if ($this->usuario_model->validarUsuari($usuario, $password) == true) {
			//Metemos en un array los datos del usuario y lo pasamos a la vista
			//grabamos la variaboe de session
			$this->session->set_userdata('usuario', $usuario);
			$datos = array(
					'user' => $usuario,
					'output' =>""
			);
			$this->load->view('admin', $datos);
		} else {
			//si no es correcto, llamamos a la funcion index(); que nos volvera a cargar la vist del login
			$this->index();
		}
	}

	public function logout(){
		$this->session->unset_userdata('usuario');
		header("Location: http://localhost:8080/proyecto_DAW/admin");
	}





	/*public function offices_management()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('offices');
			$crud->set_subject('Office');
			$crud->required_fields('city');
			$crud->columns('city','country','phone','addressLine1','postalCode');

			$output = $crud->render();

			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function employees_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('employees');
			$crud->set_relation('officeCode','offices','city');
			$crud->display_as('officeCode','Office City');
			$crud->set_subject('Employee');

			$crud->required_fields('lastName');

			$crud->set_field_upload('file_url','assets/uploads/files');

			$output = $crud->render();

			$this->_example_output($output);
	}

	public function customers_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_table('customers');
			$crud->columns('customerName','contactLastName','phone','city','country','salesRepEmployeeNumber','creditLimit');
			$crud->display_as('salesRepEmployeeNumber','from Employeer')
				 ->display_as('customerName','Name')
				 ->display_as('contactLastName','Last Name');
			$crud->set_subject('Customer');
			$crud->set_relation('salesRepEmployeeNumber','employees','lastName');

			$output = $crud->render();

			$this->_example_output($output);
	}

	public function orders_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_relation('customerNumber','customers','{contactLastName} {contactFirstName}');
			$crud->display_as('customerNumber','Customer');
			$crud->set_table('orders');
			$crud->set_subject('Order');
			$crud->unset_add();
			$crud->unset_delete();

			$output = $crud->render();

			$this->_example_output($output);
	}

	public function products_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_table('products');
			$crud->set_subject('Product');
			$crud->unset_columns('productDescription');
			$crud->callback_column('buyPrice',array($this,'valueToEuro'));

			$output = $crud->render();

			$this->_example_output($output);
	}

	public function valueToEuro($value, $row)
	{
		return $value.' &euro;';
	}

	public function film_management()
	{
		$crud = new grocery_CRUD();

		$crud->set_table('film');
		$crud->set_relation_n_n('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname','priority');
		$crud->set_relation_n_n('category', 'film_category', 'category', 'film_id', 'category_id', 'name');
		$crud->unset_columns('special_features','description','actors');

		$crud->fields('title', 'description', 'actors' ,  'category' ,'release_year', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features');

		$output = $crud->render();

		$this->_example_output($output);
	}

	public function film_management_twitter_bootstrap()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('twitter-bootstrap');
			$crud->set_table('film');
			$crud->set_relation_n_n('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname','priority');
			$crud->set_relation_n_n('category', 'film_category', 'category', 'film_id', 'category_id', 'name');
			$crud->unset_columns('special_features','description','actors');

			$crud->fields('title', 'description', 'actors' ,  'category' ,'release_year', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features');

			$output = $crud->render();
			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function multigrids()
	{
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);

		$output1 = $this->offices_management2();

		$output2 = $this->employees_management2();

		$output3 = $this->customers_management2();

		$js_files = $output1->js_files + $output2->js_files + $output3->js_files;
		$css_files = $output1->css_files + $output2->css_files + $output3->css_files;
		$output = "<h1>List 1</h1>".$output1->output."<h1>List 2</h1>".$output2->output."<h1>List 3</h1>".$output3->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}

	public function offices_management2()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('offices');
		$crud->set_subject('Office');

		$crud->set_crud_url_path(site_url(strtolower(__CLASS__."/".__FUNCTION__)),site_url(strtolower(__CLASS__."/multigrids")));

		$output = $crud->render();

		if($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}

	public function employees_management2()
	{
		$crud = new grocery_CRUD();

		$crud->set_theme('datatables');
		$crud->set_table('employees');
		$crud->set_relation('officeCode','offices','city');
		$crud->display_as('officeCode','Office City');
		$crud->set_subject('Employee');

		$crud->required_fields('lastName');

		$crud->set_field_upload('file_url','assets/uploads/files');

		$crud->set_crud_url_path(site_url(strtolower(__CLASS__."/".__FUNCTION__)),site_url(strtolower(__CLASS__."/multigrids")));

		$output = $crud->render();

		if($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}

	public function customers_management2()
	{
		$crud = new grocery_CRUD();

		$crud->set_table('customers');
		$crud->columns('customerName','contactLastName','phone','city','country','salesRepEmployeeNumber','creditLimit');
		$crud->display_as('salesRepEmployeeNumber','from Employeer')
			 ->display_as('customerName','Name')
			 ->display_as('contactLastName','Last Name');
		$crud->set_subject('Customer');
		$crud->set_relation('salesRepEmployeeNumber','employees','lastName');

		$crud->set_crud_url_path(site_url(strtolower(__CLASS__."/".__FUNCTION__)),site_url(strtolower(__CLASS__."/multigrids")));

		$output = $crud->render();

		if($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}*/

}