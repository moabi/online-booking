var $ = jQuery;
var bookingPage = '/reservation-service/';
var isBookingTpl = $('#booking-wrapper').length;
var reservation = {
	name	  : '',
	theme 	  : '',
	lieu      : '',
	sejour    : '',
	departure : '',
	arrival   : '',
	days      : '',
	participants : '',
	budgetPerMin    : '',
	budgetPerMax    : '', 
	globalBudgetMin : '',
	globalBudgetMax : '',
	currentBudget   : 0,
	currentDay   : '',
	tripObject   : {}
	
};


$.noty.defaults = {
    layout: 'bottom',
    theme: 'defaultTheme', // or 'relax'
    type: 'alert',
    text: '', // can be html or string
    dismissQueue: true, // If you want to use queue feature set this true
    template: '<div id="add_success" class="active"><span class="noty_text"></span><div class="noty_close"></div></div>',
    animation: {
        open: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceInLeft'
        close: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceOutLeft'
        easing: 'swing',
        speed: 500 // opening & closing animation speed
    },
    timeout: 1500, // delay for closing event. Set false for sticky notifications
    force: false, // adds notification to the beginning of queue when set to true
    modal: false,
    maxVisible: 2, // you can set max visible notification for dismissQueue true option,
    killer: false, // for close all notifications before show
    closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
    callback: {
        onShow: function() {},
        afterShow: function() {},
        onClose: function() {},
        afterClose: function() {},
        onCloseClick: function() {},
    },
    buttons: false // an array of buttons
};


function doAjaxRequest( theme , geo, type ){
     jQuery.ajax({
          url: '/wp-admin/admin-ajax.php',
          data:{
               'action':'do_ajax',
               'theme':theme,
               'geo' : geo,
               'type' : type,
               'count':1
               },
          dataType: 'JSON',
          success:function(data){    
		            jQuery('#activities-content').empty().append(jQuery('<div>', {
		                html : data
		            }));
		            //console.log(data);
                             },
          error: function(errorThrown){
               alert('error');
               console.log(errorThrown);
          }
     });
 
}

//BOOKING FN

//STORAGE
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
    console.log(reservation);
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function checkCookie() {
    //var trip = getCookie("reservation");
    var trip = Cookies.getJSON('reservation');
    
    if (trip) {
        loadTrip( trip );
        console.log('trip exist already');
        
    } else {
        console.log("Welcome new user ");
        initTrip();

    }
}

function read_cookie(cname) {
 var result = document.cookie.match(new RegExp(cname + '=([^;]+)'));
 result && (result = JSON.parse(result[1]));
 return result;
}

//ACTIONS

/*
	User Account functions
*/
function deleteUserTrip(tripID){
	$.ajax({
	          url: '/wp-admin/admin-ajax.php',
	          data:{
	               'action':'do_ajax',
	               'deleteUserTrip' : tripID
	               },
	          dataType: 'JSON',
	          success:function(data){    
			        console.log(data);
			       var n = noty({text: 'Suppression effectuée'});
	               $('#ut-' + tripID).remove();
	                             },
	          error: function(errorThrown){
	               var n = noty({text: 'Echec de la suppression :('});
	               console.warn(errorThrown.responseText);
	          }
		     });
}

/*
	Global functions
*/
function saveTrip(){
		tripName = $('#tripName').val();
		if(tripName === ''){
			$('#tripName').addClass('required').attr('placeholder','champs obligatoire');
		} else{
			$('#tripName').removeClass('required');
			$.ajax({
	          url: '/wp-admin/admin-ajax.php',
	          data:{
	               'action':'do_ajax',
	               'reservation' : 1,
	               'bookinkTrip': tripName
	               },
	          dataType: 'JSON',
	          success:function(data){    
			        console.log(data);
			        if(data === '10'){
				        var n = noty({text: 'Il n\'est pas possible d\'enregistrer plus de 10 élements.Merci d\'effacer des events dans votre compte',type: 'alert'});
			        } else {
				        var n = noty({text: 'Résérvation effectué ! elle est visible dans "mon compte"'});
			        }
			       
	                
	                             },
	          error: function(errorThrown){
	               var n = noty({text: 'Echec de la sauvegarde :('});
	               console.log(errorThrown.responseText);
	          }
		     });
		}

     
	
	
}

function addActivity(id,activityname,price,type,img){

	getLength = reservation.tripObject[reservation.currentDay][id];
	if(!getLength){
		reservation.tripObject[reservation.currentDay][id] = {
			name  : activityname,
			price : price,
			type  : type,
			img   : encodeURIComponent(img)
		}
		reservation.currentBudget = parseInt( (reservation.currentBudget + price),10);
		tripImg = (img) ? '<img src="'+img+'" />' : '';
		tripType = (type) ? type : 'notDefined';
					
		$('.dayblock[data-date="'+ reservation.currentDay +'"] .day-content').append('<div data-id="'+ id +'" class="dc '+tripType+'"><span class="popit">'+ tripImg +'</span>'+ activityname +' <span class="dp">'+ price +' euros</span> <div class="fs1" aria-hidden="true" data-icon="" onclick="deleteActivity(\''+ reservation.currentDay +'\', '+ id +', '+ price +')"></div></div>');

		var n = noty({text: 'Ajouté à votre séjour'});
		cookieValue = JSON.stringify(reservation);
		Cookies.set('reservation', cookieValue, { expires: 7, path: '/' });
	} else {
		var n = noty({text: 'cette activité est déjà présente sur cette journée'});
	}

}

/*
*delete Activity from Obj
*@param day : string format dd/mm/yyyy
*@parama id : number 
*@param price : number	
*/
function deleteActivity(day,id,price){
	console.log(day);
	$('.dayblock[data-date="'+day+'"]').find('.day-content div[data-id="'+ id +'"]').remove();
	var target = $('.dayblock[data-date="'+day+'"]').find('.day-wrapper');
	target.addClass('anim-effect-boris');
	setTimeout(function(){
		target.removeClass('anim-effect-boris');
	}, 300);
	obj = reservation.tripObject[day];
	reservation.currentBudget = parseInt( (reservation.currentBudget - price),10);
	delete obj[id];
	cookieValue = JSON.stringify(reservation);
	Cookies.set('reservation', cookieValue, { expires: 7, path: '/' });
	
}

/*
* Get this day active
* set it active globally and in html
* popup success
* @param day : string format dd/mm/yyyy
*/
function changeCurrentDay(day){
	reservation.currentDay = day;
	$(".dayblock[data-date='"+ day+"']").addClass('current').siblings().removeClass('current');
	var n = noty({text: 'changement du jour actif'});
	cookieValue = JSON.stringify(reservation);
	Cookies.set('reservation', cookieValue, { expires: 7, path: '/' });
}
/*
* delete full Day
* need to calculate number of days and set departure/arrival
* @param day : string format dd/mm/yyyy
*/
function removeDay(day){
	delete reservation.tripObject[day];
	reservation.days = reservation.days - 1;
	
	$(".dayblock[data-date='"+ day+"']").remove();
	reservation.departure = $('.dayblock:last-child').attr('data-date');
	
	
	console.log(reservation);

	cookieValue = JSON.stringify(reservation);
	Cookies.set('reservation', cookieValue, { expires: 7, path: '/' });

	var n = noty({text: 'Jour supprimé'});
}
/*
* set Budget in obj, store it in cookies
* @param min : number 
* @parama max : number 
*/
function setBudgetPer(min,max){
		reservation.budgetPerMin = min;
		reservation.budgetPerMax = max; 
		console.log('set budget');  
		//setCookie('reservation', JSON.stringify(reservation), 2);
		cookieValue = JSON.stringify(reservation);
		Cookies.set('reservation', cookieValue, { expires: 7, path: '/' });
}

function setReservationTerms(theme, lieu){
	reservation.theme = theme;
	reservation.lieu = lieu;
	console.log('set terms');
	//setCookie('reservation', JSON.stringify(reservation), 2);
	cookieValue = JSON.stringify(reservation);
	Cookies.set('reservation', cookieValue, { expires: 7, path: '/' });
}

function setNumberOfPersonns(personNb){
	reservation.participants = personNb;
	console.log('set number of personns');
	//setCookie('reservation', JSON.stringify(reservation), 2);
	cookieValue = JSON.stringify(reservation);
	Cookies.set('reservation', cookieValue, { expires: 7, path: '/' });
}
//INIT TRIP
/*
	a useless function
*/
function createdayTrip(id,day){
	this.idTrip = id;
	this.idDay = day;
	this.animation = animation;
	this.repasmidi = repasmidi;
	this.repassoiree = repassoiree;
	this.soiree = soiree;
	this.hebergement = hebergement;
	this.transport = transport;
	this.createDay = createDay;
}

/*
* load trip from a known object
* $trip : obj - full trip object
* gotoBookingPage - bolean - are we on the booking page
*/
function loadTrip($trip,gotoBookingPage){
	reservation = {};
	reservation = $trip;
	//either we need to go to the page or not
	if(gotoBookingPage === true){
		console.log('true');
		//setCookie('reservation', JSON.stringify($trip), 2);
		cookieValue = JSON.stringify($trip);
		Cookies.set('reservation', cookieValue, { expires: 7, path: '/' });
		window.location = bookingPage;
	}else {
		$getBudgetMin = ( reservation.budgetPerMin ) ? reservation.budgetPerMin : 100;
		$getBudgetMax = ( reservation.budgetPerMax ) ? reservation.budgetPerMax : 300;
		$('#daysTrip').empty();
		$( "#arrival" ).datepicker( "setDate", reservation.arrival );
		$( "#departure" ).datepicker( "setDate", reservation.departure );
		$( "#slider-range" ).slider( "option", "values", [ reservation.budgetPerMin, reservation.budgetPerMax ] );
		$( "#budget" ).val( reservation.budgetPerMax + "/" + reservation.budgetPerMax );
		$('#st').html(reservation.budgetPerMin);
		$('#end').html(reservation.budgetPerMax);
		$('#participants').val(reservation.participants );
		$('#budget').val(reservation.budgetPerMin+'/'+reservation.budgetPerMax);
		$("#lieu").select2("val", reservation.lieu);
		$("#theme").select2("val", reservation.theme);
		defineTripDates();
		the_activites();

		var n = noty({text: 'réussite du chargement de votre voyage'});
	}
	

}



/*
* init trip if there is no previous data, based on dates inputs
* define global obj :  budget, participants, dates
* init tripdates fn and activities fn
*/
function initTrip(){
	
	//get global var from project
	$participants = $('#participants').val();
	$budgetRange = $('#budget').val().split('/');
	
	reservation.participants = $participants;	
	reservation.budgetPerMin = $budgetRange[0];
	reservation.budgetPerMax = $budgetRange[1];
	reservation.globalBudgetMin = $budgetRange[0] * $participants;
	reservation.globalBudgetMax = $budgetRange[1] * $participants;
	
	defineTripDates();
	the_activites();
	
	console.log(reservation);
	
}

/*
* define dates for the trip
* check if object dates exists, calculate range, define nb of days
* create html days list (without activites)
*/
function defineTripDates(){

	if(!reservation.departure){
		reservation.departure = $('#departure').val();
		reservation.arrival = $('#arrival').val();
	}
	
	var start   = moment(reservation.arrival, "DD/MM/YYYY");
	var end = moment(reservation.departure, "DD/MM/YYYY");
	//define range & modify html
	var range = moment().range(start,end);
	//define number of days
	reservation.days = range.diff('days') + 1;
	i = 0;
	//for each days, define an obj
	range.by('days', function(momentTime) {
		var dayIs = moment(momentTime).format("DD/MM/YYYY");
		var niceDayIs = moment(momentTime).format("dddd DD MMMM ");
		//define first day as the curent day in the global var
		if(i === 0){
			reservation.currentDay = dayIs;
		}
		//day obj is not defined we define it 
		if(!reservation.tripObject[dayIs]){
			reservation.tripObject[dayIs] = {};
		}
		//build html list
		var currentClass = (i === 0) ? 'current' : 'classic';
		var removeFn = (i !== reservation.days - 1) ? '' : '<span onclick="removeDay(\''+ dayIs+'\');" class="fs1 rd" aria-hidden="true" data-icon="Q">';
		
		$('#daysTrip').append('<div class="dayblock '+currentClass+'" data-date="'+ dayIs +'" ><div class="day-wrapper">'+removeFn+'</span><span onclick="changeCurrentDay(\''+ dayIs+'\');" class="js-change fs1" aria-hidden="true" data-icon=""></span>'+ niceDayIs +'</div><div class="day-content"></div></div>');
		i++;
	});
}

/*
* get activites from the obj
* calculate days, iterate thrue them to get activites
* build html list of activites in days html list
*/
function the_activites(){
	//console.log(reservation);
	daysObj = reservation.tripObject;
	
	days = Object.keys(daysObj).length;
	console.log();
	if(days !== 0){
		//iterate thrue days
		for (var day in daysObj){
			activities = Object.keys(daysObj[day]).length;
			//if have activites, iterate thrue them
			if(activities > 0){
				for (var id in daysObj[day]){
					//console.log(id);
					var activityname = reservation.tripObject[day][id]['name'];
					var price = reservation.tripObject[day][id]['price'];
					var img = decodeURIComponent(reservation.tripObject[day][id]['img']);
					var type = reservation.tripObject[day][id]['type'];
					tripImg = (img) ? '<img src="'+img+'" />' : '';
					tripType = (type) ? type : 'notDefined';
					$('.dayblock[data-date="'+ day +'"]').find('.day-content').append('<div data-id="'+ id +'" class="dc '+type+'"><span class="popit">'+ tripImg +'</span>'+ activityname +' <span class="dp">'+ price +' euros</span><div class="fs1" aria-hidden="true" data-icon="" onclick="deleteActivity(\''+ day +'\', '+ id +', '+ price +')"></div></div>');
				}
			}

		}
	}
}


jQuery(function () {
    //LOGIN MODAL
    //$('#loginform').fancybox();
    //BOOKING JS
    
    $('.postform').select2({
	    'width' : '96%'
    });
   
    $('.open-popup-link').magnificPopup({
  type:'inline',
  midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
});

    //SLIDER RANGE SETTINGS
    var getBudgetMin = (reservation.budgetPerMin === "") ? reservation.budgetPerMin : 100;
    var getBudgetMax = (reservation.budgetPerMax === "") ? reservation.budgetPerMax : 300;
    $("#slider-range").slider({
        range: true,
        min: 50,
        max: 1500,
        step: 1,
        values: [getBudgetMin, getBudgetMax],
        slide: function (event, ui) {
            $("#budget").val(ui.values[0] + "/" + ui.values[1]);
            $('#st').html(ui.values[0]);
            $('#end').html(ui.values[1]);
        },
        stop: function (event, ui) {
            setBudgetPer(ui.values[0], ui.values[1]);
        }
    });

    //DATEPICKER settings
    $.datepicker.setDefaults($.datepicker.regional["fr"]);

    $("#arrival").datepicker({
        defaultDate: "+1w",
        dateFormat: "dd/mm/yy",
        altFormat: "dd/mm/yy",
        minDate: 0,
        changeMonth: true,
        numberOfMonths: 1,
        inline: true,
        showOtherMonths: true,
        beforeShow: function (input, inst) {
            $('#ui-datepicker-div').addClass('ll-skin-melon');
        },
        onClose: function (selectedDate) {
            var maxRange = moment(selectedDate, 'DD/MM/YYYY').add(2, 'days');
            var maxDate = maxRange.format('DD/MM/YYYY');
            //$( "#departure" ).datepicker( "option", "minDate", maxDate );
            $("#departure").datepicker("setDate", maxDate);
            console.log(selectedDate);
            //$( "#departure" ).datepicker( "option", "maxDate", maxRange._i );
        }
    });

    $("#departure").datepicker({
        defaultDate: "+1w",
        dateFormat: "dd/mm/yy",
        altFormat: "dd/mm/yy",
        changeMonth: true,
        numberOfMonths: 1,
        disabled: true,
        minDate: 0,
        inline: true,
        showOtherMonths: true,
        beforeShow: function (input, inst) {
            $('#ui-datepicker-div').addClass('ll-skin-melon');
        },
        onClose: function (selectedDate) {
            $("#arrival").datepicker("option", "maxDate", selectedDate);

        }
    });

    $('#participants').change(function () {
        var personNb = $(this).val();
        setNumberOfPersonns(personNb);
    });
    //AJAX REQUEST FOR THE FILTERING ACTIVITIES
    $('.terms-change').change(function () {
        var theme = $('#theme').val();
        var lieu = $('#lieu').val();
        setReservationTerms(theme, lieu);
        doAjaxRequest(theme, lieu);
    });
	$('#typeterms input[type=checkbox]').change(function(){
		var type = $(this).attr('value');
		var theme = $('#theme').val();
        var lieu = $('#lieu').val();
        checkedTypes = [];
        $("#typeterms input[type=checkbox]:checked").each(function(){
		    checkedTypes.push($(this).val());
		});
		console.log(checkedTypes);
		doAjaxRequest(theme, lieu, checkedTypes);
	});

    moment.locale('fr', {
        months: "janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre".split("_"),
        monthsShort: "janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.".split("_"),
        weekdays: "dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi".split("_"),
        weekdaysShort: "dim._lun._mar._mer._jeu._ven._sam.".split("_"),
        weekdaysMin: "Di_Lu_Ma_Me_Je_Ve_Sa".split("_"),
        longDateFormat: {
            LT: "HH:mm",
            LTS: "HH:mm:ss",
            L: "DD/MM/YYYY",
            LL: "D MMMM YYYY",
            LLL: "D MMMM YYYY LT",
            LLLL: "dddd D MMMM YYYY LT"
        },
        calendar: {
            sameDay: "[Aujourd'hui à] LT",
            nextDay: '[Demain à] LT',
            nextWeek: 'dddd [à] LT',
            lastDay: '[Hier à] LT',
            lastWeek: 'dddd [dernier à] LT',
            sameElse: 'L'
        },
        relativeTime: {
            future: "dans %s",
            past: "il y a %s",
            s: "quelques secondes",
            m: "une minute",
            mm: "%d minutes",
            h: "une heure",
            hh: "%d heures",
            d: "un jour",
            dd: "%d jours",
            M: "un mois",
            MM: "%d mois",
            y: "une année",
            yy: "%d années"
        },
        ordinalParse: /\d{1,2}(er|ème)/,
        ordinal: function (number) {
            return number + (number === 1 ? 'er' : 'ème');
        },
        meridiemParse: /PD|MD/,
        isPM: function (input) {
            return input.charAt(0) === 'M';
        },
        meridiem: function (hours, minutes, isLower) {
            return hours < 12 ? 'PD' : 'MD';
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4 // The week that contains Jan 4th is the first week of the year.
        }
    });

    //last action, set trip or load existant
    //loadTrip(exampleReservation);
    //check if there is an existing trip around the user
    if(isBookingTpl){
	    checkCookie();
    } else {
	    console.warn('not booking page');
    }
    


});
