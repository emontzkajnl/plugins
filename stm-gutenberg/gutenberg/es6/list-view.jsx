var allCats = [];

registerBlockType ('stm-gutenberg/list-view', {
    title: 'STM Posts List View',
    icon: 'universal-access-alt',
    category: 'stm_blocks',

    edit: function (props) {

        var select = wp.data.select( 'core' );

        var attributes = props.attributes;

        //Header attr
        var title = attributes.title;
        var headingTag = attributes.headingTag;
        var headingCFS = attributes.headingCFS;
        var headerStyle = attributes.headerStyle;

        //block attr
        var contWidth = attributes.contWidth;
        var viewStyle = attributes.viewStyle;

        var marginTop = attributes.margin_top;
        var paddingTop = attributes.padding_top;
        var marginLeft = attributes.margin_left;
        var paddingLeft = attributes.padding_left;
        var marginRight = attributes.margin_right;
        var paddingRight = attributes.padding_right;
        var marginBottom = attributes.margin_bottom;
        var paddingBottom = attributes.padding_bottom;

        //view attr
        var showLoadMore = attributes.showLoadMore;
        var categories = props.attributes.categories;
        var postsToShow = props.attributes.postsToShow;
        var offset = props.attributes.offset;
        var order = props.attributes.order;
        var orderBy = props.attributes.orderBy;
        var orderVal = (orderBy + '/' + order).toString();

        if( allCats.length == 0 ) {
            var catsList = select.getEntityRecords( "taxonomy", "category", {"per_page": 100} );
            if(catsList != null && catsList.length != 0) {
                allCats = [{label: i18n.__('All'), value: ''}];
                catsList.forEach(function (val, key) {
                    allCats.push({label: val.name, value: val.id});
                });
            }
        }

        const ShowLoadMoreCheckboxControl = withState( {
            isChecked: showLoadMore,
        } )( ( { isChecked, setState } ) => (
            <CheckboxControl
                heading={ i18n.__('Show Load More Button') }
                label={ i18n.__('Yes') }
                checked={ isChecked }
                onChange={ ( isChecked ) => { setState( { isChecked } ); props.setAttributes( { showLoadMore: isChecked } ) } }
            />
        ) );

        function splitOrder(newOrder) {
            var splitOrder = newOrder.split('/');
            props.setAttributes( {orderBy: splitOrder[0]} );
            props.setAttributes( {order: splitOrder[1]} );
        }

        return [
            <ServerSideRender block="stm-gutenberg/list-view" attributes={props.attributes}/>,
            <InspectorControls key="inspector">
                <PanelBody title={ i18n.__( 'Block Header Settings' ) } className="stm-posts-header-settings" initialOpen={ false }>
                    <TextControl type='text' label={ i18n.__('Title') } value={ title } onChange={ (newTitle) => props.setAttributes( { title: newTitle } ) }/>
                    <SelectControl label={ i18n.__( 'Select Heading Tag' ) } value={ headingTag } options={ headingTags } onChange={ (selected) => props.setAttributes( { headingTag: selected } ) }/>
                    <TextControl type='text' label={ i18n.__( 'Custom Heading Font Size (14, 16, 17 ...)' ) } value={ headingCFS } onChange={ (cfs) => props.setAttributes( { headingCFS: cfs } ) }/>
                    <SelectControl label={ i18n.__( 'Header Style' ) } value={ headerStyle } options={ headerStyles } onChange={ (selectedStyle) => props.setAttributes( { headerStyle: selectedStyle } ) }/>
                </PanelBody>
                <PanelBody title={ i18n.__( 'View Settings' ) } className="stm-posts-view-settings" initialOpen={ false }>
                    <SelectControl label={ i18n.__( 'Content Width' ) } value={ contWidth } options={ contentWidth } onChange={ (newContWidth) => props.setAttributes( { contWidth: newContWidth } ) } />
                    <SelectControl label={ i18n.__( 'View Style' ) } value={ viewStyle } options={ viewStylesListView } onChange={ (selectedStyle) => props.setAttributes( { viewStyle: selectedStyle } ) }/>
                    <div>CSS Box</div>
                    <div className="custom-row">
                        <div className='mr'>Margin</div>
                        <div className='mr'></div>
                        <div>
                            <TextControl type='text' value={marginTop} onChange={ ( newMarginTop ) => props.setAttributes( { margin_top: newMarginTop } ) }/>
                        </div>
                        <div className='mr'></div>
                        <div className='mr'></div>

                        <div className='mr'></div>
                        <div className='pd'>Padding</div>
                        <div>
                            <TextControl type='text' value={paddingTop} onChange={ ( newPaddingTop ) => props.setAttributes( { padding_top: newPaddingTop } ) }/>
                        </div>
                        <div className='pd'></div>
                        <div className='mr'></div>

                        <div><TextControl type='text' value={marginLeft} onChange={ ( newMarginLeft ) => props.setAttributes( { margin_left: newMarginLeft } ) }/></div>
                        <div><TextControl type='text' value={paddingLeft} onChange={ ( newPaddingLeft ) => props.setAttributes( { padding_left: newPaddingLeft } ) }/></div>
                        <div></div>
                        <div><TextControl type='text' value={paddingRight} onChange={ ( newPaddingRight ) => props.setAttributes( { padding_right: newPaddingRight } ) }/></div>
                        <div><TextControl type='text' value={marginRight} onChange={ ( newMarginRight ) => props.setAttributes( { margin_right: newMarginRight } ) }/></div>

                        <div className='mr'></div>
                        <div className='pd'></div>
                        <div>
                            <TextControl type='text' value={paddingBottom} onChange={ ( newPaddingBottom ) => props.setAttributes( { padding_bottom: newPaddingBottom } ) }/>
                        </div>
                        <div className='pd'></div>
                        <div className='mr'></div>

                        <div className='mr'></div>
                        <div className='mr'></div>
                        <div>
                            <TextControl type='text' value={marginBottom} onChange={ ( newMarginBottom ) => props.setAttributes( { margin_bottom: newMarginBottom } ) }/>
                        </div>
                        <div className='mr'></div>
                        <div className='mr'></div>
                    </div>
                </PanelBody>
                <PanelBody title={ i18n.__( 'Posts Settings' ) } className="stm-list-view" initialOpen={ true }>
                    <SelectControl label={ i18n.__( 'Categories' ) } value={ categories } options={ allCats } onChange={ (newState) => props.setAttributes( { categories: newState } ) } />
                    <TextControl type='text' label={ i18n.__( 'Limit post number' ) } value={ postsToShow } onChange={ (newPostsToShow) => props.setAttributes( { postsToShow: newPostsToShow } ) }/>
                    <TextControl type='text' label={ i18n.__( 'Offset posts' ) } value={ offset } onChange={ (newOffset) => props.setAttributes( { offset: newOffset } ) }/>
                    <SelectControl label={ i18n.__( 'Order' ) } value={ orderVal } options={ orderParams } onChange={ (newOrder) => splitOrder(newOrder) }/>
                    <ShowLoadMoreCheckboxControl />
                </PanelBody>
            </InspectorControls>
        ];
    },

    save: function (props) {
        return null;
    },
});