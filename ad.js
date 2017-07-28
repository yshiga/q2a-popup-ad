<script>
$(document).ready(function () {
	var popup = new $.Popup({
		width : ^box_width,
		height: ^box_height
	});
        popupHtml = '^html';
        
	percentage = ^percentage;
        documentHeight = $(document).height();
        scrollHeight = documentHeight * (percentage / 100)

	popupflg = false;
	if (percentage <= 0) {
		popup.open(popupHtml, 'html');
		popupflg = true;
	}
	$(^window).scroll(function (e) {
		var window = $(e.currentTarget);
		var scrollTop = window.scrollTop();

		if ( !popupflg && scrollHeight <= scrollTop) {
			popup.open(popupHtml, 'html');
			window['optimizely'] = window['optimizely'] || [];
			window.optimizely.push(["trackEvent", "popup_show_" + percentage]); 
			popupflg = true;
		}
	});
});
</script>
