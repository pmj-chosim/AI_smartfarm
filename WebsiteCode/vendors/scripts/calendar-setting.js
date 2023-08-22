
// include('koneksi.php');

jQuery(document).ready(function () {
	jQuery("#add-event").submit(function () {
		alert("Submitted");
		var values = {};
		$.each($("#add-event").serializeArray(), function (i, field) {
			values[field.name] = field.value;
		});
		console.log(values);
	});
});

(function () {
	"use strict";
	jQuery(function () {
		// page is ready
		jQuery("#calendar").fullCalendar({
			themeSystem: "bootstrap4",
			// emphasizes business hours
			businessHours: false,
			defaultView: "month",
			// event dragging & resizing
			selectable: true,
			selecHelper: true,
			editable: true,
			// header
			header: {
				left: "title",
				center: "month,agendaWeek,agendaDay",
				right: "today prev,next",
			},
			 events: "../../../api/tampilJadwal.php",
			 
			// [
			// 	{
			// 		title: "Go Space :)",
			// 		description:
			// 			"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu pellentesque nibh. In nisl nulla, convallis ac nulla eget, pellentesque pellentesque magna.",
			// 		start: "2022-12-27",
			// 		end: "2022-12-27",
			// 		className: "fc-bg-default",
			// 		icon: "rocket",
			// 	},
			// 	{
			// 		title: "Dentist",
			// 		description:
			// 			"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu pellentesque nibh. In nisl nulla, convallis ac nulla eget, pellentesque pellentesque magna.",
			// 		start: "2022-12-29T11:30:00",
			// 		end: "2022-12-29T012:30:00",
			// 		className: "fc-bg-blue",
			// 		icon: "medkit",
			// 		allDay: false,
			// 	},
			// ],
			dayClick: function () {
				jQuery("#modal-view-event-add").modal();
			},
			eventClick: function (event, jsEvent, view) {
				jQuery(".event-icon").html("<i class='fa fa-" + event.icon + "'></i>");
				jQuery(".event-title").html(event.title);
				jQuery(".event-body").html(event.description);
				jQuery(".eventUrl").attr("href", event.url);
				jQuery("#modal-view-event").modal();
			},
		});
	});
})(jQuery);
