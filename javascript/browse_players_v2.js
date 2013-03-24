<script>
	$(function(){
		var $container = $("#container"),filters = {};
		$container.isotope({
			itemSelector:".element",
			masonry:{columnWidth:160}
		});
		$(".filter a").click(function(){
			var $this = $(this);
			if($this.hasClass("selected")){return;}
			var $optionSet = $this.parents(".option-set");	
			$optionSet.find(".selected").removeClass("selected");
			$this.addClass("selected");
			var group = $optionSet.attr("data-filter-group");
			filters[group] = $this.attr("data-filter-value");
			var isoFilters = [];
			for ( var prop in filters ) {isoFilters.push( filters[ prop ] )}
			var selector = isoFilters.join("");
			$container.isotope({ filter: selector });
			return false;
		});
	});
</script>