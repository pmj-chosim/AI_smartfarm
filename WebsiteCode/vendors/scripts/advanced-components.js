// Switchery
		var elems = Array.prototype.slice.call(document.querySelectorAll('.switch-btn'));
		$('.switch-btn').each(function() {
			new Switchery($(this)[0], $(this).data());
		});

		// Bootstrap Touchspin
		$("input[name='demo_vertical2']").TouchSpin({
			verticalbuttons: true,
			// verticalupclass: 'fa fa-plus',
			// verticaldownclass: 'fa fa-minus'
		});
		$("input[name='demo3']").TouchSpin();
		$("input[name='demo1']").TouchSpin({
			min: 0,
			max: 300,
			stepinterval: 1,
			maxboostedstep: 10,
			postfix: 'Hari'
		});
		$("input[name='luas']").TouchSpin({
			min: 0,
			max: 300,
			stepinterval: 1,
			maxboostedstep: 10,
			postfix: 'Hektare'
		});
		//  function loadd(){
			$("input[name='demo2[]']").TouchSpin({
				min: 0,
				max: 300,
				stepinterval: 1,
				maxboostedstep: 10000000,
				prefix: 'Hari ke-'
			});
			$("input[name='hstpupuk[]']").TouchSpin({
				min: 0,
				max: 300,
				stepinterval: 1,
				maxboostedstep: 10000000,
				prefix: 'Hari ke-'
			});
			$("input[name='hstpestisida[]']").TouchSpin({
				min: 0,
				max: 300,
				stepinterval: 1,
				maxboostedstep: 10000000,
				prefix: 'Hari ke-'
			});
		//  };
		// loadd();
		$("input[name='demo3_22']").TouchSpin({
			initval: 40
		});
		$("input[name='demo5']").TouchSpin({
			prefix: "pre",
			postfix: "post"
		});