<?php echo $header ?> <?php echo $column_left ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" data-toggle="tooltip" title="<?php echo $button_filter ?>" onclick="$('#filter-order').toggleClass('hidden-sm hidden-xs');" class="btn btn-default hidden-md hidden-lg"><i class="fa fa-filter"></i></button>
        {# <button type="button" id="button-setting" title="<?php echo $button_setting ?>" data-loading-text="<?php echo $text_loading ?>" class="btn btn-info"><i class="fa fa-cog"></i></button> #}
      </div>
      <h1><?php echo $heading_title ?></h1>
      <ul class="breadcrumb">
        <?php foreach($breadcrumbs as $breadcrumb){ ?>
          <li><a href="<?php echo $breadcrumb['href'] ?>"><?php echo $breadcrumb['text'] ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if($error_install) { ?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close pull-right" data-dismiss="alert">&times;</button>
      <i class="fa fa-exclamation-circle"></i> <?php echo $error_install ?></div>
    <?php } ?>
    <div id="filter-order" class="col-md-3 col-md-push-9 col-sm-12 hidden-sm hidden-xs">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-filter"></i> <?php echo $text_filter ?></h3>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <label class="control-label" for="input-year">Year</label>
            <select name="filter_year" id="input-year" class="form-control">
              <option value="0" selected="selected">None</option>
              <?php foreach($years as $year){ ?>
              <?php if($year == $filter_year){ ?>
              
              <option value="<?php echo $year ?>" selected="selected"><?php echo $year ?></option>
              
              <?php }else{ >
              
              <option value="<?php echo $year ?>"><?php echo $year ?></option>
              
              <?php } ?>
            <?php } ?>
          </select>
          </div>
          <div class="form-group">
            <label class="control-label" for="input-month-name">Month</label>
            <select name="filter_month" id="input-month-name" class="form-control">
              <option value="0" selected="selected">None</option>
            <?php foreach($months as $key => $month){ ?>
              <?php if($key == $filter_month){ ?>
              
              <option value="<?php echo $key ?>" selected="selected"><?php echo $month ?></option>
              
              <?php }else{ ?>
              
                <option value="<?php echo $key ?>"><?php echo $month ?></option>
              
              <?php } ?>
            <?php } ?>
          </select>
          </div>
          <div class="form-group hide">
            <label class="control-label" for="input-month-name"><?php echo $'Payment Method' }}</label>
            <select name="filter_payment" id="input-payment" class="form-control">
              <option value="0" selected="selected">None</option>
              {% if filter_payment == 'payu' %}
              <option value="payu" selected="selected"><?php echo $'Pay U Money' }}</option>
              <option value="ccavenuepay"><?php echo $'CC Avenue' }}</option>
              {% elseif filter_payment == 'ccavenuepay' %}
              <option value="payu"><?php echo $'Pay U Money' }}</option>
              <option value="ccavenuepay" selected="selected"><?php echo $'CC Avenue' }}</option>
              {% else %}
              <option value="payu"><?php echo $'Pay U Money' }}</option>
              <option value="ccavenuepay"><?php echo $'CC Avenue' }}</option>
              {% endif %}
            </select>
          </div>
          <div class="form-group">
            <label class="control-label" for="input-date-added">Start Date</label>
            <div class="input-group date">
              <input type="text" name="filter_date_start" value="<?php echo $filter_date_start ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
              <span class="input-group-btn">
              <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
              </span> </div>
          </div>
          <div class="form-group">
            <label class="control-label" for="input-date-added">End Date</label>
            <div class="input-group date">
              <input type="text" name="filter_date_end" value="<?php echo $filter_date_end ?>" placeholder="End Date" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
              <span class="input-group-btn">
              <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
              </span> </div>
          </div>
          <div class="form-group">
            <label class="control-label" for="input-date">Date Filter</label>
            <div class="input-group date">
              <input type="text" name="filter_date" value="<?php echo $filter_date ?>" placeholder="Date Filter" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
              <span class="input-group-btn">
              <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
              </span> </div>
          </div>
          <div class="form-group text-right">
            <button type="button" id="button-filter" class="btn btn-default"><i class="fa fa-filter"></i> <?php echo $button_filter ?></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9 col-md-pull-3 col-sm-12">
    <?php foreach($rows as $row){ ?>
    <div class="row"><?php foreach($row as $dashboard_1){ ?>
      <?php $class = 'col-md-3 col-sm-6' ?>
      <?php foreach($row as $dashboard_2){ ?>
      <?php if($dashboard_2['width'] > 3){ ?>
      <?php $class = 'col-md-12 col-sm-12' ?>
      <?php }} ?>
  
      <div class="<?php echo $class ?>">
      <div class="tile tile-primary">
  <div class="tile-heading">Order<span class="pull-right">
    <?php if($percentage > 0){ ?> 
    <i class="fa fa-caret-up"></i>
    <?php }else if($percentage < 0){ ?>
    <i class="fa fa-caret-down"></i>
    <?php } ?>
    <?php echo $dashboard_1['count'] ?></span></div>
  <div class="tile-body"><i class="fa fa-shopping-cart"></i>
    <h2 class="pull-right" style="font-size: 20px !important;"><?php echo $dashboard_1['order'] ?><br><?php echo $dashboard_1['count'] ?> <br><?php if ($dashboard_1['cod_total']){ ?> COD:<?php echo $dashboard_1['cod_total'] ?> <?php } ?> <br> <?php if($dashboard_1['other_total']){ ?> Online Order:<?php echo $dashboard_1.other_total ?> <?php } ?></h2>
    {# <h2 class="pull-right" style="font-size: 26px !important;">(<?php echo $dashboard_1.count ?>)</h2> #}
  </div>
  <?php if($filter_year && $filter_month){ ?>
  <div class="tile-footer"><a href="<?php echo $order ?>&filter_order_status_id=<?php echo $dashboard_1['order_status_id'] ?>&filter_month=<?php echo $filter_month ?>&filter_year=<?php echo $filter_year ?>"><?php echo $text_view ?></a></div>
  <?php }else{ ?>
  <div class="tile-footer"><a href="<?php echo $order ?>&filter_order_status_id=<?php echo $dashboard_1['order_status_id'] ?>"><?php echo $text_view ?></a></div>
  <?php } ?>
</div>
      </div>
      <?php } ?></div>
      <?php } ?></div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = '';

	var filter_year = $('select[name=\'filter_year\']').val();

	if (filter_year) {
		url += '&filter_year=' + encodeURIComponent(filter_year);
	}

	var filter_month = $('select[name=\'filter_month\']').val();

	if (filter_month) {
		url += '&filter_month=' + encodeURIComponent(filter_month);
  }
  
  var filter_date_start = $('input[name=\'filter_date_start\']').val();

if (filter_date_start) {
  url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
}
var filter_date_end = $('input[name=\'filter_date_end\']').val();

if (filter_date_end) {
  url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
}

var filter_date = $('input[name=\'filter_date\']').val();

if (filter_date) {
  url += '&filter_date=' + encodeURIComponent(filter_date);
}


var filter_payment = $('select[name=\'filter_payment\']').val();

if (filter_payment) {
  url += '&filter_payment=' + encodeURIComponent(filter_payment);
}


	location = 'index.php?route=sale/order_filter&token=<?php echo $user_token ?>' + url;
});
$('#button-setting').on('click', function() {
	$.ajax({
		url: 'index.php?route=common/developer&token=<?php echo $token ?>',
		dataType: 'html',
		beforeSend: function() {
			$('#button-setting').button('loading');
		},
		complete: function() {
			$('#button-setting').button('reset');
		},
		success: function(html) {
			$('#modal-developer').remove();
			
			$('body').prepend('<div id="modal-developer" class="modal">' + html + '</div>');
			
			$('#modal-developer').modal('show');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});	
//--></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
$('.date').datetimepicker({
language: '<?php echo $datepicker ?>',
pickTime: false
});
//--></script> 
</div>
<?php echo $footer ?>