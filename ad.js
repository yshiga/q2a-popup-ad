<script>
$(document).ready(function () {
	var popup = new $.Popup({
		width : ^box_width,
		height: ^box_height
	});
	percentage = ^percentage;
	popupflg = false;
	if (percentage <= 0) {
		popup.open('^html', 'html');
		popupflg = true;
	}
	$(^window).scroll(function (e) {
		var window = $(e.currentTarget),
		scrollTop = window.scrollTop(),
		documentHeight = $(document).height();
		scrollHeight = documentHeight * (percentage / 100)

		if ( !popupflg && scrollHeight <= scrollTop) {
			popup.open('^html', 'html');
			popupflg = true;
		}
	});
});
</script>
