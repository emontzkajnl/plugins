( function ( $, wp ) {
	'use strict';

	let overlay, searchInput, modalLoaded = false;

	$( document ).on( 'click', 'a[href="#modal-gam-ad-search"]', function ( ev ) {
		ev.stopPropagation();
		ev.preventDefault();
		if ( jQuery( this ).hasClass( 'disabled' ) ) {
			return;
		}
		document.location.href = '#modal-gam-ad-search';
		setTimeout( function () {
			searchInput.focus();
		}, 500 );
	} );

	$( document ).on( 'keyup', '#advads-gam-search-input', function () {
		$( '#advads-gam-search-button' ).prop( 'disabled', searchInput.val().trim().length < 2 );
	} );

	$( document ).on( 'submit', '#advads-gam-search', function () {
		searchAds( searchInput.val() );
		return false;
	} );

	$( document ).on( 'click', '#modal-gam-ad-search .advads-modal-close-background, #modal-gam-ad-search .advads-modal-close ', function ( ev ) {
		ev.preventDefault();
		closeModal();
	} );

	$( document ).on( 'click', '#gam-search-load-results', function () {
		const ads = collectAdToLoad();
		if ( ads.length ) {
			appendAdUnits( ads );
		}
	} );

	$( document ).on( 'click', '#advanced-ad-type-gam', function () {
		if ( ! modalLoaded ) {
			loadModal();
		}
	} );

	/**
	 * Get ad data to be added to the ad unit list variable.
	 *
	 * @returns {Array}
	 */
	function collectAdToLoad() {
		let ads = [];
		$( '#modal-gam-ad-search tbody input[name="gam-unit-found\[\]"]:checked' ).each( function () {
			ads.push( AAGAM.base64Decode( $( this ).val() ) );
		} );
		return ads;
	}

	/**
	 * Append (or update) ad units to the ad unit list variable and in the database.
	 *
	 * @param {string[]} ads the ad units to be added in JSON string format.
	 */
	function appendAdUnits( ads ) {
		const overlay = $( '#gam-ad-search-overlay' ), self = this;
		overlay.show();
		const payload = {
			nonce: overlay.attr( 'data-nonce' ),
			units: ads
		};
		wp.ajax.send( 'advads_gamapi_append_ads', {
			data:    payload,
			success: function ( data ) {
				closeModal();
				gamAdvancedAdsJS.adUnitList = data.units;
				const adNotFound            = $( '#advads-gam-adunit-not-found' );
				if ( adNotFound.length ) {
					const adsById = getAdUnitListById();
					if ( typeof adsById[adNotFound.attr( 'data-id' )] !== 'undefined' ) {
						adNotFound.remove();
					}
				}
				window.AdvancedAdsGamGetAdList().renderAdList();
			},
			error:   function ( response ) {
				closeModal();
				console.error( response );
			}
		} );
	}

	/**
	 * Close the modal frame.
	 */
	function closeModal() {
		$( '#advads-gam-search-input' ).val( '' );
		$( '#advads-gam-search-button' ).prop( 'disabled', true );
		$( '#modal-gam-ad-search .advads-modal-footer' ).html( ( wp.template( 'gam-ad-search-footer' ) )( {action: 'init'} ) );
		$( '#modal-gam-ad-search .advads-modal-body' ).empty();
		overlay.hide();
		document.location.hash = '#close';
	}

	/**
	 * Search ads by name on the GAM account.
	 *
	 * @param {string} search the partial name to search for.
	 */
	function searchAds( search ) {
		if ( search.trim().length < 2 ) {
			return;
		}
		overlay.show();
		const payload = {
			nonce:  overlay.attr( 'data-nonce' ),
			search: search
		};
		wp.ajax.send( 'advads_gamapi_search_ads', {
			data:    payload,
			success: function ( data ) {
				overlay.hide();
				let unitsFound = [], adList = getAdUnitListById( gamAdvancedAdsJS.adUnitList );
				for ( const id in data.results.units ) {
					unitsFound.push( {
						id:          id,
						data:        window.AAGAM.base64Encode( JSON.stringify( data.results.units[id], false, false ) ),
						name:        data.results.units[id].name,
						description: data.results.units[id].description,
						inList:      typeof adList[id] !== 'undefined'
					} );
				}
				$( '#modal-gam-ad-search .advads-modal-body' ).html( ( wp.template( 'gam-ad-search-results' ) )( {units: unitsFound} ) );
				let footerMarkup = ( wp.template( 'gam-ad-search-footer' ) )( {action: 'init'} );

				if ( data.results.count !== 0 ) {
					let unitsInlist = 0;
					for ( let ad of gamAdvancedAdsJS.adUnitList ) {
						if ( typeof data.results.units[ad.id] !== 'undefined' ) {
							unitsInlist ++;
						}
					}
					footerMarkup = ( wp.template( 'gam-ad-search-footer' ) )( {action: unitsInlist === data.results.count ? 'imported' : 'load'} );
				}

				$( '#modal-gam-ad-search .advads-modal-footer' ).html( footerMarkup );
			},
			error:   function ( response ) {
				overlay.hide();
				console.error( response );
			}
		} );
	}

	/**
	 * Get the ad unit list indexed by ad slot ID.
	 *
	 * @returns {Object}
	 */
	function getAdUnitListById() {
		let list = {};
		for ( const ad of gamAdvancedAdsJS.adUnitList ) {
			list[ad.id] = ad;
		}
		return list;
	}

	/**
	 * Retrieve modal frame markup.
	 */
	function loadModal() {
		wp.ajax.send(
			'advads_load_gam_search',
			{
				data:    {
					nonce: $( '#gam-ad-search-nonce' ).val(),
				},
				success: function ( response ) {
					modalLoaded = true;
					$( '#wpwrap' ).append( response.markup ).append( '<style type="text/css">a[href="#modal-gam-ad-search"] {visibility: visible ! important;}</style>' );
					$( '#modal-gam-ad-search .advads-modal-header' ).html( ( wp.template( 'gam-ad-search-head' ) )() );
					$( '#modal-gam-ad-search .advads-modal-content' ).append( ( wp.template( 'gam-ad-search-overlay' ) )() );
					$( '#modal-gam-ad-search .advads-modal-footer' ).html( ( wp.template( 'gam-ad-search-footer' ) )( {action: 'init'} ) );
					overlay     = $( '#gam-ad-search-overlay' );
					searchInput = $( '#advads-gam-search-input' );
				},
				error:   function ( error ) {
					console.error( error );
				}
			}
		);
	}

	$( function () {
		// On DOM ready.
		if ( $( '#advanced-ad-type-gam' ).prop( 'checked' ) ) {
			loadModal();
		}
	} );
} )( window.jQuery, window.wp );
