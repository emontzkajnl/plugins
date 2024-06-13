registerBlockType('stm-gutenberg/video-format', {
    title: 'STM Posts Video Format',
    icon: 'universal-access-alt',
    category: 'stm_blocks',

    edit: function (props) {

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
        var categories = props.attributes.categories;
        var postsToShow = props.attributes.postsToShow;
        var offset = props.attributes.offset;
        var order = props.attributes.order;
        var orderBy = props.attributes.orderBy;
        var orderVal = (orderBy + '/' + order).toString();

        var bgColor = props.attributes.bgColor;

        var marginTop = props.attributes.margin_top;
        var paddingTop = props.attributes.padding_top;
        var marginLeft = props.attributes.margin_left;
        var paddingLeft = props.attributes.padding_left;
        var marginRight = props.attributes.margin_right;
        var paddingRight = props.attributes.padding_right;
        var marginBottom = props.attributes.margin_bottom;
        var paddingBottom = props.attributes.padding_bottom;

        return [
            <ServerSideRender block="stm-gutenberg/video-format" attributes={ props.attributes }/>,
            <InspectorControls key="inspector">
                <PanelBody title={ i18n.__( 'Block Header Settings' ) } className="stm-posts-header-settings" initialOpen={ false }>
                    <TextControl type='text' label={ i18n.__('Title') } value={ title } onChange={ (newTitle) => props.setAttributes( { title: newTitle } ) }/>
                    <SelectControl label={ i18n.__( 'Select Heading Tag' ) } value={ headingTag } options={ headingTags } onChange={ (selected) => props.setAttributes( { headingTag: selected } ) }/>
                    <TextControl type='text' label={ i18n.__( 'Custom Heading Font Size (14, 16, 17 ...)' ) } value={ headingCFS } onChange={ (cfs) => props.setAttributes( { headingCFS: cfs } ) }/>
                    <SelectControl label={ i18n.__( 'Header Style' ) } value={ headerStyle } options={ headerStyles } onChange={ (selectedStyle) => props.setAttributes( { headerStyle: selectedStyle } ) }/>
                </PanelBody>
                <PanelBody title={ i18n.__( 'View Settings' ) } className="stm-posts-view-settings" initialOpen={ false }>
                    <SelectControl label={ i18n.__( 'Content Width' ) } value={ contWidth } options={ contentWidth } onChange={ (newContWidth) => props.setAttributes( { contWidth: newContWidth } ) } />
                    <SelectControl label={ i18n.__( 'View Style' ) } value={ viewStyle } options={ viewStylesVideoPosts } onChange={ (selectedStyle) => props.setAttributes( { viewStyle: selectedStyle } ) }/>
                    <SelectControl label={ i18n.__( 'Columns' ) } value={ columns } options={ numColumns } onChange={ (cols) => props.setAttributes( { columns: cols } ) }/>
                    <div>
                        {i18n.__( 'Background Color' )}
                        <div className="stm_gutenberg_flex_left_center stm_admin_color_palette">
                            <ColorIndicator colorValue={ bgColor }/>
                            <ColorPalette value={ bgColor } onChange={ ( newColor ) => props.setAttributes( { bgColor: newColor } ) } />
                        </div>
                    </div>
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
                    <TextControl type='text' label={ i18n.__( 'Limit post number' ) } value={ postsToShow } onChange={ (newPostsToShow) => props.setAttributes( { postsToShow: newPostsToShow } ) }/>
                    <TextControl type='text' label={ i18n.__( 'Offset posts' ) } value={ offset } onChange={ (newOffset) => props.setAttributes( { offset: newOffset } ) }/>
                    <SelectControl label={ i18n.__( 'Order' ) } value={ orderVal } options={ orderParams } onChange={ (newOrder) => splitOrder(newOrder) }/>
                </PanelBody>
            </InspectorControls>
        ];

    },

    save: function (props) {
        return null;
    },
});