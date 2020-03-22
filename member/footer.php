


	<!-- javascript -->
	<script type="text/javascript" src="../assets/custom/js/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="../assets/custom/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../assets/custom/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="../assets/custom/js/bootstrap-table.js"></script>
	<script type="text/javascript" src="../assets/custom/js/lumino.glyphs.js"></script>
	<script type="text/javascript" src="../assets/datetimepicker/datetimepicker.js"></script>

	<!-- datatables -->
	<script src="../assets/datatables/js/jquery.dataTables.min.js"></script>

	<!-- select2 -->
	<script type="text/javascript" src="../assets/select/dist/js/select2.min.js"></script>

	<!-- toastr js -->
	<script type="text/javascript" src="../assets/toastr/js/toastr.min.js"></script>

	<!-- jquery ui -->
	<script type="text/javascript" src="../assets/mycustom/js/jquery-ui.min.js"></script>
	
	<!-- table display -->
	<script type="text/javascript" src="../assets/mycustom/js/table_display.js"></script>
	<script type="text/javascript" src="../assets/mycustom/js/custom.js"></script>
	<script type="text/javascript" src="../assets/mycustom/js/add.js"></script>
	<script type="text/javascript" src="../assets/mycustom/js/edit.js"></script>
	<script type="text/javascript" src="../assets/mycustom/js/login.js"></script>

	<!-- datepicker -->
	<script type="text/javascript" src="../assets/datepicker/dist/js/bootstrap-datepicker.js" ></script>

	<script>
		$.fn.datepicker.defaults.format = "mm/dd/yyyy";
		$('.datepicker').datepicker({
			startDate: new Date()
		});
		var a = '<?php echo basename($_SERVER['PHP_SELF']); ?>';
		// if(a === 'new.php'){
		// 	console.log('new');
		// }
		// !function ($) {
		// 	$(document).on("click","ul.nav li.parent > a > span.icon", function(){		  
		// 		$(this).find('em:first').toggleClass("glyphicon-minus");	  
		// 	}); 
		// 	$(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		// }(window.jQuery);

		// $(window).on('resize', function () {
		//   if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		// })
		// $(window).on('resize', function () {
		//   if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		// })
	</script>	
</body>
</html>