( function( editor, components, i18n, element ) {

    //blocks
    var registerBlockType = wp.blocks.registerBlockType;

    //element
    var el = wp.element.createElement;

    //editor
    var BlockControls = wp.editor.BlockControls,
        AlignmentToolbar = wp.editor.AlignmentToolbar,
        InspectorControls = wp.editor.InspectorControls;

    //data
    var select = wp.data.select( 'core' );

    //Compose
    var _compose = wp.compose;

    //Components
    var ServerSideRender = wp.components.ServerSideRender,
        Disabled = wp.components.Disabled,
        TextControl = wp.components.TextControl,
        SelectControl = wp.components.SelectControl,
        Button = wp.components.Button,
        Autocomplete = wp.components.Autocomplete,
        CheckBoxControl = wp.components.CheckboxControl,
        ColorPalette = wp.components.ColorPalette,
        ColorIndicator = wp.components.ColorIndicator;

    var allCats = [];
    var allPosts = [];
    var imgUrl = '';

    registerBlockType('stm-gutenberg/single-post', {
        title: 'STM Single Post',
        icon: 'universal-access-alt',
        category: 'stm_blocks',

        edit: function (props) {

            var attributes = props.attributes;


            //header attr
            var title = attributes.title;
            var headerStyle = attributes.headerStyle;
            var headingTag = attributes.headingTag;
            var headingCFS = attributes.headingCFS;

            //block attr
            var contWidth = attributes.contWidth;
            var viewStyle = attributes.viewStyle;
            var bgColor = attributes.bgColor;
            var useFeatImgBG = attributes.useFeatImgBG;
            var alignment = attributes.alignment;

            //view attr
            var categories = props.attributes.categories;
            var postOrder = props.attributes.postOrder;
            var postOffset = props.attributes.postOffset;
            var postId = props.attributes.postId;
            var onlySticky = props.attributes.only_sticky;
            var currentPostForAutocomplete = '';

            var marginTop = props.attributes.margin_top;
            var paddingTop = props.attributes.padding_top;
            var marginLeft = props.attributes.margin_left;
            var paddingLeft = props.attributes.padding_left;
            var marginRight = props.attributes.margin_right;
            var paddingRight = props.attributes.padding_right;
            var marginBottom = props.attributes.margin_bottom;
            var paddingBottom = props.attributes.padding_bottom;

            //get Categories
            if( allCats.length == 0 ) {
                var catsList = select.getEntityRecords( "taxonomy", "category", { per_page: -1 } );
                if(catsList.length != 0) {
                    allCats = [{label: i18n.__('All'), value: ''}];
                    catsList.forEach(function (val, key) {
                        allCats.push({label: val.name, value: val.id});
                    });
                }
            }



            //get Posts for autocomplete
            if( allPosts.length == 0 ) {

                var posts = select.getEntityRecords( "postType", "post", { per_page: 100 } );

                if( posts != null ) {
                    posts.forEach( function (val, key) {
                        if(val.id == postId) currentPostForAutocomplete = val.title.rendered;
                        allPosts.push( { name: val.title.rendered, id: val.id } );
                    } );
                }
            } else {
                allPosts.forEach( function (val, key) {
                    if(val.id == postId) currentPostForAutocomplete = val.name;
                } );
            }

            var PostAutocomplete = function PostAutocomplete() {

                var autocompleters = [{
                    name: 'post',
                    triggerPrefix: '',
                    options: allPosts,
                    getOptionLabel: function getOptionLabel(option) {
                        return el(
                            'span',
                            null,
                            option.name
                        );
                    },
                    getOptionKeywords: function getOptionKeywords(option) {
                        return [option.name];
                    },
                    isOptionDisabled: function isOptionDisabled(option) {
                        return (option.id == postId) ? true : false;
                    },
                    getOptionCompletion: function getOptionCompletion(option) {
                        props.setAttributes( { postId: option.id } );
                        return el(
                            'abbr',
                            { title: option.name },
                            option.name
                        );
                    }
                }];

                return el(
                    'div',
                    null,
                    el(
                        'p',
                        null,
                        'Post by Title'
                    ),
                    el(
                        Autocomplete,
                        { completers: autocompleters },
                        function (_ref) {
                            var isExpanded = _ref.isExpanded,
                                listBoxId = _ref.listBoxId,
                                activeId = _ref.activeId;
                            return el('div', {
                                className: 'components-text-control__input',
                                contentEditable: true,
                                suppressContentEditableWarning: true,
                                'aria-autocomplete': 'list',
                                'aria-expanded': isExpanded,
                                'aria-owns': listBoxId,
                                'aria-activedescendant': activeId
                            }, currentPostForAutocomplete);
                        }
                    ),
                );
            };

            return [
                el(ServerSideRender, {
                    block: 'stm-gutenberg/single-post',
                    attributes: props.attributes
                }),
                el( BlockControls, null,
                    el( AlignmentToolbar, {
                        value: alignment,
                        onChange: function (alignment) {
                            props.setAttributes ( { alignment: alignment } );
                        }
                    } )
                ),
                el( InspectorControls, { key: 'inspector' }, // Display the block options in the inspector panel.
                    el( components.PanelBody, {
                            title: i18n.__( 'Block Header Settings' ),
                            className: 'stm-posts-header-settings',
                            initialOpen: false,
                        },
                        el( TextControl, {
                            type: 'text',
                            label: i18n.__( 'Title' ),
                            value: title,
                            onChange: function( newTitle ) {
                                props.setAttributes( { title: newTitle } );
                            },
                        } ),
                        el( SelectControl, {
                            label: i18n.__( 'Select Heading Tag' ),
                            value: headingTag,
                            options: headingTags,
                            onChange: function (selected) {
                                props.setAttributes( { headingTag: selected } );
                            }
                        } ),
                        el( TextControl, {
                            type: 'text',
                            label: i18n.__( 'Custom Heading Font Size (14, 16, 17 ...)' ),
                            value: headingCFS,
                            onChange: function( cfs ) {
                                props.setAttributes( { headingCFS: cfs } );
                            },
                        } ),
                        el( SelectControl, {
                            label: i18n.__( 'Header Style' ),
                            value: headerStyle,
                            options: headerStyles,
                            onChange: function (selectedStyle) {
                                props.setAttributes( { headerStyle: selectedStyle } );
                            }
                        } )
                    ),
                    el( components.PanelBody, {
                            title: i18n.__( 'View Settings' ),
                            className: 'stm-posts-view-settings',
                            initialOpen: true,
                        },
                        el( SelectControl, {
                            label: i18n.__( 'Content Width' ),
                            value: contWidth,
                            options: contentWidth,
                            onChange: function( newContWidth ) {
                                props.setAttributes( { contWidth: newContWidth } );
                            },
                        } ),
                        el( SelectControl, {
                            label: i18n.__( 'View Style' ),
                            value: viewStyle,
                            options: viewStylesSinglePost,
                            onChange: function (selectedStyle) {
                                props.setAttributes( { viewStyle: selectedStyle } );
                            }
                        } ),
                        el( 'div', {},
                            i18n.__( 'Background Color' ),
                            el( 'div', {
                                    className: 'stm_gutenberg_flex_left_center stm_admin_color_palette'
                                },
                                el( ColorIndicator, {
                                    colorValue: bgColor
                                } ),
                                el( ColorPalette, {
                                    value: bgColor,
                                    onChange: function (newState) {
                                        props.setAttributes( { bgColor: newState } );
                                    }
                                } ),
                            ),
                        ),
                        el( 'p', {} ),
                        el( 'div', {}, el( CheckBoxControl, {
                            heading: '',
                            label: i18n.__('Background Featured Image'),
                            checked: useFeatImgBG,
                            onChange: function (checked) {
                                props.setAttributes( { useFeatImgBG: checked } )
                            }
                        } )),
                            el("div", null, "CSS Box"), el("div", {
                                    className: "custom-row"
                                }, el("div", {
                                    className: 'mr'
                                }, "Margin"), el("div", {
                                    className: 'mr'
                                }), el("div", null, el(TextControl, {
                                    type: 'text',
                                    value: marginTop,
                                    onChange: newMarginTop => props.setAttributes({
                                        margin_top: newMarginTop
                                    })
                                })), el("div", {
                                    className: 'mr'
                                }), el("div", {
                                    className: 'mr'
                                }), el("div", {
                                    className: 'mr'
                                }), el("div", {
                                    className: 'pd'
                                }, "Padding"), el("div", null, el(TextControl, {
                                    type: 'text',
                                    value: paddingTop,
                                    onChange: newPaddingTop => props.setAttributes({
                                        padding_top: newPaddingTop
                                    })
                                })), el("div", {
                                    className: 'pd'
                                }), el("div", {
                                    className: 'mr'
                                }), el("div", null, el(TextControl, {
                                    type: 'text',
                                    value: marginLeft,
                                    onChange: newMarginLeft => props.setAttributes({
                                        margin_left: newMarginLeft
                                    })
                                })), el("div", null, el(TextControl, {
                                    type: 'text',
                                    value: paddingLeft,
                                    onChange: newPaddingLeft => props.setAttributes({
                                        padding_left: newPaddingLeft
                                    })
                                })), el("div", null), el("div", null, el(TextControl, {
                                    type: 'text',
                                    value: paddingRight,
                                    onChange: newPaddingRight => props.setAttributes({
                                        padding_right: newPaddingRight
                                    })
                                })), el("div", null, el(TextControl, {
                                    type: 'text',
                                    value: marginRight,
                                    onChange: newMarginRight => props.setAttributes({
                                        margin_right: newMarginRight
                                    })
                                })), el("div", {
                                    className: 'mr'
                                }), el("div", {
                                    className: 'pd'
                                }), el("div", null, el(TextControl, {
                                    type: 'text',
                                    value: paddingBottom,
                                    onChange: newPaddingBottom => props.setAttributes({
                                        padding_bottom: newPaddingBottom
                                    })
                                })), el("div", {
                                    className: 'pd'
                                }), el("div", {
                                    className: 'mr'
                                }), el("div", {
                                    className: 'mr'
                                }), el("div", {
                                    className: 'mr'
                                }), el("div", null, el(TextControl, {
                                    type: 'text',
                                    value: marginBottom,
                                    onChange: newMarginBottom => props.setAttributes({
                                        margin_bottom: newMarginBottom
                                    })
                                })), el("div", {
                                    className: 'mr'
                                }), el("div", {
                                    className: 'mr'
                                })
                            )
                    ),
                    el( components.PanelBody, {
                            title: i18n.__( 'Post Settings' ),
                            className: 'stm-single-post',
                            initialOpen: false,
                        },
                        el( 'div', {}, el( CheckBoxControl, {
                            heading: '',
                            label: i18n.__('Show Only Sticky Post'),
                            checked: onlySticky,
                            onChange: function (checked) {
                                props.setAttributes( { only_sticky: checked } )
                            }
                        } )),
                        el( SelectControl, {
                            label: i18n.__( 'Categories' ),
                            value: categories,
                            options: allCats,
                            onChange: function (newState) {
                                props.setAttributes( {categories: newState} );
                            }
                        } ),
                        el( SelectControl, {
                            label: i18n.__('Get Post From Category'),
                            value: postOrder,
                            options: getPostFromCategory,
                            onChange: function (newOrder) {
                                props.setAttributes( {postOrder: newOrder} );
                            }
                        } ),
                        el( TextControl, {
                            type: 'text',
                            label: i18n.__( 'Offset Posts' ),
                            value: postOffset,
                            onChange: function( offset ) {
                                props.setAttributes( { postOffset: offset } );
                            },
                        } ),
                        el ( PostAutocomplete, {} ),

                    ),
                ),
            ];

        },

        save: function (props) {
            return null;
        },
    });
} )(
    window.wp.editor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
);