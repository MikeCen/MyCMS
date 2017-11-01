/**
 * 静态页面计数器JS文件  
 *需配合Home/Index下的getCount方法
 */
var newsIds = {};
$(".news_count").each(function(i){
	newsIds[i] = $(this).attr("news-id");
});

url = "/index.php?m=home&c=index&a=getCount";
$.post(url,newsIds,function(result){
	if (result.status == 1 ) {
		counts = result.data;
		$.each(counts,function(news_id,count){
			$(".node-"+news_id).html(count);
		});
	}
},"JSON");