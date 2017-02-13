$(".btn").button();

function del(cust_id) {
	if (confirm("确认删除吗？")) {
		$.get("./index.php?s=/BaseData/deleteCustomer/cust_id/" + cust_id, function(data) {
			if (data == 1) {
				location.reload()
			}
		})
	}
};
$("#jump").change(function() {
	var url = "";
	switch ($(this).val()) {
	case "0":
		url = "./index.php?s=/BaseData/cate";
		break;
	case "1":
		url = "./index.php?s=/BaseData/index";
		break;
	case "2":
		url = "./index.php?s=/BaseData/store";
		break;
	case "3":
		url = "./index.php?s=/BaseData/customer";
		break
	};
	window.location.href = url
});

function toAddCustomer() {
	$("#dialogCustomer").dialog({
		height: 400,
		width: 650,
		title: '客户添加',
		modal: true,
		buttons: {
			"保存": function() {
				var cust_name = $('#cust_name').val();
				var cust_dept = $('#cust_dept').val();
				var cust_comfullname = $('#cust_comfullname').val();
				var cust_address = $('#cust_address').val();
				var cust_phone = $('#cust_phone').val();
				action_url = "./index.php?s=/BaseData/doAddCustomer";
				if (cust_name != "") action_url += "/cust_name/" + cust_name;
				if (cust_dept != "") action_url += "/cust_dept/" + cust_dept;
				if (cust_comfullname != "") action_url += "/cust_comfullname/" + cust_comfullname;
				if (cust_address != "") action_url += "/cust_address/" + cust_address;
				if (cust_phone != "") action_url += "/cust_phone/" + cust_phone;
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
		},
		open: function() {
			$("#cust_name").val('');
			$("#cust_dept").val('');
			$("#cust_comfullname").val('');
			$("#cust_address").val('');
			$("#cust_phone").val('')
		},
		close: function() {}
	})
};

function toEditCustomer(cust_id) {
	$("#dialogCustomer").dialog({
		height: 400,
		width: 650,
		title: '产品编辑',
		modal: true,
		open: function() {
			$.getJSON("./index.php?s=/BaseData/getCustomerById/cust_id/" + cust_id, function(data) {
				$('#cust_name').val(data.cust_name);
				$('#cust_dept').val(data.cust_dept);
				$('#cust_comfullname').val(data.cust_comfullname);
				$('#cust_phone').val(data.cust_phone);
				$('#cust_address').val(data.cust_address)
			})
		},
		close: function() {},
		buttons: {
			"保存": function() {
				var cust_name = $('#cust_name').val();
				var cust_dept = $('#cust_dept').val();
				var cust_comfullname = $('#cust_comfullname').val();
				var cust_address = $('#cust_address').val();
				var cust_phone = $('#cust_phone').val();
				action_url = "./index.php?s=/BaseData/doEditCustomer/cust_id/" + cust_id;
				if (cust_name != "") action_url += "/cust_name/" + cust_name;
				if (cust_dept != "") action_url += "/cust_dept/" + cust_dept;
				if (cust_comfullname != "") action_url += "/cust_comfullname/" + cust_comfullname;
				if (cust_address != "") action_url += "/cust_address/" + cust_address;
				if (cust_phone != "") action_url += "/cust_phone/" + cust_phone;
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
}