		$(document).ready(function(){

			$('.qtyPlus').click(function(e){
				e.preventDefault();
				
				productID = $(this).attr('id');
				
				var currentVal = parseInt($('#productID'+productID+'').val());
				
				if (!isNaN(currentVal)) {
					$('#productID'+productID+'').val(currentVal + 1);
				} else {
					$('#productID'+productID+'').val(0);
				}
			});

			$('.qtyMinus').click(function(e) {
				e.preventDefault();
				
				productID = $(this).attr('id');
				
				var currentVal = parseInt($('#productID'+productID+'').val());

				if (!isNaN(currentVal) && currentVal > 0) {
					$('#productID'+productID+'').val(currentVal - 1);
				} else {
					$('#productID'+productID+'').val(0);
				}
			});
			
			$('.qtyPlus2').click(function(e){
				e.preventDefault();
				
				cartID = $(this).attr('id');
				
				var realPrice = parseInt($('#realPrice'+cartID+'').text());
				var currentVal = parseInt($('#cartID'+cartID+'').val());
				
				if (!isNaN(currentVal)) {
					$('#cartID'+cartID+'').val(currentVal + 1);
					
					$.post(''+realLink+'ajaxUpdateProduct.php', {
						arrayID: cartID,
						newQuantity: currentVal + 1,
					}, function(response){});
					
					var sumPrice = realPrice * (currentVal + 1);
					
					$('#sumPrice'+cartID+'').text(sumPrice);
				} else {
					$('#cartID'+cartID+'').val(1);
					
					$.post(''+realLink+'ajaxUpdateProduct.php', {
						arrayID: cartID,
						newQuantity: 1,
					}, function(response){});
					
					var sumPrice = realPrice * 1;
					
					$('#sumPrice'+cartID+'').text(sumPrice);
				}
			});

			$('.qtyMinus2').click(function(e) {
				e.preventDefault();
				
				cartID = $(this).attr('id');
				
				var realPrice = parseInt($('#realPrice'+cartID+'').text());
				var currentVal = parseInt($('#cartID'+cartID+'').val());

				if (!isNaN(currentVal) && currentVal > 2) {
					$('#cartID'+cartID+'').val(currentVal - 1);
					
					$.post(''+realLink+'ajaxUpdateProduct.php', {
						arrayID: cartID,
						newQuantity: currentVal - 1,
					}, function(response){});
					
					var sumPrice = realPrice * (currentVal - 1);
					
					$('#sumPrice'+cartID+'').text(sumPrice);
				} else {
					$('#cartID'+cartID+'').val(1);
					
					$.post(''+realLink+'ajaxUpdateProduct.php', {
						arrayID: cartID,
						newQuantity: 1,
					}, function(response){});
					
					var sumPrice = realPrice * 1;
					
					$('#sumPrice'+cartID+'').text(sumPrice);
				}
			});

// ==========================================================================================

			filter_data();

			function filter_data() {

			    $('.addToCart').click(function(){

			    	var pColors = get_filter('pColor');

			        $.ajax({
		                url:""+realLink+"cart.php",
		                method:"POST",
		                data:{pColors:pColors},
			                success:function(data){
			                   //alert(pColors);
			                   console.log(this.data);
			                }
	            	});
			    });

			}

		    function get_filter(class_name) {
		        var filter = [];
		        $('.'+class_name+':checked').each(function(){
		            filter.push($(this).val());
		        });

		        if($('.'+class_name+'').is(':checked')){
					$('.colorsC').find('input:hidden').each(function() {
					   $("<input type='text' />").attr({ style: 'width: 45px;', value: this.value }).insertBefore(this);
					}).remove();				    
		        } else {
					$('.colorsC').find('input:text').each(function() {
					   $("<input type='hidden' />").attr({ style: 'width: 45px;', value: '0' }).insertBefore(this);
					}).remove();
				}

		        return filter;
		    }

		    $('.css-checkbox').click(function(){
        		filter_data();
   			});

// ==========================================================================================
			
			$('.addToCart').click(function(e) {
				e.preventDefault();
				
				productID = $(this).attr('id');
				productQuantity = $('#productID'+productID+'').val();
				
				$.post(''+realLink+'ajaxAddProduct.php', {
					pID: productID,
					pQua: productQuantity,
				}, function(response){
					$('#successID'+productID+'').show('slow');
				});
				alert('Успешно добавихте продукта в количката');
				setTimeout(function() {
					$('#successID'+productID+'').hide('slow');
					
				}, 1000);
				
				return false;
			});
		});