var $ = jQuery;
var bookingPage = '/reservation-service/';
var isBookingTpl = $('#booking-wrapper').length;
var USERID = $('#user-logged-in-infos').attr("data-id");

var reservation = {
	user 	  : '',
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
    timeout: 1800, // delay for closing event. Set false for sticky notifications
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
	//console.log(type);
     jQuery.ajax({
          url: '/wp-admin/admin-ajax.php',
          settings:{
	          cache : true
          },
          data:{
               'action':'do_ajax',
               'theme':theme,
               'geo' : geo,
               'type' : type,
               'count':1
               },
          //JSON can cause issues on Chrome ? use text instead ?
          dataType: 'JSON',
          success:function(data){    
		            jQuery('#activities-content').empty().append(jQuery('<div>', {
		                html : data
		            }));
		            //console.log(data);
                             },
          error: function(errorThrown){
               console.warn('error');
               console.log(errorThrown);
               var n = noty({
		               text: 'Echec du filtre de recherche :(',
		               template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		               });
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
        if (c.indexOf(name) === 0) return c.substring(name.length, c.length);
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

function tripToCookie(reservation){
	cookieValue = JSON.stringify(reservation);
	Cookies.set('reservation', cookieValue, { expires: 1, path: '/' });
	
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
	               var n = noty({
		               text: 'Echec de la suppression :(',
		               template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		               });
	               console.warn(errorThrown.responseText);
	          }
		     });
}

/*
	Global functions
*/

/*
	saveTrip
	save trip to DB, ajax request
	trip name is mandatory
	@param string (existingTripId) unique ID if exist, will perform an update of the trip
*/
function saveTrip(existingTripId){
		tripName = $('#tripName').val();
		if(tripName === ''){
			$('#tripName').addClass('required').attr('placeholder','champs obligatoire');
		} else{
			$('#tripName').removeClass('required');
			//set name and store it in reservation object
			reservation.name = tripName;
			tripToCookie(reservation);
			//default value for existing Trip
			if(!existingTripId){
				existingTripId = 0;
			}
			//request the ajax store fn
			$.ajax({
	          url: '/wp-admin/admin-ajax.php',
	          data:{
	               'action':'do_ajax',
	               'reservation' : 1,
	               'bookinkTrip': tripName,
	               'existingTripId' : existingTripId
	               },
	          dataType: 'JSON',
	          success:function(data){    
			        console.log(data);
			        if(data === '10'){
				        var n = noty({
					        text: 'Il n\'est pas possible d\'enregistrer plus de 10 élements.Merci d\'effacer des events dans votre compte',
					        template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
					        });
			        } else if( data === null){
				        var n = noty({
					        text: 'enregistrement non effectué, merci de nous contacter directement',
					        template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
					        });
			        } else if( data === 'updated'){
				        var n = noty({
					        text: 'enregistrement mis à jour'
					        });
			        } else if( data === 'stored'){
				        var n = noty({text: 'Résérvation effectué ! elle est visible dans "mon compte"'});
			        } else {
				        var n = noty({
					        text: 'enregistrement non effectué, merci de nous contacter directement',
					        template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
					        });
			        }
			       
	                
	                             },
	          error: function(errorThrown){
	               var n = noty({
		               text: 'Echec de la sauvegarde :(',
		               template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		               });
	               console.log(errorThrown.responseText);
	          }
		     });
		}

     
	
	
}
/*animation*/
function addActivityAnimation(id){
	
	flyingTarget = $('.dayblock.current').offset();
	flyingStart = $('#ac-'+ id);
	flyingStartPoint = flyingStart.offset();
	
	flyingStart.find('img').clone().appendTo(flyingStart)
		.addClass('future-flying-img animated zoomOutRight');
	
	setTimeout(function(){
		$('.future-flying-img')
			.fadeOut(500)
			.remove();
	}, 1000);
	
	
}
function addActivity(id,activityname,price,type,img,order){

	getLength = reservation.tripObject[reservation.currentDay][id];
	if(!getLength){
		reservation.tripObject[reservation.currentDay][id] = {
			name  : activityname,
			price : price,
			type  : type,
			img   : encodeURIComponent(img),
			order : order
		};
		//console.log('obj price : ' + price);
		reservation.currentBudget = parseInt(reservation.currentBudget,10) + parseInt(price,10);
		tripImg = (img) ? '<img src="'+img+'" />' : '';
		tripType = (type) ? type : 'notDefined';
		$htmlDay = $('.dayblock[data-date="'+ reservation.currentDay +'"] .day-content');		
		$htmlDay.append('<div data-order="'+order+'" data-id="'+ id +'" class="dc '+tripType+'"><span class="popit">'+ tripImg +'</span>'+ activityname +' <span class="dp">'+ price +' € </span> <div class="fs1" aria-hidden="true" data-icon="" onclick="deleteActivity(\''+ reservation.currentDay +'\', '+ id +', '+ price +')"></div></div>');
		
		$htmlDay.find('div.dc').sort(function (a, b) {
		    return +a.getAttribute('data-order') - +b.getAttribute('data-order');
		})
		.appendTo( $htmlDay );

		checkBudget();
		addActivityAnimation(id);
		var n = noty({text: 'Ajouté à votre séjour'});
		tripToCookie(reservation);
	} else {
		var n = noty({
			text: 'cette activité est déjà présente sur cette journée',
			template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
			});
	}

}

/*
*delete Activity from Obj
*@param day : string format dd/mm/yyyy
*@parama id : number 
*@param price : number	
*/
function deleteActivity(day,id,price){
	//console.log(day);
	$('.dayblock[data-date="'+day+'"]').find('.day-content div[data-id="'+ id +'"]').remove();
	var target = $('.dayblock[data-date="'+day+'"]').find('.day-wrapper');
	target.addClass('anim-effect-boris');
	setTimeout(function(){
		target.removeClass('anim-effect-boris');
	}, 300);
	obj = reservation.tripObject[day];
	//console.log('obj price : ' + price);
	reservation.currentBudget = parseInt( (reservation.currentBudget - price),10);
	delete obj[id];
	checkBudget();
	tripToCookie(reservation);
	
}

/**************
	DAYS RELATED FUNCTIONS
	**************/

/*
	check if arrival is before departure
	check number of days
*/	
function checkIfDateOk(start,end){
	
	//console.log(moment(start).format("DD/MM/YYYY") + ' => initials dates  <= ' + moment(end).format("DD/MM/YYYY"));
	
	isReversedDate = moment(start).isAfter(end);
	
	if(isReversedDate === true ){
		
		console.warn('issue with departure date : ' + end);
		console.log(reservation.arrival + ' is after ' + reservation.departure);
		console.log(start + ' is after ' + end);
		
		//add One day to rebuild an event with 2 days, but empty :(
		end = start.add(1,'days').format("DD/MM/YYYY");
		reservation.departure = end;
		reservation.days = 2;
		var n = noty({
			text: 'Erreur dans le calcul des jours...nous avons du tout reconstruire...désolé',
			template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		});
	} else {
		//console.log(reservation.arrival + ' is before ' + reservation.departure + ' so it is fine...');
	}

	
	return isReversedDate;
	
}	
/*
	check number of days allowed
*/
function checkNumberOfDays(days){
	if(days > 4 || isNaN(days) === true){
		console.warn('number of days too high or wrong');
		days = 4;
	}
	
	return days;
}	
/*
	check if there is same amount of days
	in startToEnd and in tripObject
*/
function checkCoherence(start,end){
	calcNumberOfDays = parseInt(end.diff(start , 'days'),10) + 1;
	tripNbDays = Object.keys(reservation.tripObject).length;
	//console.log(tripNbDays,calcNumberOfDays);
	if(tripNbDays !== calcNumberOfDays){
		console.warn('diff between tripObj and dates');
	}
}
/*
* define dates for the trip
* check if object dates exists, calculate range, define nb of days
* create html days list (without activites)
* calc max number of days
*/
function defineTripDates(){

	if(!reservation.departure){
		console.warn('departure did not exist');
		reservation.departure = $('#departure').val();
		reservation.arrival = $('#arrival').val();
	}
	
	var start   = moment(reservation.arrival,"DD/MM/YYYY");
	var end = moment(reservation.departure,"DD/MM/YYYY");
	
	checkIfDateOk(start,end);
	checkCoherence(start,end);
	//define range & modify html
	momentstart = moment(start).format("DD/MM/YYYY");
	//console.log(momentstart, endFormatted);
	var range = moment().range(start,end);
	//console.log(range);
	//define number of days
	//we need the exact nb starting from 1 - that's why we add one here
	  calcNumberOfDays = parseInt(end.diff(start , 'days'),10) + 1;
	  //console.log('calcNumberOfDays' + calcNumberOfDays);
	  reservation.days = checkNumberOfDays(calcNumberOfDays);
	  
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
		var removeFn = (i !== reservation.days - 1) ? '' : '<span onclick="removeLastDay();" class="fs1 rd" aria-hidden="true" data-icon="Q">';
		
		$('#daysTrip').append('<div class="dayblock '+currentClass+'" data-date="'+ dayIs +'" ><div class="day-wrapper">'+removeFn+'</span><span onclick="changeCurrentDay(\''+ dayIs+'\');" class="js-change fs1" aria-hidden="true" data-icon=""></span>'+ niceDayIs +'</div><div class="day-content"></div></div>');
		i++;
	});
}

/*
	add a day to event
	maximum is 4 days
	minimum is 2 days
	increment number of days
	update departure day
	update trip object
*/
function addADay(){
	if(reservation.days > 3){
		
		var n = noty({
			text: 'Nombre maximum de jour atteint',
			template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		});
		
	} else {
		$('.fs1.rd').remove();
		//define last day
		lastDay = moment(reservation.departure, "DD/MM/YYYY");
		reservation.departure = lastDay.add(1, 'days').format("DD/MM/YYYY");
		//$( "#departure" ).datepicker( "setDate", reservation.departure );
		var niceDayIs = lastDay.format("dddd DD MMMM ");
		dayIs = reservation.departure;
		reservation.days++;
		reservation.tripObject[dayIs] = {};
		var removeFn = '<span onclick="removeLastDay();" class="fs1 rd" aria-hidden="true" data-icon="Q">';
		//html append Day
		$('#daysTrip').append('<div class="dayblock" data-date="'+ reservation.departure +'" ><div class="day-wrapper">'+removeFn+'</span><span onclick="changeCurrentDay(\''+ reservation.departure+'\');" class="js-change fs1" aria-hidden="true" data-icon=""></span>'+ niceDayIs +'</div><div class="day-content"></div></div>');
		
		//store the day added
		tripToCookie(reservation);
		var n = noty({text: 'Jour ajouté'});
	}
}

/*
	remove Last day
	decrement number of days
	decrement departure day
	add a delete button to new last day
*/

function removeLastDay(){
	if(parseInt(reservation.days,10) < 2){
		var n = noty({
			text: 'Nombre minimum de jour atteint',
			template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		});
	} else{
		lastDay = moment(reservation.departure, "DD/MM/YYYY");
		reservation.days--;
		lastDayString = lastDay.format("DD/MM/YYYY");
		//console.log(lastDayString);
		delete reservation.tripObject[lastDayString];
		$(".dayblock[data-date='"+ lastDayString +"']").remove();
		newDeparture = lastDay.subtract(1, 'days').format("DD/MM/YYYY");
		reservation.departure = newDeparture;
		reservation.currentDay = reservation.arrival;
		$(".dayblock[data-date='"+ reservation.currentDay +"']").addClass('current');
		//$( "#departure" ).datepicker( "setDate", reservation.departure );
		//add a del button
		var spanBtn = '<span onclick="removeLastDay();" class="fs1 rd" aria-hidden="true" data-icon="Q"></span>';
		$(".dayblock:last-child").find('.day-wrapper').append(spanBtn);
		//store the day added
		//console.log(reservation);
		checkBudget();
		tripToCookie(reservation);
		var n = noty({text: 'Jour supprimé'});
	}
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
	tripToCookie(reservation);
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
	tripToCookie(reservation);
	var n = noty({text: 'Jour supprimé'});
}

/*
	Change tripObject
	duplicate key (day) 
	rename it to the new day - add a prefix to make it unique
	delete old key
	rename it correctly (remove prefix)
	rebuild html list
	obj : replace departure && arrival
*/
function changeDateRangeEvent(selectedDate){
	obj = reservation.tripObject;
	oldTrip = Object.keys(reservation.tripObject);
	
	//console.log(oldTrip);
	//number of days can't be negative or null
	checkNumberOfDays(reservation.days);
	//we set the new departure date
	if(reservation.days === 1){
		reservation.departure = selectedDate;
	} 
	
	//iterate through dates
	for (var i in oldTrip) {
    	if (oldTrip.hasOwnProperty(i) && typeof(i) !== 'function') {
	    	oldDay = oldTrip[i];
	    	//calculate days
	    	formattDay = moment(oldDay, "DD/MM/YYYY");
	    	//if = 0 this is Events arrival
	    	if(parseInt(i,10) < 1){
		    	//console.log('define first day');
		    	incrementDay = selectedDate + '*';
		    	reservation.arrival = selectedDate;
		    	reservation.currentDay = selectedDate;
		    	//console.log(incrementDay);
		    	//replace with new day
		    	reservation.tripObject[incrementDay] = reservation.tripObject[oldDay];
		    	//delete old day
				delete reservation.tripObject[oldDay];
		        //console.log(oldTrip[i]);
		    	
	    	} else {
		    	//console.log('more than one day');
		    	incrementDay = moment(selectedDate, "DD/MM/YYYY").add(i, 'days').format("DD/MM/YYYY");
		        var dminys = parseInt((reservation.days),10);
		        //console.log(i);
			    //console.log('number of days = ',dminys);
		    	if( parseInt(i,10)  ===  dminys){
			    	//console.log('last event day');
			    	reservation.departure = incrementDay;
		    	}
		    	reservation.tripObject[incrementDay+ '*'] = reservation.tripObject[oldDay];
				delete reservation.tripObject[oldDay];
		        //console.log(oldTrip[i]);
	        
	    	}
	    }
	}
	daysWithPrefix = Object.keys(reservation.tripObject);
	//remove prefix
	for (var i in oldTrip) {
		if (daysWithPrefix.hasOwnProperty(i) && typeof(i) !== 'function') {
			
		oldDay = daysWithPrefix[i];
		var res = oldDay.replace("*", "");
    	reservation.tripObject[res] = reservation.tripObject[oldDay];
		delete reservation.tripObject[oldDay];
		//change last day
		//console.log(i);
			lastDay = parseInt(reservation.days,10) - 1;
			if( parseInt(i,10)   === lastDay ){
				//console.log('last day');
				reservation.departure = res;
			}
		}
	}
	
	
	//console.log(reservation);
	//re-create html days
	loadTrip(reservation,false);
	tripToCookie(reservation);
	var n = noty({text: 'date changée'});
	
	
	
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
		checkBudget();
		//setCookie('reservation', JSON.stringify(reservation), 2);
		tripToCookie(reservation);
}

function setReservationTerms(theme, lieu){
	reservation.theme = theme;
	reservation.lieu = lieu;
	tripToCookie(reservation);
}

function setNumberOfPersonns(personNb){
	reservation.participants = personNb;
	checkBudget();
	tripToCookie(reservation);
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
	reservation.user = USERID;
	reservation.currentBudget = 0;
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
		$('#tripName').val(reservation.name);
		$( "#arrival" ).datepicker( "setDate", reservation.arrival );
		//$( "#departure" ).datepicker( "setDate", reservation.departure );
		$( "#slider-range" ).slider( "option", "values", [ reservation.budgetPerMin, reservation.budgetPerMax ] );
		$( "#budget" ).val( reservation.budgetPerMax + "/" + reservation.budgetPerMax );
		$('#st').html(reservation.budgetPerMin);
		$('#end').html(reservation.budgetPerMax);
		$('#participants').val(reservation.participants );
		$('#budget').val(reservation.budgetPerMin+'/'+reservation.budgetPerMax);
		if(reservation.name){
			$('#tripName').val(reservation.name);
		}
		if( $("#lieu").length ){
			$("#lieu").select2("val", reservation.lieu);
			$("#theme").select2("val", reservation.theme);
		}
		
		defineTripDates();
		the_activites();
		checkBudget();

		var n = noty({text: 'Chargement de votre voyage'});
	}
	

}

function checkBudget(){
	globalBudget = parseInt(reservation.budgetPerMax,10);
	actualCost = parseInt(reservation.currentBudget,10);
	if( globalBudget < actualCost){
		console.log('budget is too high');
		$('#budget-icon').css('color','red');
		
	} else {
		console.log('budget is ok');
		$('#budget-icon').css('color','green');
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
	reservation.user = USERID;
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
* get activites from the obj
* calculate days, iterate thrue them to get activites
* build html list of activites in days html list
* get price obj
* add to global Budget
*/
function the_activites(){
	//console.log(reservation);
	daysObj = reservation.tripObject;
	days = Object.keys(daysObj).length;
	
	if(days !== 0){
		//iterate thrue days
		for (var day in daysObj){
			if (daysObj.hasOwnProperty(day)) {
			activities = Object.keys(daysObj[day]).length;
			//if have activites, iterate thrue them
			if(activities > 0){
				for (var id in daysObj[day]){
					if (daysObj[day].hasOwnProperty(id)) {
					//console.log(id);
					var activityname = reservation.tripObject[day][id]['name'],
						price = reservation.tripObject[day][id]['price'],
						order = reservation.tripObject[day][id]['order'],
						img = decodeURIComponent(reservation.tripObject[day][id]['img']),
						type = reservation.tripObject[day][id]['type'];
						
					tripImg = (img) ? '<img src="'+img+'" />' : '';
					tripType = (type) ? type : 'notDefined';
					$htmlDay = $('.dayblock[data-date="'+ day +'"]').find('.day-content');
					//build html
					$htmlDay.append('<div data-order="'+order+'" data-id="'+ id +'" class="dc '+type+'"><span class="popit">'+ tripImg +'</span>'+ activityname +' <span class="dp">'+ price +' €</span><div class="fs1" aria-hidden="true" data-icon="" onclick="deleteActivity(\''+ day +'\', '+ id +', '+ price +')"></div></div>');
					$htmlDay.find('div.dc').sort(function (a, b) {
					    return +a.getAttribute('data-order') - +b.getAttribute('data-order');
					})
					.appendTo( $htmlDay );
		
					//add to global budget
					//console.log('obj price : '+price);
					//console.log('Global : ' + reservation.currentBudget)
					reservation.currentBudget += price;
				}
				}
			}
}
		}
	}
}


jQuery(function () {

    
    $('.postform').select2({
	    'width' : '96%'
    });
   
    $('.open-popup-link').magnificPopup({
  type:'inline',
  midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
});

    //SLIDER RANGE SETTINGS
    var getBudgetMin = (reservation.budgetPerMin && reservation.budgetPerMin > 0) ? reservation.budgetPerMin : 100;
    var getBudgetMax = (reservation.budgetPerMax && reservation.budgetPerMax > 0) ? reservation.budgetPerMax : 300;

    $("#slider-range").slider({
        range: true,
        min: 50,
        max: 600,
        step: 10,
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
            //$("#departure").datepicker("setDate", maxDate);
            //console.log(selectedDate);
            arrival = moment(reservation.arrival, 'DD/MM/YYYY');
            //console.log(arrival);
            //if date does not change we don't move, otherwise we calculate the new days
            if(moment(selectedDate, 'DD/MM/YYYY').isSame(arrival)){
	            console.log('no date move');
            } else {
	            console.log('date move');
	            changeDateRangeEvent(selectedDate);
            }
            
            
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
            //$('#ui-datepicker-div').addClass('ll-skin-melon');
        },
        onClose: function (selectedDate) {
            //$("#arrival").datepicker("option", "maxDate", selectedDate);

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
        if(lieu === null){
        	lieu = $('select#lieu option:first-child').attr('value');
        	$("select#lieu").val(lieu).trigger("change");
        }
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
    
    
//RESERVATION - SINGLE PAGE
var slickReservation = $('.slickReservation');
    if (slickReservation.length) {
        slickReservation.slick({
            autoplay: true,
            dots: false,
            infinie: true,
            arrows: true,
            prevArrow: '<div class="fs1 prevmulti" aria-hidden="true" data-icon="4"></div>',
            nextArrow: '<div class="fs1 nextmulti" aria-hidden="true" data-icon="5"></div>',
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: false
        });
    }
$('.img-pop').magnificPopup({ 
  type: 'image',
  gallery:{
    enabled:true
  }
	// other options
});


});
