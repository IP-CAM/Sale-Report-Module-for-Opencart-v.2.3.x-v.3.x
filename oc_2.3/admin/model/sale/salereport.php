<?php
class ModelSaleSalereport extends Model {
	public function getTotalOrders($data = array()) {
		if(isset($data['filter_product']) && $data['filter_product']){
			$product_id = $this->db->query("SELECT * FROM ". DB_PREFIX ."product_description WHERE `name` LIKE '%". $this->db->escape($data['filter_product']) ."%'")->row['product_id'];
		}else{
			$product_id = 0;
		}

		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN `". DB_PREFIX ."order_product` op ON(op.order_id = o.order_id)";

		// $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o";

		if (!empty($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} elseif (isset($data['filter_order_status_id']) && $data['filter_order_status_id'] !== '') {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status_id']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
			// $sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_order_type'])) {
			if($data['filter_order_type'] == 'cod'){
				$sql .= " AND o.payment_code = 'cod'";
			}else{
				$sql .= " AND o.payment_code != 'cod'";
			}
		}
		if (!empty($data['filter_payment'])) {
			$sql .= " AND o.payment_code = '" . $data['filter_payment'] . "'";
		}
		if (!empty($data['filter_product']) && $product_id) {
			$sql .= " AND op.product_id = '" . (int)$product_id . "'";
		}

		if (!empty($data['missing'])) {
			$sql .= " AND o.order_id NOT IN (SELECT order_id FROM ". DB_PREFIX ."call)";
		}

		if (!empty($data['filter_telephone'])) {
			$sql .= " AND ( o.telephone = '" . (int)$data['filter_telephone'] . "' OR  o.email = '" . $data['filter_telephone'] . "')";
		}

		if (!empty($data['filter_state'])) {
			$sql .= " AND o.shipping_zone LIKE '%" . $this->db->escape($data['filter_state']) . "%'";
		}
		if (!empty($data['filter_order_type'])) {
			if($data['filter_order_type'] == 'cod'){
				$sql .= " AND o.payment_code = '" . $data['filter_order_type'] . "'";
			}elseif($data['filter_order_type'] == 'layerpayment'){
				$sql .= " AND o.payment_code = 'layerpayment'";
			}elseif($data['filter_order_type'] == 'cashfree'){
				$sql .= " AND o.payment_code = 'cashfree'";
			}elseif($data['filter_order_type'] == 'razorpay'){
				$sql .= " AND o.payment_code = 'razorpay'";
			}elseif($data['filter_order_type'] == 'payu'){
				$sql .= " AND o.payment_code = 'payu'";
			}elseif($data['filter_order_type'] == 'ccavenuepay'){
				$sql .= " AND o.payment_code = 'ccavenuepay'";
			}elseif($data['filter_order_type'] == 'wallet'){
				$sql .= " AND o.total = '0'";
			}else{
				$sql .= " AND o.payment_code <> 'cod'";
			}
		}
		if (!empty($data['filter_custom_field'])) {
			// $sql .= " AND `payment_custom_field` NOT LIKE '%{\"17\":\"\"%'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		if(!empty($data['filter_date'])){
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date']) . "')";
	    }elseif (!empty($data['filter_year']) && !empty($data['filter_month'])) {
			$sql .= " AND DATE(o.date_modified) >= DATE('" . $this->db->escape($data['filter_year']) . "-" . $this->db->escape($data['filter_month']) . "-01') AND DATE(o.date_modified) <= DATE('" . $this->db->escape($data['filter_year']) . "-" . $this->db->escape($data['filter_month']) . "-31')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) BETWEEN  DATE('" . $this->db->escape($data['filter_date_start']) . "') AND DATE('" . $this->db->escape($data['filter_date_end']) . "')";
		}
		if (!empty($data['filter_date_refund'])) {
			$sql .= " AND DATE(o.estimated_date) = DATE('" . $this->db->escape($data['filter_date_refund']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		// $sql .= " AND o.order_id NOT IN (SELECT order_id FROM ". DB_PREFIX ."customerpartner_to_order)";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getTotalSales($data = array()) {

		$sql = "SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order`";

		if (!empty($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} elseif (isset($data['filter_order_status_id']) && $data['filter_order_status_id'] !== '') {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} elseif(!empty($data['daily'])) {
			$sql .= " WHERE order_status_id > 0 AND order_status_id != 7 AND order_status_id != 11";
		}else{
			$sql .= " WHERE order_status_id = 5";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		// if (!empty($data['filter_date'])) {
		// 	$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		// }

		if (!empty($data['zone_id'])) {
			$sql .= " AND shipping_zone_id = '". (int)$data['zone_id'] ."'";
		}
		if(!empty($data['filter_date'])){
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date']) . "')";
	    }elseif (!empty($data['filter_month']) && !empty($data['filter_month'])) {
			$sql .= " AND DATE(date_modified) >= DATE('" . $this->db->escape($data['filter_year']) . "-" . $this->db->escape($data['filter_month']) . "-01') AND DATE(date_modified) <= DATE('" . $this->db->escape($data['filter_year']) . "-" . $this->db->escape($data['filter_month']) . "-31')";
		}elseif (!empty($data['monthly'])) {
			$sql .= " AND DATE(date_modified) >= '" . date('Y-m-01') . "' AND DATE(date_modified) <= '" . date('Y-m-t')."'";
		}
		if(!empty($data['start_date'])){
			$sql .= " AND date_modified >= '" . $data['start_date'] . "' AND date_modified <= '" . $data['last_date'] ."'";
		}
		if(!empty($data['filter_date'])){
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date']) . "')";
	    }elseif (!empty($data['daily'])) {
			$sql .= " AND DATE(date_added) = '" . date('Y-m-d') . "'";
		}
		if (!empty($data['filter_payment']) && $data['filter_payment'] != 'other') {
			$sql .= " AND payment_code = '" . $data['filter_payment'] . "'";
		}elseif(!empty($data['filter_payment']) && $data['filter_payment'] == 'other'){
			$sql .= " AND payment_code != 'cod'";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
    public function getTotalSalesOnSubTotal($data = array()) {

		$sql = "SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN `". DB_PREFIX ."order_total` ot ON(ot.order_id = o.order_id) WHERE ot.code='sub_total'";

		if (!empty($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " AND (" . implode(" OR ", $implode) . ")";
			}
		} elseif (isset($data['filter_order_status_id']) && $data['filter_order_status_id'] !== '') {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} elseif(!empty($data['daily'])) {
			$sql .= " WHERE o.order_status_id > 0 AND o.order_status_id != 7";
		}else{
			$sql .= " WHERE o.order_status_id = 5";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		// if (!empty($data['filter_date'])) {
		// 	$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		// }

		if (!empty($data['zone_id'])) {
			$sql .= " AND o.shipping_zone_id = '". (int)$data['zone_id'] ."'";
		}
		if(!empty($data['filter_date'])){
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date']) . "')";
	    }elseif (!empty($data['filter_month']) && !empty($data['filter_month'])) {
			$sql .= " AND DATE(o.date_modified) >= DATE('" . $this->db->escape($data['filter_year']) . "-" . $this->db->escape($data['filter_month']) . "-01') AND DATE(o.date_modified) <= DATE('" . $this->db->escape($data['filter_year']) . "-" . $this->db->escape($data['filter_month']) . "-31')";
		}elseif (!empty($data['monthly'])) {
			$sql .= " AND DATE(o.date_modified) >= '" . date('Y-m-01') . "' AND DATE(o.date_modified) <= '" . date('Y-m-t')."'";
		}
		if(!empty($data['start_date'])){
			$sql .= " AND DATE(o.date_modified) >= '" . $data['start_date'] . "' AND DATE(o.date_modified) <= '" . $data['last_date'] ."'";
		}
		if(!empty($data['filter_date'])){
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date']) . "')";
	    }elseif (!empty($data['daily'])) {
			$sql .= " AND DATE(o.date_added) = '" . date('Y-m-d') . "'";
		}
		if (!empty($data['filter_payment']) && $data['filter_payment'] != 'other') {
			$sql .= " AND o.payment_code = '" . $data['filter_payment'] . "'";
		}elseif(!empty($data['filter_payment']) && $data['filter_payment'] == 'other'){
			$sql .= " AND o.payment_code != 'cod'";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
