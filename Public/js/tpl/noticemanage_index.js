$(".btn").button();
$("#fastSearch").click(function() {
	window.location.href = "./index.php?s=/NoticeManage/index/keyword/" + $("#keyword").val()
});

function del(id) {
	if (confirm("确认删除吗？")) {
		window.location.href = "./index.php?s=/NoticeManage/delete/ntc_id/" + id
	}
};

function add() {
	$("#dialog").dialog({
		height: 400,
		width: 650,
		title: "通知添加",
		modal: true,
		buttons: {
			"保存": function() {
				var ntc_title = $("#ntc_title").val();
				var ntc_content = $("#ntc_content").val();
				var ntc_author = $("#ntc_author").val();
				var url = "./index.php?s=/NoticeManage/doAdd";
				if (ntc_title != "") url += "/ntc_title/" + ntc_title;
				if (ntc_content != "") url += "/ntc_content/" + ntc_content;
				if (ntc_author != "") url += "/ntc_author/" + ntc_author;
				window.location.href = url
			},
			"关闭": function() {
				$(this).dialog("close")
			}
		},
		open: function() {
			$("#ntc_title").html('');
			$("#ntc_content").html('');
			$("#ntc_author").val(ntc_author)
		}
	})
};

function view(id) {
	$("#dialog").dialog({
		height: 400,
		width: 650,
		title: "通知查看",
		modal: true,
		buttons: {
			"关闭": function() {
				$(this).dialog("close")
			}
		},
		open: function() {
			$.getJSON('./index.php?s=/NoticeManage/view/ntc_id/' + id, function(data) {
				$("#ntc_title").html(data.ntc_title);
				$("#ntc_content").html(data.ntc_content);
				$("#ntc_author").val(data.ntc_author)
			})
		}
	})
};

function toEdit(id) {
	$("#dialog").dialog({
		height: 400,
		width: 650,
		title: "通知编辑",
		modal: true,
		buttons: {
			"保存": function() {
				var ntc_id = id;
				var ntc_title = $("#ntc_title").val();
				var ntc_content = $("#ntc_content").val();
				var ntc_author = $("#ntc_author").val();
				var url = "./index.php?s=/NoticeManage/doEdit";
				if (ntc_id != "") url += "/ntc_id/" + ntc_id;
				if (ntc_title != "") url += "/ntc_title/" + ntc_title;
				if (ntc_content != "") url += "/ntc_content/" + ntc_content;
				if (ntc_author != "") url += "/ntc_author/" + ntc_author;
				window.location.href = url
			},
			"关闭": function() {
				$(this).dialog("close")
			}
		},
		open: function() {
			$.getJSON('./index.php?s=/NoticeManage/view/ntc_id/' + id, function(data) {
				$("#ntc_title").html(data.ntc_title);
				$("#ntc_content").html(data.ntc_content);
				$("#ntc_author").val(data.ntc_author)
			})
		}
	})
};

function clearTip(obj) {
	if ($(obj).val() == '输入标题、内容、作者') {
		$(obj).attr('style', 'color:#000');
		$(obj).val('')
	}
};


function fillTip(obj) {
	if ($(obj).val() == '') {
		$(obj).attr('style', 'color:#CCC');
		$(obj).val('输入标题、内容、作者')
	}
};

