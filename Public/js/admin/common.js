/**
 * 添加按钮操作
 */
$("#button-add").click(function(){
	var url =  SCOPE.add_url;
	window.location.href = url;
});

/**
 * 提交form表单操作
 */
  $("#mycms-button-submit").click(function(){
 	var data = $("#mycms-form").serializeArray();
 	var postData = {};
 	$(data).each(function(){
 		postData[this.name] = this.value;
 	});
 	var url = SCOPE.save_url;
 	$.post(url,postData,function(result){
 		if (result.status == 1) {
 			return dialog.success(result.message,SCOPE.jump_url);
 		}else if(result.status === 0){
 			return dialog.error(result.message);
 		}
 	},"JSON");
 });

 /**
  * 编辑模式
  */
 $(".mycms-table #mycms-edit").click(function(){
 	var id = $(this).attr('attr-id');
 	var url = SCOPE.edit_url+'&id='+id;
 	window.location.href = url;
 });

/**
 * 弹出删除确认操作
 * @author MikeCen <mikecen9@gmail.com>
 */
 $(".mycms-table #mycms-delete").click(function(){
 	var id = $(this).attr('attr-id');
 	var message= $(this).attr("attr-message");
 	var url = SCOPE.set_status_url;
 	var data = {};
 	data['id'] = id;
 	data['status'] = -1;
 	layer.open({
 		type:0,
 		title:'警告',
 		btn:['是','否'],
 		icon:3,
 		closeBtn:2,
 		content:'是否确认'+message,
 		scrollbar:true,
 		yes:function(){
 			toDetele(url,data);
 		},
 	});
 });
 
/**
 * 执行删除操作
 * @author MikeCen <mikecen9@gmail.com>
 */
 function toDetele(url,data){
 	$.post(url,data,function(result){
 		if (result.status == 1) {
 			return dialog.success(result.message,'');
 		}else{
 			return dialog.error(result.message);
 		}
 	},'JSON');
 }

/**
 * 列表排序
 * @author MikeCen <mikecen9@gmail.com>
 */
 $("#button-listorder").click(function(){
 	var data = $("#mycms-listorder").serializeArray();
 	var postData = {};
 	$(data).each(function(){
 		postData[this.name] = this.value;
 	});
 	var url = SCOPE.listorder_url;
 	$.post(url,postData,function(result){
 		if (result.status == 1) {
 			return dialog.success(result.message,result['data']['jump_url']);
 		}else if(result.status === 0){
 			return dialog.error(result.message,result['data']['jump_url']);
 		}
 	},"JSON");
 });

/**
 * 修改状态
 */
  $(".mycms-table #mycms-on-off").click(function(){
 	var id = $(this).attr('attr-id');
 	var status= $(this).attr("attr-status");
 	var url = SCOPE.set_status_url;
 	var data = {};
 	data['id'] = id;
 	data['status'] = status;
 	layer.open({
 		type:0,
 		title:'警告',
 		btn:['是','否'],
 		icon:3,
 		closeBtn:2,
 		content:'是否确认修改状态？',
 		scrollbar:true,
 		yes:function(){
 			toDetele(url,data);
 		},
 	});
 });

  /**
   * 推送按钮JS代码
   */
  $("#mycms-push").click(function(){
 	var id = $("#select-push").val();
 	if (id === 0) {
 		return dialog.error('请选择推荐位！');
 	}
 	push = {};
 	postData = {};
 	$("input[name='pushcheck']:checked").each(function(i){
 		push[i] = $(this).val();
 	});
 	postData['push'] = push;
 	postData['position_id'] = id;
 	// console.log(postData);return;
 	url = SCOPE.push_url;
 	$.post(url,postData,function(result){
 		if (result.status == 1) {
 			return dialog.success(result.message,result['data']['jump_url']);
 		}
 		if (result.status === 0) {
 			return dialog.error(result.message);
 		}
 	},"JSON");
  });