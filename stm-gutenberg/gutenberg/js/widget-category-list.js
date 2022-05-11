registerBlockType('stm-gutenberg/widget-category-list', {
  title: 'STM Widget Category List',
  icon: 'universal-access-alt',
  category: 'widgets',

  edit(props) {
    var attributes = props.attributes; //Header attr

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
    const ShowCountCheckboxControl = withState({
      isChecked: showCount
    })(({
      isChecked,
      setState
    }) => React.createElement(CheckboxControl, {
      heading: i18n.__('Show Count'),
      label: i18n.__('Yes'),
      checked: isChecked,
      onChange: isChecked => {
        setState({
          isChecked
        });
        props.setAttributes({
          showCount: isChecked
        });
      }
    }));
    const HideEmptyCheckboxControl = withState({
      isChecked: hideEmpty
    })(({
      isChecked,
      setState
    }) => React.createElement(CheckboxControl, {
      heading: i18n.__('Hide empty'),
      label: i18n.__('Yes'),
      checked: isChecked,
      onChange: isChecked => {
        setState({
          isChecked
        });
        props.setAttributes({
          hideEmpty: isChecked
        });
      }
    }));
    return [React.createElement(ServerSideRender, {
      block: "stm-gutenberg/widget-category-list",
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
    }, React.createElement(SelectControl, {
      label: i18n.__('View Style'),
      value: viewStyle,
      options: categoryStyles,
      onChange: selectedStyle => props.setAttributes({
        viewStyle: selectedStyle
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
    }))), React.createElement(PanelBody, {
      title: i18n.__('Categories Settings'),
      className: 'stm-categories-settings',
      initialOpen: false
    }, React.createElement(TextControl, {
      type: 'text',
      label: i18n.__('Max Number'),
      value: maxNums,
      onChange: newNum => props.setAttributes({
        max_nums: newNum
      })
    }), React.createElement(ShowCountCheckboxControl, null), React.createElement(HideEmptyCheckboxControl, null)))];
  },

  save() {
    return null;
  }

});