$(".btn").button();
$("#fastSearch").click(function() {
	window.location.href = "./index.php?s=/Instore/index/searchBy/" + $("#searchBy").val() + "/keyword/" + $("#keyword").val()
});
$("#btnAdd").click(function() {
	window.location.href = "./index.php?s=/Instore/add"
});

$('#btnExport').click(function() {
	window.location.href = './index.php?s=/Instore/export'
});

function del(ism_id) {
	if (confirm("确认删除吗？")) {
		window.location.href = "./index.php?s=/Instore/delete/ism_id/" + ism_id
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
				var ism_sn = content.find("input[name='ism_sn']").val();
				var ism_sellerunit = content.find("input[name='ism_sellerunit']").val();
				// var ism_operator_name = content.find("input[name='ism_operator_name']").val();
				var ism_supplier_name = content.find("input[name='ism_supplier_name']").val();
				var iss_store_name = content.find("input[name='iss_store_name']").val();
				var iss_total_start = content.find("input[name='iss_total_start']").val();
				var iss_total_end = content.find("input[name='iss_total_end']").val();
				var ism_writer_name = content.find("input[name='ism_writer_name']").val();
				var ism_date_start = content.find("input[name='ism_date_start']").val();
				var ism_date_end = content.find("input[name='ism_date_end']").val();
				var iss_prodname = content.find("input[name='iss_prodname']").val();
				action_url = "./index.php?s=/Instore/index";
				if (ism_sn != "") action_url += "/ism_sn/" + ism_sn;
				if (ism_sellerunit != "") action_url += "/ism_sellerunit/" + ism_sellerunit;
				// if (ism_operator_name != '请输入关键字或空格') action_url += "/ism_operator_name/" + ism_operator_name;
				if (ism_supplier_name != '请输入关键字或空格') action_url += "/ism_supplier_name/" + ism_supplier_name;
				if (iss_store_name != '请输入关键字或空格') action_url += "/iss_store_name/" + iss_store_name;
				if (iss_total_start != "") action_url += "/iss_total_start/" + iss_total_start;
				if (iss_total_end != "") action_url += "/iss_total_end/" + iss_total_end;
				if (ism_writer_name != '请输入关键字或空格') action_url += "/ism_writer_name/" + ism_writer_name;
				if (ism_date_start != "") action_url += "/ism_date_start/" + ism_date_start;
				if (ism_date_end != "") action_url += "/ism_date_end/" + ism_date_end;
				if (iss_prodname != '请输入关键字或空格') action_url += "/iss_prodname/" + iss_prodname;
				window.location.href = action_url
			},
			'取消': function() {
				$(this).dialog("close")
			}
		}
	})
};
$("#ism_date_start").datepicker({
	changeYear: true
});
$("#ism_date_end").datepicker({
	changeYear: true
});
$.widget("custom.catcomplete", $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this,
			currentCategory = "";
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
				currentCategory = item.category
			};
			self._renderItem(ul, item)
		})
	}
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
$(function() {
	$("#iss_prodname").catcomplete({
		source: './index.php?s=/Instore/getProduct',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$('#iss_prodname').val(ui.item.prod_name)
		}
	});
	$("#ism_supplier_name").autocomplete({
		source: './index.php?s=/Instore/getSupplier',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$("#ism_supplier").val(ui.item.id)
		}
	});
	$("#ism_operator_name").autocomplete({
		source: './index.php?s=/Instore/getOperator',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$("#ism_operator").val(ui.item.id)
		}
	});
	$("#ism_writer_name").autocomplete({
		source: './index.php?s=/Instore/getOperator',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$("#ism_writer").val(ui.item.id)
		}
	});
	$("#iss_store_name").autocomplete({
		source: './index.php?s=/Instore/getStore',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			$("#ism_writer").val(ui.item.id)
		}
	})
});