registerBlockType('stm-gutenberg/widget-category-list', {
    title: 'STM Widget Category List',
    icon: 'universal-access-alt',
    category: 'widgets',
    edit (props) {
        var attributes = props.attributes;

        //Header attr
        var title = attributes.title;
        var headingTag = attributes.headingTag;
        var headingCFS = attributes.headingCFS;
        var headerStyle = attributes.headerStyle;
        var showCount = attributes.showCount;
        var hideEmpty = attributes.hideEmpty;

        var viewStyle = attributes.viewStyle;

        var maxNums = attributes.max_nums;

        var marginTop = props.attributes.margin_top;
        var paddingTop = props.attributes.padding_top;
        var marginLeft = props.attributes.margin_left;
        var paddingLeft = props.attributes.padding_left;
        var marginRight = props.attributes.margin_right;
        var paddingRight = props.attributes.padding_right;
        var marginBottom = props.attributes.margin_bottom;
        var paddingBottom = props.attributes.padding_bottom;

        const ShowCountCheckboxControl = withState( {
            isChecked: showCount,
        } )( ( { isChecked, setState } ) => (
            <CheckboxControl
                heading={ i18n.__('Show Count') }
                label={ i18n.__('Yes') }
                checked={ isChecked }
                onChange={ ( isChecked ) => { setState( { isChecked } ); props.setAttributes( { showCount: isChecked } ) } }
            />
        ) );

        const HideEmptyCheckboxControl = withState( {
            isChecked: hideEmpty,
        } )( ( { isChecked, setState } ) => (
            <CheckboxControl
                heading={ i18n.__('Hide empty') }
                label={ i18n.__('Yes') }
                checked={ isChecked }
                onChange={ ( isChecked ) => { setState( { isChecked } );  props.setAttributes( { hideEmpty: isChecked } ) } }
            />
        ) );

        return[
            <ServerSideRender block="stm-gutenberg/widget-category-list" attributes={props.attributes}/>,
            <InspectorControls key="inspector">
                <PanelBody title={ i18n.__( 'Block Header Settings' ) } className="stm-posts-header-settings" initialOpen={ false }>
                    <TextControl type='text' label={ i18n.__('Title') } value={ title } onChange={ (newTitle) => props.setAttributes( { title: newTitle } ) }/>
                    <SelectControl label={ i18n.__( 'Select Heading Tag' ) } value={ headingTag } options={ headingTags } onChange={ (selected) => props.setAttributes( { headingTag: selected } ) }/>
                    <TextControl type='text' label={ i18n.__( 'Custom Heading Font Size (14, 16, 17 ...)' ) } value={ headingCFS } onChange={ (cfs) => props.setAttributes( { headingCFS: cfs } ) }/>
                    <SelectControl label={ i18n.__( 'Header Style' ) } value={ headerStyle } options={ headerStyles } onChange={ (selectedStyle) => props.setAttributes( { headerStyle: selectedStyle } ) }/>
                </PanelBody>
                <PanelBody title={ i18n.__( 'View Settings' ) } className='stm-posts-view-settings' initialOpen={ false }>
                    <SelectControl label={ i18n.__( 'View Style' ) } value={ viewStyle } options={ categoryStyles } onChange={ (selectedStyle) => props.setAttributes( { viewStyle: selectedStyle } ) }/>
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
                <PanelBody title={ i18n.__( 'Categories Settings' ) } className='stm-categories-settings' initialOpen={ false }>
                    <TextControl type='text' label={ i18n.__('Max Number') } value={ maxNums } onChange={ (newNum) => props.setAttributes( { max_nums: newNum } ) }/>
                    <ShowCountCheckboxControl />
                    <HideEmptyCheckboxControl />
                </PanelBody>
            </InspectorControls>
        ];
    },
    save () {
        return null;
    }
});