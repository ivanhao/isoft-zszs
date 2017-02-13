var id_num = 0;
$(".btn").button();

function getRow(id_num) {
	var row = "<tr class='tr_row' align='center' id='row_" + id_num + "'>" + "<td align='center'><input type='text' name='oss_prodname[]' id='oss_prodname_" + id_num + "' size='17' onfocus='clearTip(this)'  onblur='fillTip(this)' value='请输入关键字或空格' style='color:#CCC'/></td>" + "<td align='center'><input type='text' id='oss_cate_name_" + id_num + "' size='10' disabled/></td>" + "<td align='center'><input name='oss_price[]' type='text' id='oss_price_" + id_num + "' size='10' onblur='compute(" + id_num + ")'/></td>" + "<td align='center'><input name='oss_count[]' type='text' id='oss_count_" + id_num + "' size='10' onblur='compute(" + id_num + ")'/></td>" + "<td align='center'><input name='oss_total[]' type='text' id='oss_total_" + id_num + "' size='10'/></td>" + "<td align='center'>" + "<select name='oss_store[]' id='oss_store_" + id_num + "' >" + "<option value=''>--请选择--</option>" + "</select>" + "</td>" + "<td align='center'><input name='oss_remark[]' type='text' id='oss_remark_" + id_num + "' size='25' /></td>" + "<td align='center'><a href='#' name='linkDelete' onclick='deleteRow(" + id_num + ")'>删除</a></td>" + "<input name='oss_stockid[]' type='hidden' id='oss_stockid_" + id_num + "' size='17'/>" + "<input name='row_count[]' type='hidden' value='" + id_num + "'>" + "<input name='oss_cate[]' id='oss_cate_" + id_num + "' type='hidden'>" + "<input name='oss_prod[]' id='oss_prod_" + id_num + "' type='hidden'>" + "</tr>";
	return row
};
$("#btnAdd").click(function() {
	var row = getRow(id_num);
	$("#table").append(row);
	bindAutoComplete(id_num);
	bindStore(id_num);
	id_num++
});
$('#btn_submit').click(function() {
	if ($('.tr_row').size() == 0) {
		alert('请先添加明细');
		return
	};
	$('#form1').submit()
});

function bindStore(id) {
	var isdefault;
	$.getJSON('./index.php?s=/Outstore/getStore', function(data) {
		$.each(data, function(index, ele) {
			isdefault = ele.sto_isdefault == 1 ? "selected=true" : '';
			$('#oss_store_' + id).append("<option value='" + ele.id + "' " + isdefault + ">" + ele.value + "</option>")
		})
	})
};

function deleteRow(rowid) {
	$("#row_" + rowid).remove()
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
$.widget("custom.catcomplete", $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this,
			currentCategory = "";
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append("<li class='ui-autocomplete-category' style='text-align:left'>" + item.category + "</li>");
				currentCategory = item.category
			};
			self._renderItem(ul, item)
		})
	},
	_renderItem: function(ul, item) {
		return $("<li style='text-align:left'></li>").data("item.autocomplete", item).append($("<a></a>").text(item.label)).appendTo(ul)
	}
});
$.widget('custom.autocomplete_operator', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this;
		ul.append("<li style='font-weight:bold;font-style:italic'><a>添加经办人</a></li>");
		$.each(items, function(index, item) {
			self._renderItem(ul, item)
		})
	}
});
$.widget('custom.autocomplete_customer', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this;
		ul.append("<li style='font-weight:bold;font-style:italic'><a>添加客户</a></li>");
		$.each(items, function(index, item) {
			self._renderItem(ul, item)
		})
	}
});
$.widget('custom.autocomplete_writer', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this;
		ul.append("<li style='font-weight:bold;font-style:italic'><a>添加制单人</a></li>");
		$.each(items, function(index, item) {
			self._renderItem(ul, item)
		})
	}
});

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

function compute(id_num) {
	var arg1 = $("#oss_price_" + id_num).val();
	var arg2 = $("#oss_count_" + id_num).val();
	$("#oss_total_" + id_num).val(mul(arg1, arg2))
};

function bindAutoComplete(id_num) {
	$("#oss_prodname_" + id_num).catcomplete({
		source: './index.php?s=/Outstore/getStock',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			if (ui.item.stk_prod == undefined) {
				alert('没有库存');
				return
			};
			$("#oss_prodname_" + id_num).attr('style', 'color:#000');
			$("#oss_price_" + id_num).val(ui.item.stk_price);
			$("#oss_prod_" + id_num).val(ui.item.stk_prod);
			$("#oss_cate_" + id_num).val(ui.item.stk_cate);
			$("#oss_cate_name_" + id_num).val(ui.item.pdca_name);
			$("#oss_stockid_" + id_num).val(ui.item.stk_id);
			$("#oss_store_" + id_num).val(ui.item.stk_store);
			$("#oss_count_" + id_num).val(1);
			$("#oss_total_" + id_num).val(mul(ui.item.stk_price, 1));
			$("#oss_remark_" + id_num).val(ui.item.stk_remark)
		}
	})
};
$(function() {
	$("#osm_operator_name").autocomplete_operator({
		source: './index.php?s=/Outstore/getOperator',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			if (typeof(ui.item) == 'undefined') {
				window.open('./index.php?s=/BaseData/employee');
				return false
			};
			$("#osm_operator").val(ui.item.id)
		}
	});
	$("#osm_customer_name").autocomplete_customer({
		source: './index.php?s=/Outstore/getCustomer',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			if (typeof(ui.item) == 'undefined') {
				window.open('./index.php?s=/BaseData/customer');
				return false
			};
			$("#osm_customer").val(ui.item.id)
			$("#osm_dept").val(ui.item.cust_dept)
		}
	});
	$("#osm_writer_name").autocomplete_writer({
		source: './index.php?s=/Outstore/getOperator',
		minLength: 1,
		delay: 0,
		select: function(event, ui) {
			if (typeof(ui.item) == 'undefined') {
				window.open('./index.php?s=/BaseData/employee');
				return false
			};
			$("#osm_writer").val(ui.item.id)
		}
	});
	switch (action) {
	case 'add':
		url = url + '/doAdd';
		break;
	case 'update':
		url = url + '/doEdit';
		$("input[name='oss_prodname[]']").each(function(index, ele) {
			bindAutoComplete(index)
		})
	};
	$('#form1').attr('action', url)
});