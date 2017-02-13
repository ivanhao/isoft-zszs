$(".btn").button();

function del(pdca_id) {
	if (confirm("确认删除吗？")) {
		$.get("./index.php?s=/BaseData/deleteCate/pdca_id/" + pdca_id, function(data) {
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

function toAddCate() {
	$("#dialogCate").dialog({
		height: 400,
		width: 650,
		title: '产品类别添加',
		modal: true,
		buttons: {
			"保存": function() {
				var pdca_name = $("#pdca_name").val();
				action_url = "./index.php?s=/BaseData/doAddCate";
				if (pdca_name != "") action_url += "/pdca_name/" + pdca_name;
				$.get(action_url, function(data) {
					if (data == 1) {
						location.reload()
					}
				});
				$(this).dialog("close")
			},
			'取消': function() {
				$(this).dialog("close")
			}
		},
		open: function() {
			$('#pdca_name').val('')
		},
		close: function() {}
	})
};

function toEditCate(pdca_id) {
	$("#dialogCate").dialog({
		height: 400,
		width: 650,
		title: '产品类别编辑',
		modal: true,
		open: function() {
			$.getJSON("./index.php?s=/BaseData/getCateById/pdca_id/" + pdca_id, function(data) {
				$('#pdca_name').val(data.pdca_name)
			})
		},
		buttons: {
			"保存": function() {
				var pdca_name = $('#pdca_name').val();
				action_url = "./index.php?s=/BaseData/doEditCate/pdca_id/" + pdca_id;
				if (pdca_name != "") action_url += "/pdca_name/" + pdca_name;
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
		close: function() {}
	})
}