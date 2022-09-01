(function($){
	"use strict";

	function disable() {
		$( 'input,select,button,textarea' ).prop( 'disabled', true );
	}

	function enable() {
		$( 'input,select,button,textarea' ).prop( 'disabled', false );
	}

	function getSpinnerCode() {
		return '<img alt="" class="dbop-spinner" src="' + advadsTrackingDbopVars.adminImageUrl + 'spinner.gif" />';
	}

	// export stats data
	$( document ).on( 'submit', '#export-stats-form', function( ev ) {
		ev.preventDefault();
		var period = $( this ).find( '.advads-period' ).val();

		if ( period === 'custom' ) {
			var from = $( this ).find( '.advads-from' ).val();
			var to   = $( this ).find( '.advads-to' ).val();

			if ( ! $.advadsIsConsistentPeriod( from, to ) ) {
				$( '#export-period-error' ).show();
				return false;
			}
		}
		$( '#export-period-error' ).hide();
		var url = ajaxurl + '?action=advads_tracking_export&period=' + period + '&nonce=' + advadsTrackingDbopVars.nonce;
		if ( undefined !== to ) {
			url += '&from=' + from + '&to=' + to;
		}
		$( '#stats-download-frame' ).attr( 'src', url );
	} );

	// remove stats
	$( document ).on( 'submit', '#remove-stats-form', function( ev ) {
		ev.preventDefault();
		var period = $( this ).find( '.advads-period' ).val();

		var formData = {
			nonce: advadsTrackingDbopVars.nonce,
			action: 'advads_tracking_remove',
			period: period,
		};
		$( this ).find( '.button' ).after( $( getSpinnerCode() ) );
		disable();

		$.ajax( {
			type: 'POST',
			url: ajaxurl,
			data: formData,
			success: function ( resp, textStatus, XHR ) {
				$( '.dbop-spinner' ).remove();
				if ( undefined !== resp.status && resp.status ) {
					if ( undefined !== resp['alt-msg'] ) {
						$( '#remove-error-notice' ).text( trackingDbopLocale.optimizeFailure );
						enable();
					} else {
						$( '#remove-error-notice' ).empty();
						location.reload();
					}
				} else {
					enable();
					$( '#remove-error-notice' ).text( trackingDbopLocale.SQLFailure );
					if ( undefined !== resp.msg ) {
						console.log( resp.msg );
					}
				}
			},
			error: function ( request, textStatus, err ) {
				$( '.dbop-spinner' ).remove();
				enable();
				console.log( request );
				alert( trackingDbopLocale.serverFail );
			}
		} );

	} );

	$( document ).on( 'submit', '#debug-mode-form', function ( ev ) {
		ev.preventDefault();
		$( this ).find( '.button' ).after( $( getSpinnerCode() ) );
		disable();
		wp.ajax.send( 'advads_tracking_debug_mode', {
			data: {
				nonce: advadsTrackingDbopVars.nonce,
				ad:    $( '#debug-mode-adID' ).val()
			}
		} )
		  .done( function () {
			  location.reload();
		  } )
		  .fail( function ( response ) {
			  $( '.widefat' ).before( '<div class="error"><p>' + response.responseJSON.data.message + '</p></div>' );
		  } )
		  .always( function ( response ) {
			  $( '.dbop-spinner' ).remove();
			  enable();
			  console.log( response );
		  } );
	} );

	$( document ).on( 'submit', '#reset-stats-form', function ( ev ) {
		ev.preventDefault();
		var ad = $( '#reset-stats-adID' ).val();
		if ( '' == ad ) {
			$( '#reset-error-notice' ).text( trackingDbopLocale.resetNoAd );
		} else {
			$( '#reset-error-notice' ).empty();
			var adName    = $( '#reset-stats-adID option:selected' ).text();
			var reconfirm = confirm( trackingDbopLocale.resetConfirm + ' ' + adName );
			if ( reconfirm ) {
				var formData = {
					nonce: advadsTrackingDbopVars.nonce,
					action: 'advads_tracking_reset',
					ad: ad,
				};
				$( this ).find( '.button' ).after( $( getSpinnerCode() ) );
				disable();
				$.ajax( {
					type: 'POST',
					url: ajaxurl,
					data: formData,
					success: function ( resp ) {
						var $errorNotice = $( '#reset-error-notice' );
						if (typeof resp.data !== 'undefined') {
							resp = resp.data;
						}
						$( '.dbop-spinner' ).remove();
						if ( undefined !== resp.status && resp.status ) {
							$errorNotice.empty();
							if ( typeof resp.redirect !== 'undefined' ) {
								window.location.href = resp.redirect;
							} else {
								window.location.reload();
							}
						} else {
							enable();
							$errorNotice.html( trackingDbopLocale.SQLFailure );
							if ( undefined !== resp.msg ) {
								$errorNotice.html( $errorNotice.text() + ":<br>" + resp.msg );
								console.log( resp.msg );
							}
						}
					},
					error: function ( request ) {
						$( '.dbop-spinner' ).remove();
						enable();
						console.log( request );
						alert( trackingDbopLocale.serverFail );
					}
				} );

			}
		}
	} );

})( jQuery );
