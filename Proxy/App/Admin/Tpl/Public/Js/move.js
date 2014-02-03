$(function (){
	$("select[name=cate-one]").change(function(){
		//取出当前对象
		var obj = $(this);
		if(obj.index()<2){
			var entitle = obj.val();
			//异步发送
			$.post(WEB +　"/Index/Upload/get_cate", {entitle : entitle} , function(data){
				if(data){
					var option = "";
					$.each(data, function(i, k){
						option += '<option value="' + k.entitle + '">' + k.cntitle + "</option>";
				});
					obj.next().html(option).show();
				} else {
					obj.next().html("").hide();
				}
			}, "json" );
		}
	});
	$( ".move" ).click( function () {
		var vid = $(this).parents("tr").children("td:nth-child(1)").html();
		$("input[name=vid]").val(vid);
		dialog($( "#category" ));
	} );
});
function dialog (obj) {
	obj.css( {
		left : ( $( window ).width() - obj.width() ) / 2,
		top : $( document ).scrollTop() + ( $( window ).height() - obj.height() ) / 2
	} ).fadeIn();
	$( '#background' ).css( {
		opacity : 0.3,
    	filter : 'Alpha(Opacity = 30)',
		'height' : $( document ).height()
	} ).show();
}