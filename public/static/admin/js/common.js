/** 通用form表单提交数据的方法 **/
function capp_save(form) {
    var data = $(form).serialize();

    // console.log(data);
    url = $(form).attr('url');
    // 执行ajax
    $.post(url, data, function(result) {
        if(result.code === 0) {
            layer.msg(result.msg, {icon:5, time:2000});
        } else if(result.code === 1) {
            self.location = result.data.jump_url;
        }
    }, 'json')
}

// 时间插件适配方法
function selecttime(flag){
    if(flag==1){
        var endTime = $("#countTimeend").val();
        if(endTime != ""){
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:endTime})}else{
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
    }else{
        var startTime = $("#countTimestart").val();
        if(startTime != ""){
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',minDate:startTime})}else{
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
    }
}

/**
 * 通用化删除操作
 * @param obj
 */
function app_del(obj){
    // 获取模板当中的url地址
    url = $(obj).attr('del_url');

    layer.confirm('确认要删除吗？',function(index){
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function(data){
                if(data.code === 1) {
                    // 执行跳转
                    self.location=data.data.jump_url;
                }else if(data.code === 0) {
                    layer.msg(data.msg, {icon:2, time:2000});
                }
            },
            error:function(data) {
                console.log(data.msg);
            }
        });
    });
}


/**通用修改状态**/
function capp_status(obj) {
    // 获取url地址
    url = $(obj).attr('status_url');

    layer.confirm('确认要修改状态吗?', function(index) {
       $.ajax({
           type:'POST',
           url: url,
           dateType:'json',
           success: function(result) {
               if(result.code === 1) {
                   self.location = result.data.jump_url;
               } else if(result.code === 0) {
                   layer.msg(data.msg, {icon:2, time:2000});
               }
           },
           error:function (data) {
               console.log(data.log);
           }
       })
    });
}