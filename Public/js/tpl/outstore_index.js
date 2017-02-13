$(".btn").button();
$("#osm_date_start").datepicker({
	changeYear: true
});
$("#osm_date_end").datepicker({
	changeYear: true
});
$("#fastSearch").click(function() {
	window.location.href = "./index.php?s=/Outstore/index/searchBy/" + $("#searchBy").val() + "/keyword/" + $("#keyword").val()
});
$("#btnAdd").click(function() {
	window.location.href = "./index.php?s=/Outstore/add"
});

$('#btnExport').click(function() {
	window.location.href = './index.php?s=/Outstore/export'
});

function del(osm_id) {
	if (confirm("确认删除吗？")) {
		window.location.href = "./index.php?s=/Outstore/delete/osm_id/" + osm_id
	}
};

function clearTip(obj) {
	if ($(obj).val() == '请输入关键字或空格') {
		$(obj).attr('style', 'color:#000');
		$(obj).val('')
	}
};

function fillTip(obj) {
	if ($(obj).val() == '') {
		$(obj).attr('style', 'color:#CCC');
		$(obj).val('请输入关键字或空格')
	}
};


function toSearch() {
	$("#dialog").dialog({
		height: 400,
		width: 650,
		modal: true,
		buttons: {
			"确认": function() {
				var content = $("#dialog").contents();
				var osm_sn = content.find("input[name='osm_sn']").val();
				var osm_customer_name = content.find("input[name='osm_customer_name']").val();
				// var osm_operator_name = content.find("input[name='osm_operator_name']").val();
				var oss_total_start = content.find("input[name='oss_total_start']").val();
				var oss_total_end = content.find("input[name='oss_total_end']").val();
				var oss_store_name = content.find("input[name='oss_store_name']").val();
				var osm_writer_name = content.find("input[name='osm_writer_name']").val();
				var osm_date_start = content.find("input[name='osm_date_start']").val();
				var osm_date_end = content.find("input[name='osm_date_end']").val();
				var oss_prodname = content.find("input[name='oss_prodname']").val();
				action_url = "./index.php?s=/Outstore/index";
				if (osm_sn != "") action_url += "/osm_sn/" + osm_sn;
				if (osm_customer_name != "请输入关键字或空格") action_url += "/osm_customer_name/" + osm_customer_name;
				// if (osm_operator_name != "请输入关键字或空格") action_url += "/osm_operator_name/" + osm_operator_name;
				if (oss_total_start != "") action_url += "/oss_total_start/" + oss_total_start;
				if (oss_total_end != "") action_url += "/oss_total_end/" + oss_total_end;
				if (oss_store_name != "请输入关键字或空格") action_url += "/oss_store_name/" + oss_store_name;
				if (osm_writer_name != "请输入关键字或空格") action_url += "/osm_writer_name/" + osm_writer_name;
				if (osm_date_start != "") action_url += "/osm_date_start/" + osm_date_start;
				if (osm_date_end != "") action_url += "/osm_date_end/" + osm_date_end;
				if (oss_prodname != "请输入关键字或空格") action_url += "/oss_prodname/" + oss_prodname;
				window.location.href = action_url
			},
			'取消': function() {
				$(this).dialog("close")
			}
		}
	})
};

function view(id) {
	$.FrameDialog.create({
		url: "./index.php?s=/Outstore/view/osm_id/" + id,
		title: '查看',
		height: '400',
		width: '600'
	}).bind('dialogclose', function(event, ui) {
		if ($.FrameDialog._results == "OK") {} else {}
	})
};

function toEdit(id) {
	$.FrameDialog.create({
		url: "./index.php?s=/Outstore/toEdit/osm_id/" + id,
		title: '编辑',
		height: '400',
		width: '600'
	}).bind('dialogclose', function(event, ui) {
		if ($.FrameDialog._results == "OK") {} else {}
	})
};


$(function() {
	$("#oss_prodname").autocomplete({
		source: './index.php?s=/Outstore/getProduct',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$('#oss_prodname').val(ui.item.prod_name)
		}
	});
	$("#osm_customer_name").autocomplete({
		source: './index.php?s=/Outstore/getCustomer',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$("#osm_customer_name").val(ui.item.value)
		}
	});
	$("#osm_operator_name").autocomplete({
		source: './index.php?s=/Outstore/getOperator',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$("#osm_operator_name").val(ui.item.value)
		}
	});
	$("#osm_writer_name").autocomplete({
		source: './index.php?s=/Outstore/getOperator',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$("#osm_writer_name").val(ui.item.value)
		}
	});
	$("#oss_store_name").autocomplete({
		source: './index.php?s=/Outstore/getStore',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$("#oss_store_name").val(ui.item.value)
		}
	})
});