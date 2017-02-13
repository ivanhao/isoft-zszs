$(".btn").button();
$("#osm_date_start").datepicker({
	changeYear: true
});
$("#osm_date_end").datepicker({
	changeYear: true
});
$("#fastSearch").click(function() {
	window.location.href = "./index.php?s=/StockManage/stock/searchBy/" + $("#searchBy").val() + "/keyword/" + $("#keyword").val()
});

function toEditStock(stk_id) {
	$("#dialogStock").dialog({
		height: 400,
		width: 650,
		title: '库存编辑',
		modal: true,
		open: function() {
			$.getJSON('./index.php?s=/StockManage/getStockById/stk_id/' + stk_id, function(data) {
				$('#stk_prodname').val(data.stk_prodname);
				$('#stk_cate').val(data.stk_cate);
				$('#stk_price').val(data.stk_price);
				$('#stk_count').val(data.stk_count);
				$('#stk_total').val(data.stk_total);
				$('#stk_store').val(data.stk_store);
				$('#stk_remark').val(data.stk_remark)
			})
		},
		buttons: {
			"确认": function() {
				var stk_prodname = $("#stk_prodname").val();
				var stk_cate = $("#stk_cate").val();
				var stk_price = $("#stk_price").val();
				var stk_count = $("#stk_count").val();
				var stk_total = $("#stk_total").val();
				var stk_store = $("#stk_store").val();
				var stk_remark = $("#stk_remark").val();
				action_url = "./index.php?s=/StockManage/doEditStock/stk_id/" + stk_id;
				if (stk_prodname != "") action_url += "/stk_prodname/" + stk_prodname;
				if (stk_cate != "") action_url += "/stk_cate/" + stk_cate;
				if (stk_price != "") action_url += "/stk_price/" + stk_price;
				if (stk_count != "") action_url += "/stk_count/" + stk_count;
				if (stk_total != "") action_url += "/stk_total/" + stk_total;
				if (stk_store != "") action_url += "/stk_store/" + stk_store;
				if (stk_remark != "") action_url += "/stk_remark/" + stk_remark;
				$("#dialogStock").remove();
				$(this).dialog("close");
				$.get(action_url, function(data) {
					if (data == 1) {
						location.reload()
					}
				})
			},
			'取消': function() {
				$(this).dialog("close")
			}
		}
	})
};

function del(stk_id) {
	if (confirm("确认删除吗？")) {
		window.location.href = "./index.php?s=/StockManage/delete/stk_id/" + stk_id
	}
};

function mul(arg1, arg2) {
	var m = 0,
		s1 = arg1.toString(),
		s2 = arg2.toString();
	try {
		m += s1.split(".")[1].length
	} catch (e) {}
	try {
		m += s2.split(".")[1].length
	} catch (e) {}
	return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m)
};
$('#stk_price').blur(function() {
	$('#stk_total').val(mul($('#stk_price').val(), $('#stk_count').val()))
});
$('#stk_count').blur(function() {
	$('#stk_total').val(mul($('#stk_price').val(), $('#stk_count').val()))
});
$('#btnExport').click(function() {
	window.location.href = './index.php?s=/StockManage/export'
});

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
				// var osm_sn = content.find("input[name='osm_sn']").val();
				// var osm_customer_name = content.find("input[name='osm_customer_name']").val();
				// var osm_operator_name = content.find("input[name='osm_operator_name']").val();
				// var oss_total_start = content.find("input[name='oss_total_start']").val();
				// var oss_total_end = content.find("input[name='oss_total_end']").val();
				var oss_store_name = content.find("input[name='oss_store_name']").val();
				// var osm_writer_name = content.find("input[name='osm_writer_name']").val();
				// var osm_date_start = content.find("input[name='osm_date_start']").val();
				// var osm_date_end = content.find("input[name='osm_date_end']").val();
				var oss_prodname = content.find("input[name='oss_prodname']").val();
				action_url = "./index.php?s=/StockManage/stock";
				// if (osm_sn != "") action_url += "/osm_sn/" + osm_sn;
				// if (osm_customer_name != "请输入关键字或空格") action_url += "/osm_customer_name/" + osm_customer_name;
				// if (osm_operator_name != "请输入关键字或空格") action_url += "/osm_operator_name/" + osm_operator_name;
				// if (oss_total_start != "") action_url += "/oss_total_start/" + oss_total_start;
				// if (oss_total_end != "") action_url += "/oss_total_end/" + oss_total_end;
				if (oss_store_name != "请输入关键字或空格") action_url += "/oss_store_name/" + oss_store_name;
				// if (osm_writer_name != "请输入关键字或空格") action_url += "/osm_writer_name/" + osm_writer_name;
				// if (osm_date_start != "") action_url += "/osm_date_start/" + osm_date_start;
				// if (osm_date_end != "") action_url += "/osm_date_end/" + osm_date_end;
				if (oss_prodname != "请输入关键字或空格") action_url += "/oss_prodname/" + oss_prodname;
				window.location.href = action_url
			},
			'取消': function() {
				$(this).dialog("close")
			}
		}
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
// 	$("#osm_customer_name").autocomplete({
// 		source: './index.php?s=/Outstore/getCustomer',
// 		minLength: 1,
// 		delay: 0,
// 		select: function(event, ui) {
// 			$("#osm_customer_name").val(ui.item.value)
// 		}
// 	});
// 	$("#osm_operator_name").autocomplete({
// 		source: './index.php?s=/StockManage/getOperator',
// 		minLength: 1,
// 		delay: 0,
// 		select: function(event, ui) {
// 			$("#osm_operator_name").val(ui.item.value)
// 		}
// 	});
// 	$("#osm_writer_name").autocomplete({
// 		source: './index.php?s=/Outstore/getOperator',
// 		minLength: 1,
// 		delay: 0,
// 		select: function(event, ui) {
// 			$("#osm_writer_name").val(ui.item.value)
// 		}
// 	});
	$("#oss_store_name").autocomplete({
		source: './index.php?s=/Outstore/getStore',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$("#oss_store_name").val(ui.item.value)
		}
	})
});