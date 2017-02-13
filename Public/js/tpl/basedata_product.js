$(".btn").button();

function del(id) {
	if (confirm("确认删除吗？")) {
		$.get("./index.php?s=/BaseData/deleteProduct/prod_id/" + id, function(data) {
			if (data == 1) {
				location.reload()
			}
		})
	}
};
$('#prod_cate').click(function() {
	if ($(this).val() == '-1') {
		window.location.href="./index.php?s=/BaseData/cate";
	}
});
$('#prod_store').click(function() {
	if ($(this).val() == '-1') {
		window.location.href ="./index.php?s=/BaseData/store";
	}
});
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

function toAddProduct() {
	$("#dialogProduct").dialog({
		height: 400,
		width: 650,
		title: '产品添加',
		modal: true,
		buttons: {
			"保存": function() {
				var prod_name = $("#prod_name").val();
				var prod_price = $("#prod_price").val();
				var prod_cate = $("#prod_cate").val();
				var prod_store = $("#prod_store").val();
				action_url = "./index.php?s=/BaseData/doAddProduct";
				if (prod_cate != "") action_url += "/prod_cate/" + prod_cate;
				if (prod_name != "") action_url += "/prod_name/" + prod_name;
				if (prod_price != "") action_url += "/prod_price/" + prod_price;
				if (prod_store != "") action_url += "/prod_store/" + prod_store;
				$(this).dialog('close');
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
			$('#prod_cate').val(0);
			$('#prod_name').val('');
			$('#prod_price').val('');
			$('#prod_count').val('');
			$('#prod_store').val('');
		},
		close: function() {}
	})
};

function toEditProduct(prod_id) {
	$("#dialogProduct").dialog({
		height: 400,
		width: 650,
		title: '产品编辑',
		modal: true,
		open: function() {
			$.getJSON("./index.php?s=/BaseData/getProdById/prod_id/" + prod_id, function(data) {
				$('#prod_cate').val(data.prod_cate);
				$('#prod_name').val(data.prod_name);
				$('#prod_price').val(data.prod_price);
				$('#prod_count').val(data.prod_count);
				$('#prod_store').val(data.prod_store);
			})
		},
		close: function() {},
		buttons: {
			"保存": function() {
				var prod_name = $("#prod_name").val();
				var prod_price = $("#prod_price").val();
				var prod_cate = $("#prod_cate").val();
				var prod_store = $("#prod_store").val();
				action_url = "./index.php?s=/BaseData/doEditProduct/prod_id/" + prod_id;
				if (prod_name != "") action_url += "/prod_name/" + prod_name;
				if (prod_cate != "") action_url += "/prod_cate/" + prod_cate;
				if (prod_price != "") action_url += "/prod_price/" + prod_price;
				if (prod_store != "") action_url += "/prod_store/" + prod_store;
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