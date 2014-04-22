<script type="text/javascript">
	$(function(){
		$(".filter a").click(function(){
			var $this = $(this);
			$(".filter a").removeClass("selected");
			$this.addClass("selected");
			var target = $this.attr("data-filter-value");
			if(target){
				$("#container a").addClass("hideme");
				$("#container a"+target).removeClass("hideme");
			} else {
				$("#container a").removeClass("hideme");
			}

			return false;
		});
	});

</script>
<style type="text/css">
	#container .hideme {
		display: none;
	}
</style>