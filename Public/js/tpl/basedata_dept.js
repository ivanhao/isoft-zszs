$(".btn").button();

function del(id) {
	if (confirm("确认删除吗？")) {
		$.get("./index.php?s=/BaseData/deleteDept/id/" + id, function(data) {
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

function toAddDept() {
	$("#dialogDept").dialog({
		height: 400,
		width: 650,
		title: '部门添加',
		modal: true,
		buttons: {
			"保存": function() {
				var name = $("#name").val();
				var pid = $("#pid").val();
				action_url = "./index.php?s=/BaseData/doAddDept";
				if (name != "") action_url += "/name/" + name;
				if (pid != "") action_url += "/pid/" + pid;
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
			$('#name').val('')
			$('#pid').val('')
		},
		close: function() {}
	})
};

function toEditDept(id) {
    $("#name").val();
	$("#dialogDept").dialog({
		height: 400,
		width: 650,
		title: '部门编辑',
		modal: true,
		open: function() {
			$.getJSON("./index.php?s=/BaseData/getDeptById/id/" + id, function(data) {
				$('#name').val(data.name)
				$('#pid').val(data.pid)
			})
		},
		buttons: {
			"保存": function() {
				var name = $("#name").val();
				var pid = $("#pid").val();
				action_url = "./index.php?s=/BaseData/doEditDept/id/" + id;
				if (name != "") action_url += "/name/" + name;
				if (pid != "") action_url += "/pid/" + pid;
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