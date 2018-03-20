(function ($) {
	$(document).ready(function () {
		//$.cookie("UTCOffset",new Date().getTimezoneOffset());
		
		function setUTCOffset() {
			var UTCOffset = new Date().getTimezoneOffset();
			document.cookie="UTCOffset="+UTCOffset+";path=/";
		}
		setUTCOffset();
		
		$('#next').click(function () {
			$.post(
				PT_Ajax.ajaxurl,
				{
					// wp ajax action
					action: 'ajax-inputtitleSubmit',

					// vars
					title: $('input[name=title]').val(),
					myvariable: 'somethingstupid',

					// send the nonce along with the request
					nextNonce: PT_Ajax.nextNonce
				},
				function (response) {
					//console.log(response);
				}
			);
			return false;
		});
		
		$('#saveAlliance').click(function() {
			var $r = $("#result");
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-savealliance',
					nextNonce: PT_Ajax.nextNonce,
					
					alliancename: $('input[name=AllianceName]').val(),
					prestigeLevel: $("#PrestigeLevel").children(":selected").val(),
					nonOwner: $("#NonOwner").attr("checked") == "checked" ? 1 : 0
				},
				function (response) {
					if (response.success) {
						$("#result").html("New alliance created").addClass('ui-state-highlight');
					} else {
						$("#result").html("Unable to create alliance (name probably in use already).").addClass('ui-state-error');;
					}
					//console.log(response);
				}
			);
			return false;
		});
		
		$('.saveAllianceChanges').click(function() {
			var $r = $("#result"),
			    allianceID = $(this).attr('AllianceID'),
				allianceName = $('input[AllianceID='+allianceID+']').val(),
				active=$('input.activeAlliances[AllianceID='+allianceID+']').attr('checked'),
				private=$('input.privateAlliances[AllianceID='+allianceID+']').attr('checked'),
				prestigeLevel=$('.PrestigeLevel[AllianceID='+allianceID+']').children(':selected').val();
				//console.log(active);
				
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-savealliancechanges',
					nextNonce: PT_Ajax.nextNonce,
					
					allianceID: allianceID,
					allianceName: allianceName,
					active: (active=='checked'?1:0),
					private:  (private=='checked'?1:0),
					prestigeLevel: prestigeLevel
				},
				function (response) {
					if (response.success) {
						$("#result").html("Alliance Saved").addClass('ui-state-highlight');
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});
		
		$('#saveNewClan').click(function() {
			var $r = $("#result");
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-saveNewClan',
					nextNonce: PT_Ajax.nextNonce,
					
					clanName: $('#newClanName').val()
				},
				function (response) {
					if (response.success) {
						$("#result").html("New clan created").addClass('ui-state-highlight');
						window.location.reload();
					} else {
						$("#result").html("Unable to create clan (name probably in use already).").addClass('ui-state-error');;
					}
					//console.log(response);
				}
			);
			return false;
		});

		$('.saveClanChanges').click(function() {
			var $r = $("#result"),
			    clanID = $(this).attr('clanID'),
				clanName = $('input[ClanID='+clanID+']').val(),
				active=$('input.activeClans[ClanID='+clanID+']').attr('checked'),
				private=$('input.privateClans[ClanID='+clanID+']').attr('checked');
				//console.log(clanID);
				
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-saveClanChanges',
					nextNonce: PT_Ajax.nextNonce,
					
					clanID: clanID,
					clanName: clanName,
					active: (active=='checked'?1:0),
					private: (private=='checked'?1:0)
				},
				function (response) {
					if (response.success) {
						$("#result").html("Clan Saved").addClass('ui-state-highlight');
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});
		
		$('.deleteAlliance').click(function() {
			var $r = $("#result"),
			    allianceID = $(this).attr('AllianceID');
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			if (!confirm('This will remove this alliance and cannot be undone.  Are you sure?')) {
				return;
			}
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-deletealliance',
					nextNonce: PT_Ajax.nextNonce,
					
					allianceID: allianceID
				},
				function (response) {
					if (response.success) {
						$("#result").html("Alliance Deleted").addClass('ui-state-highlight');
						window.location.reload();
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});

		$('.deleteClan').click(function() {
			var $r = $("#result"),
			    clanID = $(this).attr('ClanID');
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			if (!confirm('This will remove this clan and cannot be undone.  Are you sure?')) {
				return;
			}
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-deleteClan',
					nextNonce: PT_Ajax.nextNonce,
					
					clanID: clanID
				},
				function (response) {
					if (response.success) {
						$("#result").html("Clan Deleted").addClass('ui-state-highlight');
						window.location.reload();
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});

		$('.deleteWar').click(function() {
			var $r = $("#result"),
			    warID = $(this).attr('WarID');
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			if (!confirm('This will remove this war and cannot be undone.  Are you sure?')) {
				return;
			}
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-deletewar',
					nextNonce: PT_Ajax.nextNonce,
					
					warID: warID
				},
				function (response) {
					if (response.success) {
						$("#result").html("War Deleted").addClass('ui-state-highlight');
						window.location.reload();
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});

		$('#createWar').click(function() {
			var $r = $("#result"),
			    allianceID = $("#myAlliances").children(":selected").attr("value"),
				baseCount = $('input[name=BaseCount]').val(),
				timeToWar = $('input[name=TimeToWar]').val();
				
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-createwar',
					nextNonce: PT_Ajax.nextNonce,
					
					allianceID: allianceID,
					timeToWar: timeToWar,
					baseCount: baseCount
					
				},
				function (response) {
					if (response.success) {
						$("#result").html("War Created").addClass('ui-state-highlight');
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});

		$('#createWar2').click(function() {
			var $r = $("#result"),
			    allianceID = $("#myAlliances").children(":selected").attr("value"),
				baseCount = $('input[name=BaseCount]').val(),
				timeToWar = $('input[name=TimeToWar]').val(),
				opponent = $('input[name=opponent]').val(),
				notes = $('input[name=notes]').val();
				
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-createwar2',
					nextNonce: PT_Ajax.nextNonce,
					
					allianceID: allianceID,
					timeToWar: timeToWar,
					opponent: opponent,
					notes: notes,
					baseCount: baseCount
					
				},
				function (response) {
					if (response.success) {
						$("#result").html("War Created").addClass('ui-state-highlight');
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});

		$('.setStart').click(function() {
			var $r = $("#result"),
			    warID = $(this).attr('WarID'),
				timeOffset = 0,
				UTCOffset = new Date().getTimezoneOffset();

			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			if ($(".warTimes[WarID="+warID+"]").html() != '') {
				if(confirm('This will overwrite a time already set. Are you sure you want to continue?') != true) { return false; }
			}
			
			timeOffset=prompt('Please enter the time remaining until war (hh:mm).');
			
			if (timeOffset == null) { console.log(timeOffset+' (null)'); return; } else { console.log(timeOffset); }
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-setWarTime',
					nextNonce: PT_Ajax.nextNonce,
					
					warID: warID,
					timeOffset: timeOffset,
					UTCOffset: UTCOffset
					
				},
				function (response) {
					//console.log(timeOffset);
					if (response.success) {
						$("#result").html("War time set").addClass('ui-state-highlight');
						$(".warTimes[WarID="+warID+"]").html(response.newLocalTime);
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});
		
		$('.Friends').change(function() {
			var $r = $("#result"),
			    warID = $(this).attr('warID'),
				friendlyFlag = $(this).children(':selected').val();
			
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-setFriendlyFlag',
					nextNonce: PT_Ajax.nextNonce,
					
					warID: warID,
					friendlyFlag: friendlyFlag
					
				},
				function (response) {
					if (response.success) {
						console.log('Success');
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});
		
		
		$('.TrustLevel').change(function() {
			var $r = $("#result"),
			    clanID = $(this).attr('clanID')
			    userID = $(this).attr('userID'),
				trustLevel = $(this).children(':selected').val();
			
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-setTrustLevel',
					nextNonce: PT_Ajax.nextNonce,
					
					clanID: clanID,
					userID: userID,
					trustLevel: trustLevel
				},
				function (response) {
					if (response.success) {
						console.log('Success');
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});
		
		
		$('.Outcome').change(function() {
			var $r = $("#result"),
			    warID = $(this).attr('warID'),
				outcomeFlag = $(this).children(':selected').val();
			
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-setOutcomeFlag',
					nextNonce: PT_Ajax.nextNonce,
					
					warID: warID,
					outcomeFlag: outcomeFlag
					
				},
				function (response) {
					if (response.success) {
						console.log('Success');
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});
		
		
		function savePersonalWarInfo(warID) {
			var $r = $("#result"),
			    notes = $('.personalWarNotes[warID='+warID+']').val(),
			    inWar = ($('.inWar[warID='+warID+']').attr('checked') == 'checked' ? 1 : 0);
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			//console.log('Notes: '+notes+' / inWar: '+inWar);
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-savePersonalWarInfo',
					nextNonce: PT_Ajax.nextNonce,
					
					warID: warID,
					notes: notes,
					inWar: inWar					
				},
				function (response) {
					if (response.success) {
						console.log('Success');
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		}
		
		$('.personalWarNotes').blur(function() {
			var warID = $(this).attr('warID');
			savePersonalWarInfo(warID);
			console.log('blur');
		});
		
		$('.inWar').change(function() {
			var warID = $(this).attr('warID');
			savePersonalWarInfo(warID);
			console.log('change');
		});
		
		$('.opponentName').blur(function() {
			var warID = $(this).attr('warID'),
			    $r = $("#result"),
			    opponentName = $('.opponentName[warID='+warID+']').val();
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					action: 'ajax-saveOpponentName',
					nextNonce: PT_Ajax.nextNonce,
					
					warID: warID,
					opponentName: opponentName			
				},
				function (response) {
					if (response.success) {
						console.log('Success');
					} else {
						$("#result").html(response.error).addClass('ui-state-error');
					}
					//console.log(response);
				}
			);
			return false;
		});
		
		$('#setImpersonatedUser').click(function () {
			var impersonatedUserID = $("#userIDs").children(":selected").attr("value")
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					// wp ajax action
					action: 'ajax-setImpersonatedUser',

					// vars
					impersonatedUserID: impersonatedUserID,

					// send the nonce along with the request
					nextNonce: PT_Ajax.nextNonce
				},
				function (response) {
					//console.log(response);
					if (response.success) {
						
					} else {
						alert(response.error);
					}
				}
			);
			return false;
		});
		
		$('#clearImpersonatedUser').click(function () {
			$.post(
				PT_Ajax.ajaxurl,
				{
					// wp ajax action
					action: 'ajax-clearImpersonatedUser',

					// send the nonce along with the request
					nextNonce: PT_Ajax.nextNonce
				},
				function (response) {
					//console.log(response);
				}
			);
			return false;
		});
		
		$('#allowPlayer').click(function () {
			var allowedUserID = $("#allowedPlayerID").val(),
			    clanID = $("#clanID").val(),
				$r = $("#result4");
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
				
			$.post(
				PT_Ajax.ajaxurl,
				{
					// wp ajax action
					action: 'ajax-allowPlayer',

					// vars
					allowedUserID: allowedUserID,
					clanID: clanID,

					// send the nonce along with the request
					nextNonce: PT_Ajax.nextNonce
				},
				function (response) {
					if (response.success) {
						$r.html("Your friend can now see your wars").addClass('ui-state-highlight');						
					} else {
						$r.html(response.error).addClass('ui-state-error');
					}
				}
			);
			return false;
		});
		
		$('#warAuditDialog').dialog({
			autoOpen: false,
			width: 'auto'
		});
		
		$('.warChanged').click(function () {
			var warID = $(this).attr('warID');

			$.post(
				PT_Ajax.ajaxurl,
				{
					// wp ajax action
					action: 'ajax-getWarAudit',

					// vars
					warID: warID,

					// send the nonce along with the request
					nextNonce: PT_Ajax.nextNonce
				},
				function (response) {
					//console.log(response);
					if (response.success) {
						var table = "<table>";
						table += '<tr><th>Old Start</th><th>New Start</th><th>By</th><th>On</th></tr>';
						for (var i=0; i<response.retVal.length; i++) {
							var obj = response.retVal[i];
							table += '<tr>';
							table += '<td>'+obj.OldStartLocal+'</td>';
							table += '<td>'+obj.NewStartLocal+'</td>';
							table += '<td>'+obj.ChangedBy+'</td>';
							table += '<td>'+obj.DateTimeStampLocal+'</td>';
							table += '</tr>';
						}
						table += '</table>';
						//console.log(table);
						$('#warAuditDialog').html(table).dialog("open");
						//$r.html("Your friend can now see your wars").addClass('ui-state-highlight');						
					} else {
						//$r.html(response.error).addClass('ui-state-error');
					}
				}
			);
			return false;
		});
		
		$('.ShowHideClan').change(function () {
			var clanID = $(this).attr('clanID'),
			    //showHide = $(this).children(':selected').val(),
				showHide = ($('.ShowHideClan[ClanID='+clanID+']').attr('checked') == 'checked' ? 1 : 0),
				$r = $("#result");
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
				
			$.post(
				PT_Ajax.ajaxurl,
				{
					// wp ajax action
					action: 'ajax-showHideClan',

					// vars
					clanID: clanID,
					showHide: showHide,

					// send the nonce along with the request
					nextNonce: PT_Ajax.nextNonce
				},
				function (response) {
					if (response.success) {
						//$r.html("Your friend can now see your wars").addClass('ui-state-highlight');	
						console.log('success');
					} else {
						//$r.html(response.error).addClass('ui-state-error');
						console.log('failure ('+response.error+')');
					}
				}
			);
			return false;
		});
		
		$('.removeClan').click(function () {
			var clanID = $(this).attr('ClanID'),
			    allowedUserID = $(this).attr('allowedUserID'),
				$r = $("#result3");
			$r.removeClass('ui-state-error ui-state-highlight ui-state-active').html('');
				
			if (!confirm('This will remove this user.  Are you sure?')) {
				return;
			}
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					// wp ajax action
					action: 'ajax-removeClan',

					// vars
					allowedUserID: allowedUserID,
					clanID: clanID,

					// send the nonce along with the request
					nextNonce: PT_Ajax.nextNonce
				},
				function (response) {
					if (response.success) {
						$r.html(response.message).addClass('ui-state-highlight');
						$("#"+clanID+"_"+allowedUserID).remove();
						
					} else {
						$r.html(response.error).addClass('ui-state-error');
					}
				}
			);
			return false;
		});
		
		function log( message ) {
		  $( "<div>" ).text( message ).prependTo( "#log" );
		  $( "#log" ).scrollTop( 0 );
		}
		
		var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];


/*
	var searchRequest = null;
    
	var searchTerm = 'vet';
	  
	  $( "#opponentInput" ).autocomplete({
      //source: availableTags
	  
		source: function(request, response) {
			if (searchRequest !== null) {
				searchRequest.abort();
			}
		
			searchRequest = $.post(
				PT_Ajax.ajaxurl,
				{
					// wp ajax action
					action: 'ajax-getOpponents',

					// vars
					searchTerm: searchTerm,

					// send the nonce along with the request
					nextNonce: PT_Ajax.nextNonce
				},
				function (data) {
					searchRequest = null;
					response($.map(data.list, function(item) {
						return {
							value: item.name,
							label: item.name
						};
					}));
					console.log(data);
					
				}).fail(function() {
					searchRequest = null;
				})
		}
    });
*/		

var searchRequest = null;
$("#opponentInput").autocomplete({
    maxLength: 5,
    source: function(request, response) {
        if (searchRequest !== null) {
            searchRequest.abort();
        }
        searchRequest = $.ajax({
            url: 'SearchTest.php',
            method: 'post',
            dataType: "json",
            data: {term: request.term},
            success: function(data) {
                searchRequest = null;
                response($.map(data.items, function(item) {
                    return {
                        value: item.name,
                        label: item.name
                    };
                }));
            }
        }).fail(function() {
            searchRequest = null;
        });
    }
});

		$('#testingAjax').click(function () {
			var searchTerm = 'vet';
			
			$.post(
				PT_Ajax.ajaxurl,
				{
					// wp ajax action
					action: 'ajax-getOpponents',

					// vars
					searchTerm: searchTerm,

					// send the nonce along with the request
					nextNonce: PT_Ajax.nextNonce
				},
				function (response) {
					console.log(response);
					
					if (response.success == true) { console.log('yay!'); } else { console.log('bah'); }
					
					if (response.success) {
						console.log(response.list);
						
					} else {
						console.log('error:');console.log(response.error);
					}
				}
			);
			return false;
		});
		
	});
	})(jQuery);
