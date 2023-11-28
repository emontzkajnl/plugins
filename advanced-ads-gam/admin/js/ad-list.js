( function ( $, wp ) {
	'use strict';

	/**
	 * Maximum amount of ads to show in the ad list at the same time. Scroll past that amount.
	 *
	 * @type {number}
	 */
	const TABLE_MAX_ROWS = 10;
	const adList         = function () {
		this.adContentInput = $( 'input[name="advanced_ad\[content\]"]' );
	};
	let listInstance;

	adList.prototype = {
		constructor: adList,

		/**
		 * Get the ad unit data saved in post content;
		 *
		 * @returns {(Object|null)}
		 */
		getSavedAd() {
			return window.AAGAM.jsonParse( window.AAGAM.base64Decode( this.adContentInput.val() ) );
		},

		/**
		 * Render the body of the ad list table
		 */
		renderAdList: function() {
			$( '#advads-gam-table tbody' ).html( ( wp.template( 'gam-ad-list-rows' ) )( gamAdvancedAdsJS.adUnitList ) );
			window.AdvancedAdsGamGetAdSizes().loadAdSizes();
			this.updateSelected();
			this.updateTableStyle();
		},

		/**
		 * Update the ad list styling when a row is selected
		 */
		updateSelected: function () {
			if ( ! $( '#advads-gam-table tbody tr[data-unitid]' ).length ) {
				$( '#advads-gam-primary-search-button' ).show();
				$( '.advads-gam-secondary-search-button' ).hide();
			} else {
				$( '#advads-gam-primary-search-button' ).hide();
				$( '.advads-gam-secondary-search-button' ).show();
			}

			let adData = this.adContentInput.val();

			if ( ! adData ) {
				adData = $( '#advads-gam-table tr.selected' ).attr( 'data-unitdata' );
			}

			if ( adData ) {
				const parsed = window.AAGAM.jsonParse( window.AAGAM.base64Decode( adData ) );

				if ( parsed && typeof parsed.networkCode !== 'undefined' && typeof parsed.id !== 'undefined' ) {
					const theRow = $( '#advads-gam-table tbody tr[data-unitid="' + parsed.networkCode + '_' + parsed.id + '"]' );

					if ( theRow.length ) {
						$( '#advads-gam-adunit-not-found' ).remove();
						theRow.addClass( 'selected' );

						if ( theRow.attr( 'data-unitdata' ) === adData && ! theRow.hasClass( 'changed' ) ) {
							$( '#advads-gam-current-unit-updated' ).addClass( 'hidden' );
						} else {
							theRow.find( 'td:first-of-type' ).html( '<span class="advads-notice-inline advads-error">' + theRow.find( 'td:first-of-type' ).text() + '</span>' );
							$( '#advads-gam-current-unit-updated' ).removeClass( 'hidden' );
						}
					} else if ( $( '#tmpl-gam-unit-not-in-list' ).length && parsed.networkCode === gamAdvancedAdsJS.networkCode && ! $( '#advads-gam-adunit-not-found' ).length ) {
						$( '#advads-gam-table' ).after( ( wp.template( 'gam-unit-not-in-list' ) )( {
							unitId:   parsed.networkCode + '_' + parsed.id,
							unitName: parsed.name
						} ) );
					}

					if ( theRow.attr( 'data-unitdata' ) && wp.template ) {
						const adSizes = window.AdvancedAdsGamGetAdSizes();
						adSizes.loadAdSizes( adSizes.getAdUnitSizes( parsed ), true );
					}
				}
			}
			this.prependSelected();
		},

		/**
		 * Remove one single ad unit from the list.
		 *
		 * @param {string} id the ad unit ID.
		 */
		removeAdUnit( id ) {
			const overlay = $( '#advads-gam-ads-list-overlay' );
			overlay.show();
			const payload = {
				nonce: $( '#advads-gam-table' ).attr( 'data-nonce' ),
				id:    id
			};
			wp.ajax.send( 'advads_gamapi_remove_ad', {
				data:    payload,
				success: function ( data ) {
					overlay.hide();
					if ( data.ad_units ) {
						gamAdvancedAdsJS.adUnitList = data.ad_units;
						window.AdvancedAdsGamGetAdList().renderAdList();
					}
				},
				error:   function ( response ) {
					overlay.hide();
					console.error( response );
				}
			} );
		},

		/**
		 * Update data on a single ad unit.
		 *
		 * @param {string} id the ad unit ID.
		 */
		updateSingleAd( id ) {
			const overlay = $( '#advads-gam-ads-list-overlay' ).show();
			const payload = {
				nonce: $( '#advads-gam-table' ).attr( 'data-nonce' ),
				id:    id
			};
			wp.ajax.send( 'advads_gamapi_update_single_ad', {
				data: payload,
				success: function ( data ) {
					overlay.hide();
					gamAdvancedAdsJS.adUnitList = data.ad_units;
					window.AdvancedAdsGamGetAdList().renderAdList();
				},
				error:   function ( response ) {
					overlay.hide();
					console.error( response );
				}
			} );
		},

		/**
		 * Toggle visibility of the (ajax) loading overlay.
		 *
		 * @param {Boolean} [show=true] display the overlay if true
		 */
		loadingOverlay( show ) {
			if ( typeof show === 'undefined' ) {
				show = true;
			}

			$( '#advads-gam-ads-list-overlay' ).css( 'display', show ? 'block' : 'none' );
		},

		/**
		 * Use a <div /> node as table header when there are more than TABLE_MAX_ROWS ads in the ad list
		 */
		updateTableStyle() {
			const tableRowsCount = $( '#advads-gam-table tbody tr' ).length;

			if ( tableRowsCount < 2 ) {
				return;
			}

			$( '#advads-gam-table tbody' ).height( tableRowsCount > TABLE_MAX_ROWS ? 480 : 'auto' );
		},

		/**
		 * Ensure that the selected ad unit is always at the top of the list
		 */
		prependSelected() {
			const theRow = jQuery( '#advads-gam-table tbody tr.selected,#advads-gam-table tbody tr.changed' );
			if ( theRow.length ) {
				theRow.parent().prepend( theRow );
				const body = jQuery( '#advads-gam-table tbody' );
				if ( body.get( 0 ).scrollHeight !== body.height ) {
					body.scrollTop( 0 );
				}
			}
		}
	};

	/**
	 * Returns the unique adList instance
	 *
	 * @returns {adList}
	 */
	window.AdvancedAdsGamGetAdList = function () {
		if ( typeof listInstance === 'undefined' ) {
			listInstance = new adList();
		}
		return listInstance;
	};

} )( window.jQuery, window.wp );
