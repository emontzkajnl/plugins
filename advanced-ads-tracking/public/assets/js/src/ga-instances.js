/**
 * Stores Google Analytics tracking script instances
 */
var advancedAdsGAInstances = {
	instances: [],

	/**
	 * Return instance for given blog id, creates and stores instance if it doesn't exists.
	 *
	 * @param {integer} bId
	 * @return {AdvAdsGATracker}
	 */
	getInstance: function ( bId ) {
		if ( typeof this.instances[bId] !== 'undefined' ) {
			return this.instances[bId];
		}
		var instance = new AdvAdsGATracker( bId, advads_gatracking_uids[bId] );
		this.store( bId, instance );

		return instance;
	},

	/**
	 * Stores instance for given blog id.
	 *
	 * @param {integer} bId
	 * @param {AdvAdsGATracker} instance
	 */
	store: function ( bId, instance ) {
		this.instances[bId] = instance;
	}
};
