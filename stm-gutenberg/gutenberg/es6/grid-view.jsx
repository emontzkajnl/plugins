var allCats = [];
var allCatsAutoCompl = [];
var currentCategForAutocomplete = '';
var timeOut = null;

registerBlockType('stm-gutenberg/grid-view', {
    title: 'STM Posts Grid View',
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
        var columns = attributes.columns;

        //view attr
        var showLoadMore = attributes.showLoadMore;
        var showNavigation = attributes.showNavigation;
        var categories = attributes.categories;
        var postsToShow = attributes.postsToShow;
        var offset = attributes.offset;
        var order = attributes.order;
        var orderBy = attributes.orderBy;
        var orderVal = (orderBy + '/' + order).toString();
        var selectCategories = attributes.selectCategories;
        var selectCategoriesId = attributes.selectCategoriesId;
        var selCatSplit = (typeof(selectCategoriesId) != 'undefined') ? jQuery.map(selectCategoriesId.split(','), Number ) : [];

        var marginTop = attributes.margin_top;
        var paddingTop = attributes.padding_top;
        var marginLeft = attributes.margin_left;
        var paddingLeft = attributes.padding_left;
        var marginRight = attributes.margin_right;
        var paddingRight = attributes.padding_right;
        var marginBottom = attributes.margin_bottom;
        var paddingBottom = attributes.padding_bottom;

        if(timeOut != null) {
            clearTimeout(timeOut);
        }

        if( allCats.length == 0 ) {
            var catsList = select.getEntityRecords( "taxonomy", "category", {"per_page": 100} );
            if(catsList != null && catsList.length != 0) {
                allCats = [{label: i18n.__('All'), value: ''}];
                catsList.forEach(function (val, key) {

                    if(selCatSplit.indexOf(val.id) != -1) currentCategForAutocomplete += (currentCategForAutocomplete != '') ? ' | ' + val.name : val.name;
                    allCats.push({label: val.name, value: val.id});
                    allCatsAutoCompl.push({label: val.name, value: val.id, name: val.name, id: val.id});
                });
            }
        } else {
            allCats.forEach( function (val, key) {
                if(selCatSplit.indexOf(val.id) != -1) currentCategForAutocomplete += (currentCategForAutocomplete != '') ? ' | ' + val.name : val.name;
            } );
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

        const ShowNavigationCheckboxControl = withState( {
            isChecked: showNavigation,
        } )( ( { isChecked, setState } ) => (
            <CheckboxControl
                heading={ i18n.__('Show Navigation') }
                label={ i18n.__('Yes') }
                checked={ isChecked }
                onChange={ ( isChecked ) => { setState( { isChecked } ); props.setAttributes( { showNavigation: isChecked } ) } }
            />
        ) );

        timeOut = setTimeout(function () {
            $('.stmt-grid-mosaic').imagesLoaded(function () {
                $('.stmt-grid-mosaic').isotope({
                    itemSelector: '.col',
                    layoutMode: 'packery',
                    packery: {
                        gutter: 0
                    }
                });
            });
        }, 2000);

        function splitOrder(newOrder) {
            var splitOrder = newOrder.split('/');
            props.setAttributes( {orderBy: splitOrder[0]} );
            props.setAttributes( {order: splitOrder[1]} );
        }

        function splitCats (currCats, isTE = false) {

            var catsSelected = '';

            allCatsAutoCompl.forEach(function (val) {
                if(val.id == currCats) {
                    if(!isTE) currentCategForAutocomplete += (currentCategForAutocomplete != '') ? ' | ' + val.label : val.label;
                }
            });

            var arr = currentCategForAutocomplete.split(' | ');

               allCats.forEach( function (val, key) {
                   if(arr.includes(val.label)) catsSelected += (catsSelected == '') ? val.value : ',' + val.value;
               } );

            props.setAttributes( { selectCategoriesId: catsSelected } );
            props.setAttributes( { selectCategories: currentCategForAutocomplete } );

        }

        return [
            <ServerSideRender block='stm-gutenberg/grid-view' attributes={props.attributes} />,
            <InspectorControls key='inspector'>
                <PanelBody title={ i18n.__( 'Block Header Settings' ) } className="stm-posts-header-settings" initialOpen={ false }>
                    <TextControl type='text' label={ i18n.__('Title') } value={ title } onChange={ (newTitle) => props.setAttributes( { title: newTitle } ) }/>
                    <SelectControl label={ i18n.__( 'Select Heading Tag' ) } value={ headingTag } options={ headingTags } onChange={ (selected) => props.setAttributes( { headingTag: selected } ) }/>
                    <TextControl type='text' label={ i18n.__( 'Custom Heading Font Size (14, 16, 17 ...)' ) } value={ headingCFS } onChange={ (cfs) => props.setAttributes( { headingCFS: cfs } ) }/>
                    <SelectControl label={ i18n.__( 'Header Style' ) } value={ headerStyle } options={ headerStyles } onChange={ (selectedStyle) => props.setAttributes( { headerStyle: selectedStyle } ) }/>
                </PanelBody>
                <PanelBody title={ i18n.__( 'View Settings' ) } className="stm-posts-view-settings" initialOpen={ false }>
                    <SelectControl label={ i18n.__( 'Content Width' ) } value={ contWidth } options={ contentWidth } onChange={ (newContWidth) => props.setAttributes( { contWidth: newContWidth } ) } />
                    <SelectControl label={ i18n.__( 'View Style' ) } value={ viewStyle } options={ viewStylesGridView } onChange={ (selectedStyle) => props.setAttributes( { viewStyle: selectedStyle } ) }/>
                    <SelectControl label={ i18n.__( 'Columns' ) } value={ columns } options={ numColumns } onChange={ (cols) => props.setAttributes( { columns: cols } ) }/>
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
                    <div className='catAutocomplWrap'>
                        <p></p>
                        <SelectControl label={ i18n.__( 'Select Category For Tabs' ) }  options={ allCatsAutoCompl } onChange={ (newCat) => splitCats( newCat ) }/>
                        <TextareaControl type='text' label='' value={ currentCategForAutocomplete } onChange={ ( newText ) => { currentCategForAutocomplete = newText, splitCats(newText, true) } }/>
                        <p></p>
                    </div>
                    <TextControl type='text' label={ i18n.__( 'Limit post number' ) } value={ postsToShow } onChange={ (newPostsToShow) => props.setAttributes( { postsToShow: newPostsToShow } ) }/>
                    <TextControl type='text' label={ i18n.__( 'Offset posts' ) } value={ offset } onChange={ (newOffset) => props.setAttributes( { offset: newOffset } ) }/>
                    <SelectControl label={ i18n.__( 'Order' ) } value={ orderVal } options={ orderParams } onChange={ (newOrder) => splitOrder(newOrder) }/>
                    <ShowLoadMoreCheckboxControl />
                    <ShowNavigationCheckboxControl />
                </PanelBody>
            </InspectorControls>
        ];

    },

    save: function (props) {
        return null;
    },
});