<?php
class ControllerSaleSalereport extends Controller {
	public function index() {

		$this->load->language('sale/salereport');

		$this->document->setTitle($this->language->get('heading_title'));
        
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/salereport', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		if(isset($this->request->get['filter_month']) && $this->request->get['filter_month']){
			$data['filter_month'] = $month = $this->request->get['filter_month'];
		}else{
			$data['filter_month'] = $month = '';
		}
		if(isset($this->request->get['filter_year']) && $this->request->get['filter_year']){
			$data['filter_year'] = $year = $this->request->get['filter_year'];
		}else{
			$data['filter_year'] = $year = '';
		}
		if(isset($this->request->get['filter_date_start']) && $this->request->get['filter_date_start']){
			$data['filter_date_start'] = $filter_date_start = $this->request->get['filter_date_start'];
		}else{
			$data['filter_date_start'] = $filter_date_start = '';
		}
		if(isset($this->request->get['filter_date_end']) && $this->request->get['filter_date_end']){
			$data['filter_date_end'] = $filter_date_end = $this->request->get['filter_date_end'];
		}else{
			$data['filter_date_end'] = $filter_date_end = '';
		}

		if(isset($this->request->get['filter_date']) && $this->request->get['filter_date']){
			$data['filter_date'] = $filter_date = $this->request->get['filter_date'];
		}else{
			$data['filter_date'] = $filter_date = '';
		}

		if(isset($this->request->get['filter_payment']) && $this->request->get['filter_payment']){
			$data['filter_payment'] = $filter_payment = $this->request->get['filter_payment'];
		}else{
			$data['filter_payment'] = $filter_payment = '';
		}
		// Check install directory exists
		if (is_dir(DIR_APPLICATION . 'install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}
		
		// Dashboard Extensions
		$dashboards = array();
        
		$this->load->model('setting/extension');
		$this->load->model('sale/salereport');

		// Get a list of installed modules
		$extensions = $this->model_setting_extension->getInstalled('dashboard');
		
		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $code) {
			if ($this->config->get('dashboard_' . $code . '_status') && $this->user->hasPermission('access', 'extension/dashboard/' . $code) && $code == 'order') {
				$output = $this->load->controller('extension/dashboard/' . $code . '/dashboard');
				
				if ($output) {
					$dashboards[] = array(
						'code'       => $code,
						'width'      => $this->config->get('dashboard_' . $code . '_width'),
						'sort_order' => 1,
                        'output'     => $output,
                        'order'      => 'Complete',
						'order_status_id'  => 5,
						'count'      => $this->model_sale_salereport->getTotalOrders(array('filter_order_status_id' => '5,23','filter_month' => $month, 'filter_year' => $year,'filter_date_start' => $filter_date_start,'filter_date_end' => $filter_date_end, 'filter_payment' => $filter_payment, 'filter_date' => $filter_date))
                    );
                    $dashboards[] = array(
						'code'       => $code,
						'width'      => $this->config->get('dashboard_' . $code . '_width'),
						'sort_order' => 2,
                        'output'     => $output,
                        'order'      => 'Pending',
						'order_status_id'  => 1,
						'count'      => $this->model_sale_salereport->getTotalOrders(array('filter_order_status_id' => 1,'filter_month' => $month, 'filter_year' => $year,'filter_date_start' => $filter_date_start,'filter_date_end' => $filter_date_end,'filter_payment' => $filter_payment, 'filter_date' => $filter_date))
                    );
                    $dashboards[] = array(
						'code'       => $code,
						'width'      => $this->config->get('dashboard_' . $code . '_width'),
						'sort_order' => 3,
                        'output'     => $output,
                        'order'      => 'Canceled',
						'order_status_id'  => 7,
						'count'      => $this->model_sale_salereport->getTotalOrders(array('filter_order_status_id' => '7,10','filter_month' => $month, 'filter_year' => $year,'filter_date_start' => $filter_date_start,'filter_date_end' => $filter_date_end,'filter_payment' => $filter_payment, 'filter_date' => $filter_date))
                    );
                    $dashboards[] = array(
						'code'       => $code,
						'width'      => $this->config->get('dashboard_' . $code . '_width'),
						'sort_order' => 4,
                        'output'     => $output,
                        'order'      => 'Processing',
						'order_status_id'  => 2,
						'count'      => $this->model_sale_salereport->getTotalOrders(array('filter_order_status_id' => '15,2','filter_month' => $month, 'filter_year' => $year,'filter_date_start' => $filter_date_start,'filter_date_end' => $filter_date_end,'filter_payment' => $filter_payment,'filter_date' => $filter_date))
                    );
                    $dashboards[] = array(
						'code'       => $code,
						'width'      => $this->config->get('dashboard_' . $code . '_width'),
						'sort_order' => 5,
                        'output'     => $output,
                        'order'      => 'Shipped',
						'order_status_id'  => 3,
						'count'      => $this->model_sale_salereport->getTotalOrders(array('filter_order_status_id' => 3,'filter_month' => $month, 'filter_year' => $year,'filter_date_start' => $filter_date_start,'filter_date_end' => $filter_date_end,'filter_payment' => $filter_payment,'filter_date' => $filter_date))
                    );
                    		$dashboards[] = array(
						'code'       => $code,
						'width'      => $this->config->get('dashboard_' . $code . '_width'),
						'sort_order' => 6,
						'output'     => $output,
						'order'      => 'Monthly Sale Amount',
						'order_status_id'  => '',
						'count'      => $this->model_sale_salereport->getTotalSales(array('filter_order_status' => 5, 'monthly' => 1,'filter_month' => $month, 'filter_year' => $year,'filter_payment' => $filter_payment, 'filter_date' => $filter_date))
					);
			
					$dashboards[] = array(
						'code'       => $code,
						'width'      => $this->config->get('dashboard_' . $code . '_width'),
						'sort_order' => 7,
						'output'     => $output,
						'order'      => 'Daily Sale Amount',
						'order_status_id'  => '',
						'count'      => $this->model_sale_salereport->getTotalSales(array('daily' => 1,'filter_payment' => $filter_payment, 'filter_date' => $filter_date))
					);
				}
			}
		}

        $sort_order = array();
        
        $data['order'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'], true);

		foreach ($dashboards as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $dashboards);
		
		// Split the array so the columns width is not more than 12 on each row.
		$width = 0;
		$column = array();
        $data['rows'] = array();
        
		foreach ($dashboards as $key => $dashboard) {
			$column[] = $dashboard;
			$width = ($width + $dashboard['width']);
			
			if ($width >= 12 && $key < 8) {
				$data['rows'][] = $column;				
				$width = 0;
				$column = array();
            }
            if($key == 8 || $key == 12){
			  $data['rows'][] = $column;
			}
			// $data['rows'][] = $column;
		}
	    // unset($data['rows'][3][4]);	
		$data['rows'][1][0] = array(
			'code'       => $code,
			'width'      => $this->config->get('dashboard_' . $code . '_width'),
			'sort_order' => 10,
			'output'     => $output,
			'order'      => 'Shipped',
			'order_status_id'  => 3,
			'count'      => $this->model_sale_salereport->getTotalOrders(array('filter_order_status_id' => 3,'filter_month' => $month, 'filter_year' => $year,'filter_date_start' => $filter_date_start,'filter_date_end' => $filter_date_end,'filter_payment' => $filter_payment,'filter_date' => $filter_date))
		);
		$data['rows'][2][0] = array(
			'code'       => $code,
			'width'      => $this->config->get('dashboard_' . $code . '_width'),
			'sort_order' => 10,
			'output'     => $output,
			'order'      => 'Monthly Sale Amount',
			'order_status_id'  => '',
			'count'      => $this->model_sale_salereport->getTotalSales(array('filter_order_status' => 5, 'monthly' => 1,'filter_month' => $month, 'filter_year' => $year))
		);
		$data['rows'][2][1] = array(
			'code'       => $code,
			'width'      => $this->config->get('dashboard_' . $code . '_width'),
			'sort_order' => 10,
			'output'     => $output,
			'order'      => 'Monthly Sale Amount Based Sub Total',
			'order_status_id'  => '',
			'count'      => $this->model_sale_salereport->getTotalSalesOnSubTotal(array('filter_order_status' => 5, 'monthly' => 1,'filter_month' => $month, 'filter_year' => $year))
		);
		$data['rows'][2][2] = array(
			'code'       => $code,
			'width'      => $this->config->get('dashboard_' . $code . '_width'),
			'sort_order' => 11,
			'output'     => $output,
			'order'      => 'Daily Sale Amount',
			'order_status_id'  => '',
			'count'      => $this->model_sale_salereport->getTotalOrders(array('filter_date_added' => date("Y-m-d"))),
			'cod_total'      => $this->model_sale_salereport->getTotalSales(array('daily' => 1,'filter_payment' => 'cod', 'filter_date_added' => date("Y-m-d"))),
			'other_total'      => $this->model_sale_salereport->getTotalSales(array('daily' => 1,'filter_payment' => 'other', 'filter_date_added' => date("Y-m-d")))
		);
    //    unset($data['rows'][2]);
		if (DIR_STORAGE == DIR_SYSTEM . 'storage/') {
			$data['security'] = $this->load->controller('common/security');
		} else {
			$data['security'] = '';
		}
		$data['years'] = array(2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026,2027,2028,2029,2030);
		$data['months'] = array(
			1 => 'Jan',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Apr',
			5 => 'May',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Aug',
			9 => 'Sep',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dec'
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}
		

		$this->response->setOutput($this->load->view('sale/salereport', $data));
	}
}