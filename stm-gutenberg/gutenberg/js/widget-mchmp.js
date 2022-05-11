registerBlockType('stm-gutenberg/widget-mchmp', {
  title: 'STM Widget Mail Chimp',
  icon: 'universal-access-alt',
  category: 'widgets',

  edit(props) {
    var attributes = props.attributes; //Header attr

    var subTitle = attributes.subtitle;
    var title = attributes.title;
    var headingTag = attributes.headingTag;
    var headingCFS = attributes.headingCFS;
    var headerStyle = attributes.headerStyle;
    var viewStyle = attributes.viewStyle;
    var bgColor = props.attributes.bgColor;
    var showtermOfUse = attributes.show_termofuse;
    var touLink = attributes.tou_link;
    var marginTop = props.attributes.margin_top;
    var paddingTop = props.attributes.padding_top;
    var marginLeft = props.attributes.margin_left;
    var paddingLeft = props.attributes.padding_left;
    var marginRight = props.attributes.margin_right;
    var paddingRight = props.attributes.padding_right;
    var marginBottom = props.attributes.margin_bottom;
    var paddingBottom = props.attributes.padding_bottom;
    const ShowTermOfUseCheckboxControl = withState({
      isChecked: showtermOfUse
    })(({
      isChecked,
      setState
    }) => React.createElement(CheckboxControl, {
      heading: i18n.__('Show Terms of Use'),
      label: i18n.__('Yes'),
      checked: isChecked,
      onChange: isChecked => {
        setState({
          isChecked
        });
        props.setAttributes({
          show_termofuse: isChecked
        });
      }
    }));
    return [React.createElement(ServerSideRender, {
      block: "stm-gutenberg/widget-mchmp",
      attributes: props.attributes
    }), React.createElement(InspectorControls, {
      key: "inspector"
    }, React.createElement(PanelBody, {
      title: i18n.__('Block Header Settings'),
      className: "stm-posts-header-settings",
      initialOpen: false
    }, React.createElement(TextControl, {
      type: 'text',
      label: i18n.__('Title'),
      value: title,
      onChange: newTitle => props.setAttributes({
        title: newTitle
      })
    }), React.createElement(TextControl, {
      type: 'text',
      label: i18n.__('SubTitle'),
      value: subTitle,
      onChange: newTitle => props.setAttributes({
        subtitle: newTitle
      })
    }), React.createElement(SelectControl, {
      label: i18n.__('Select Heading Tag'),
      value: headingTag,
      options: headingTags,
      onChange: selected => props.setAttributes({
        headingTag: selected
      })
    }), React.createElement(TextControl, {
      type: 'text',
      label: i18n.__('Custom Heading Font Size (14, 16, 17 ...)'),
      value: headingCFS,
      onChange: cfs => props.setAttributes({
        headingCFS: cfs
      })
    }), React.createElement(SelectControl, {
      label: i18n.__('Header Style'),
      value: headerStyle,
      options: headerStyles,
      onChange: selectedStyle => props.setAttributes({
        headerStyle: selectedStyle
      })
    })), React.createElement(PanelBody, {
      title: i18n.__('View Settings'),
      className: 'stm-posts-view-settings',
      initialOpen: false
    }, React.createElement("div", null, i18n.__('Background Color'), React.createElement("div", {
      className: "stm_gutenberg_flex_left_center stm_admin_color_palette"
    }, React.createElement(ColorIndicator, {
      colorValue: bgColor
    }), React.createElement(ColorPalette, {
      value: bgColor,
      onChange: newColor => props.setAttributes({
        bgColor: newColor
      })
    }))), React.createElement(SelectControl, {
      label: i18n.__('View Style'),
      value: viewStyle,
      options: mlchmpStyles,
      onChange: selectedStyle => props.setAttributes({
        viewStyle: selectedStyle
      })
    }), React.createElement(ShowTermOfUseCheckboxControl, null), React.createElement(TextControl, {
      type: 'text',
      label: i18n.__('Terms Of Use Link'),
      value: touLink,
      onChange: link => props.setAttributes({
        tou_link: link
      })
    }), React.createElement("div", null, "CSS Box"), React.createElement("div", {
      className: "custom-row"
    }, React.createElement("div", {
      className: 'mr'
    }, "Margin"), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", null, React.createElement(TextControl, {
      type: 'text',
      value: marginTop,
      onChange: newMarginTop => props.setAttributes({
        margin_top: newMarginTop
      })
    })), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", {
      className: 'pd'
    }, "Padding"), React.createElement("div", null, React.createElement(TextControl, {
      type: 'text',
      value: paddingTop,
      onChange: newPaddingTop => props.setAttributes({
        padding_top: newPaddingTop
      })
    })), React.createElement("div", {
      className: 'pd'
    }), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", null, React.createElement(TextControl, {
      type: 'text',
      value: marginLeft,
      onChange: newMarginLeft => props.setAttributes({
        margin_left: newMarginLeft
      })
    })), React.createElement("div", null, React.createElement(TextControl, {
      type: 'text',
      value: paddingLeft,
      onChange: newPaddingLeft => props.setAttributes({
        padding_left: newPaddingLeft
      })
    })), React.createElement("div", null), React.createElement("div", null, React.createElement(TextControl, {
      type: 'text',
      value: paddingRight,
      onChange: newPaddingRight => props.setAttributes({
        padding_right: newPaddingRight
      })
    })), React.createElement("div", null, React.createElement(TextControl, {
      type: 'text',
      value: marginRight,
      onChange: newMarginRight => props.setAttributes({
        margin_right: newMarginRight
      })
    })), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", {
      className: 'pd'
    }), React.createElement("div", null, React.createElement(TextControl, {
      type: 'text',
      value: paddingBottom,
      onChange: newPaddingBottom => props.setAttributes({
        padding_bottom: newPaddingBottom
      })
    })), React.createElement("div", {
      className: 'pd'
    }), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", null, React.createElement(TextControl, {
      type: 'text',
      value: marginBottom,
      onChange: newMarginBottom => props.setAttributes({
        margin_bottom: newMarginBottom
      })
    })), React.createElement("div", {
      className: 'mr'
    }), React.createElement("div", {
      className: 'mr'
    }))))];
  },

  save() {
    return null;
  }

});