class StmIconPicker extends React.Component {
  constructor(props) {
    super(props);
    this.icons = [{
      "name": "Ico 1",
      "value": '<svg><path style="text-indent:0;text-align:start;line-height:normal;text-transform:none;block-progression:tb;-inkscape-font-specification:Bitstream Vera Sans" d="M 13 0 C 9.5383686 0 6.4253799 0.7476362 4.09375 1.875 C 2.927935 2.4386819 1.9388635 3.0977892 1.21875 3.84375 C 0.49863654 4.5897108 0 5.4844771 0 6.46875 C 0 7.091349 0.24739616 7.6537054 0.59375 8.15625 C 0.45701447 8.4394083 0.36522638 8.73441 0.25 9.09375 C 0.05246907 9.7097614 -0.20390602 10.33182 0.125 11.1875 C 0.5456602 12.277851 1.6603643 12.770884 2.75 12.84375 C 2.809819 12.91372 2.9301327 13.118733 3.0625 13.375 C 3.1948673 13.631267 3.3513506 13.928821 3.59375 14.25 C 3.8361494 14.571179 4.2219537 14.948929 4.78125 15.0625 C 5.3220078 15.172846 5.7015418 15.05221 6.0625 14.9375 C 6.4234582 14.822794 6.7641097 14.672215 7.0625 14.5625 C 7.3608903 14.452785 7.6374458 14.38619 7.75 14.375 C 8.9018962 15.590449 9.8434645 16.282015 10.84375 16.46875 C 11.889879 16.664043 12.82351 16.133908 13.375 15.5625 C 13.92942 14.987726 14.388127 14.674661 14.6875 14.5625 C 14.986873 14.450339 15.037304 14.50372 15.03125 14.5 C 15.444497 14.753568 16.106269 15.090968 16.84375 15.375 C 17.212491 15.517016 17.598909 15.630348 17.96875 15.71875 C 18.338591 15.80715 18.678084 15.912921 19.15625 15.8125 C 19.787005 15.680825 20.369024 15.314869 20.71875 14.90625 C 21.042456 14.528033 21.283963 14.11122 21.75 13.53125 C 21.761244 13.527726 21.80941 13.487365 21.90625 13.46875 C 22.099929 13.43152 22.419085 13.42873 22.78125 13.40625 C 23.505581 13.36129 24.368167 13.3498 25.21875 13 C 25.570824 12.855259 25.85596 12.501002 25.96875 12.1875 C 26.08154 11.873998 26.089345 11.615125 26.0625 11.375 C 26.00881 10.894749 25.846984 10.485562 25.65625 10.0625 C 25.438466 9.5794394 25.183275 9.1439725 24.9375 8.75 C 25.559157 8.1210636 26 7.3487261 26 6.46875 C 26 5.4843692 25.501387 4.5897906 24.78125 3.84375 C 24.061113 3.0977094 23.072079 2.4386216 21.90625 1.875 C 19.574592 0.7477568 16.461581 0 13 0 z M 13 2 C 16.165419 2 19.050908 2.7149932 21.0625 3.6875 C 22.068296 4.1737534 22.849356 4.7378219 23.34375 5.25 C 23.838144 5.7621781 24 6.1976308 24 6.46875 C 24 6.7394771 23.847393 7.0457659 23.375 7.46875 C 22.902607 7.8917341 22.132039 8.3373353 21.125 8.71875 C 19.110921 9.4815795 16.211141 10 13 10 C 9.788859 10 6.8890785 9.4815795 4.875 8.71875 C 3.8679607 8.3373353 3.097393 7.8917341 2.625 7.46875 C 2.152607 7.0457659 2 6.7394771 2 6.46875 C 2 6.1980229 2.1618322 5.7621642 2.65625 5.25 C 3.1506678 4.7378358 3.93169 4.1738181 4.9375 3.6875 C 6.9491201 2.7148638 9.8346314 2 13 2 z M 11 3 C 10.447715 3 10 3.4477153 10 4 C 10 4.5522847 10.447715 5 11 5 C 11.552285 5 12 4.5522847 12 4 C 12 3.4477153 11.552285 3 11 3 z M 8 4 C 7.4477153 4 7 4.4477153 7 5 C 7 5.5522847 7.4477153 6 8 6 C 8.5522847 6 9 5.5522847 9 5 C 9 4.4477153 8.5522847 4 8 4 z M 10 6 C 9.4477153 6 9 6.4477153 9 7 C 9 7.5522847 9.4477153 8 10 8 C 10.552285 8 11 7.5522847 11 7 C 11 6.4477153 10.552285 6 10 6 z M 2.1875 9.625 C 2.7630937 9.9876851 3.4129703 10.312234 4.15625 10.59375 C 6.4854215 11.475921 9.584141 12 13 12 C 16.415859 12 19.514579 11.475921 21.84375 10.59375 C 22.377519 10.391586 22.864081 10.178999 23.3125 9.9375 C 23.495559 10.228744 23.703306 10.563484 23.84375 10.875 C 23.91202 11.026429 23.8968 11.094481 23.9375 11.21875 C 23.564086 11.28585 23.189623 11.37314 22.65625 11.40625 C 22.279415 11.42964 21.907696 11.45889 21.53125 11.53125 C 21.154804 11.60361 20.703129 11.656077 20.3125 12.125 C 19.695571 12.865738 19.367774 13.383119 19.1875 13.59375 C 19.01677 13.79323 19.071156 13.784131 18.78125 13.84375 C 18.76588 13.836003 18.616165 13.823925 18.4375 13.78125 C 18.199279 13.724309 17.875009 13.620359 17.5625 13.5 C 16.937481 13.259282 16.283503 12.928932 16.09375 12.8125 C 15.473696 12.431783 14.694877 12.427161 14 12.6875 C 13.305123 12.947839 12.60483 13.432024 11.90625 14.15625 C 11.57174 14.502842 11.527871 14.588957 11.21875 14.53125 C 10.909629 14.473543 10.155108 14.054772 9.0625 12.875 C 8.6297633 12.407566 7.9759771 12.330784 7.53125 12.375 C 7.0865229 12.419216 6.7242347 12.55909 6.375 12.6875 C 6.0257653 12.81591 5.709323 12.9548 5.46875 13.03125 C 5.3611997 13.065428 5.2766557 13.089656 5.21875 13.09375 C 5.2095273 13.084674 5.2078143 13.089417 5.1875 13.0625 C 5.1062431 12.954835 4.9830077 12.707108 4.84375 12.4375 C 4.7044923 12.167892 4.5553371 11.851869 4.28125 11.53125 C 4.0071629 11.210631 3.5145131 10.869747 2.9375 10.84375 C 2.3124623 10.815117 2.1126307 10.661518 2.03125 10.5 C 2.0528768 10.421331 2.0497732 10.050804 2.15625 9.71875 C 2.166041 9.6882163 2.1763503 9.6563194 2.1875 9.625 z M 0.25 13.8125 C 0.076082031 13.901345 0.03125 14.252813 0.03125 15 C 0.03125 18.306 5.834 21.96875 13 21.96875 C 20.166 21.96875 25.96875 18.307 25.96875 15 C 25.96875 11.015 23.729 18.09275 13 18.09375 C 3.878875 18.09375 1.0036445 13.427505 0.25 13.8125 z M 0.90625 18.96875 A 1.0001 1.0001 0 0 0 0.78125 19 A 1.0001 1.0001 0 0 0 0 20 C 0 21.014722 0.51296919 21.930199 1.25 22.65625 C 1.9870308 23.382301 2.9558367 23.981649 4.125 24.46875 C 6.4633266 25.442952 9.5739302 26 13 26 C 16.42607 26 19.536673 25.442952 21.875 24.46875 C 23.044163 23.981649 24.012969 23.382301 24.75 22.65625 C 25.487031 21.930199 26 21.014722 26 20 A 1.0001 1.0001 0 1 0 24 20 C 24 20.366278 23.82125 20.748363 23.34375 21.21875 C 22.86625 21.689137 22.096212 22.207351 21.09375 22.625 C 19.088827 23.460298 16.20093 24 13 24 C 9.7990698 24 6.9111734 23.460298 4.90625 22.625 C 3.9037883 22.207351 3.1337504 21.689137 2.65625 21.21875 C 2.1787496 20.748363 2 20.366278 2 20 A 1.0001 1.0001 0 0 0 0.90625 18.96875 z" overflow="visible" enable-background="accumulate" font-family="Bitstream Vera Sans"/></svg>'
    }, {
      "name": "Ico 2",
      "value": '<svg2><path style="text-indent:0;text-align:start;line-height:normal;text-transform:none;block-progression:tb;-inkscape-font-specification:Bitstream Vera Sans" d="M 13 0 C 9.5383686 0 6.4253799 0.7476362 4.09375 1.875 C 2.927935 2.4386819 1.9388635 3.0977892 1.21875 3.84375 C 0.49863654 4.5897108 0 5.4844771 0 6.46875 C 0 7.091349 0.24739616 7.6537054 0.59375 8.15625 C 0.45701447 8.4394083 0.36522638 8.73441 0.25 9.09375 C 0.05246907 9.7097614 -0.20390602 10.33182 0.125 11.1875 C 0.5456602 12.277851 1.6603643 12.770884 2.75 12.84375 C 2.809819 12.91372 2.9301327 13.118733 3.0625 13.375 C 3.1948673 13.631267 3.3513506 13.928821 3.59375 14.25 C 3.8361494 14.571179 4.2219537 14.948929 4.78125 15.0625 C 5.3220078 15.172846 5.7015418 15.05221 6.0625 14.9375 C 6.4234582 14.822794 6.7641097 14.672215 7.0625 14.5625 C 7.3608903 14.452785 7.6374458 14.38619 7.75 14.375 C 8.9018962 15.590449 9.8434645 16.282015 10.84375 16.46875 C 11.889879 16.664043 12.82351 16.133908 13.375 15.5625 C 13.92942 14.987726 14.388127 14.674661 14.6875 14.5625 C 14.986873 14.450339 15.037304 14.50372 15.03125 14.5 C 15.444497 14.753568 16.106269 15.090968 16.84375 15.375 C 17.212491 15.517016 17.598909 15.630348 17.96875 15.71875 C 18.338591 15.80715 18.678084 15.912921 19.15625 15.8125 C 19.787005 15.680825 20.369024 15.314869 20.71875 14.90625 C 21.042456 14.528033 21.283963 14.11122 21.75 13.53125 C 21.761244 13.527726 21.80941 13.487365 21.90625 13.46875 C 22.099929 13.43152 22.419085 13.42873 22.78125 13.40625 C 23.505581 13.36129 24.368167 13.3498 25.21875 13 C 25.570824 12.855259 25.85596 12.501002 25.96875 12.1875 C 26.08154 11.873998 26.089345 11.615125 26.0625 11.375 C 26.00881 10.894749 25.846984 10.485562 25.65625 10.0625 C 25.438466 9.5794394 25.183275 9.1439725 24.9375 8.75 C 25.559157 8.1210636 26 7.3487261 26 6.46875 C 26 5.4843692 25.501387 4.5897906 24.78125 3.84375 C 24.061113 3.0977094 23.072079 2.4386216 21.90625 1.875 C 19.574592 0.7477568 16.461581 0 13 0 z M 13 2 C 16.165419 2 19.050908 2.7149932 21.0625 3.6875 C 22.068296 4.1737534 22.849356 4.7378219 23.34375 5.25 C 23.838144 5.7621781 24 6.1976308 24 6.46875 C 24 6.7394771 23.847393 7.0457659 23.375 7.46875 C 22.902607 7.8917341 22.132039 8.3373353 21.125 8.71875 C 19.110921 9.4815795 16.211141 10 13 10 C 9.788859 10 6.8890785 9.4815795 4.875 8.71875 C 3.8679607 8.3373353 3.097393 7.8917341 2.625 7.46875 C 2.152607 7.0457659 2 6.7394771 2 6.46875 C 2 6.1980229 2.1618322 5.7621642 2.65625 5.25 C 3.1506678 4.7378358 3.93169 4.1738181 4.9375 3.6875 C 6.9491201 2.7148638 9.8346314 2 13 2 z M 11 3 C 10.447715 3 10 3.4477153 10 4 C 10 4.5522847 10.447715 5 11 5 C 11.552285 5 12 4.5522847 12 4 C 12 3.4477153 11.552285 3 11 3 z M 8 4 C 7.4477153 4 7 4.4477153 7 5 C 7 5.5522847 7.4477153 6 8 6 C 8.5522847 6 9 5.5522847 9 5 C 9 4.4477153 8.5522847 4 8 4 z M 10 6 C 9.4477153 6 9 6.4477153 9 7 C 9 7.5522847 9.4477153 8 10 8 C 10.552285 8 11 7.5522847 11 7 C 11 6.4477153 10.552285 6 10 6 z M 2.1875 9.625 C 2.7630937 9.9876851 3.4129703 10.312234 4.15625 10.59375 C 6.4854215 11.475921 9.584141 12 13 12 C 16.415859 12 19.514579 11.475921 21.84375 10.59375 C 22.377519 10.391586 22.864081 10.178999 23.3125 9.9375 C 23.495559 10.228744 23.703306 10.563484 23.84375 10.875 C 23.91202 11.026429 23.8968 11.094481 23.9375 11.21875 C 23.564086 11.28585 23.189623 11.37314 22.65625 11.40625 C 22.279415 11.42964 21.907696 11.45889 21.53125 11.53125 C 21.154804 11.60361 20.703129 11.656077 20.3125 12.125 C 19.695571 12.865738 19.367774 13.383119 19.1875 13.59375 C 19.01677 13.79323 19.071156 13.784131 18.78125 13.84375 C 18.76588 13.836003 18.616165 13.823925 18.4375 13.78125 C 18.199279 13.724309 17.875009 13.620359 17.5625 13.5 C 16.937481 13.259282 16.283503 12.928932 16.09375 12.8125 C 15.473696 12.431783 14.694877 12.427161 14 12.6875 C 13.305123 12.947839 12.60483 13.432024 11.90625 14.15625 C 11.57174 14.502842 11.527871 14.588957 11.21875 14.53125 C 10.909629 14.473543 10.155108 14.054772 9.0625 12.875 C 8.6297633 12.407566 7.9759771 12.330784 7.53125 12.375 C 7.0865229 12.419216 6.7242347 12.55909 6.375 12.6875 C 6.0257653 12.81591 5.709323 12.9548 5.46875 13.03125 C 5.3611997 13.065428 5.2766557 13.089656 5.21875 13.09375 C 5.2095273 13.084674 5.2078143 13.089417 5.1875 13.0625 C 5.1062431 12.954835 4.9830077 12.707108 4.84375 12.4375 C 4.7044923 12.167892 4.5553371 11.851869 4.28125 11.53125 C 4.0071629 11.210631 3.5145131 10.869747 2.9375 10.84375 C 2.3124623 10.815117 2.1126307 10.661518 2.03125 10.5 C 2.0528768 10.421331 2.0497732 10.050804 2.15625 9.71875 C 2.166041 9.6882163 2.1763503 9.6563194 2.1875 9.625 z M 0.25 13.8125 C 0.076082031 13.901345 0.03125 14.252813 0.03125 15 C 0.03125 18.306 5.834 21.96875 13 21.96875 C 20.166 21.96875 25.96875 18.307 25.96875 15 C 25.96875 11.015 23.729 18.09275 13 18.09375 C 3.878875 18.09375 1.0036445 13.427505 0.25 13.8125 z M 0.90625 18.96875 A 1.0001 1.0001 0 0 0 0.78125 19 A 1.0001 1.0001 0 0 0 0 20 C 0 21.014722 0.51296919 21.930199 1.25 22.65625 C 1.9870308 23.382301 2.9558367 23.981649 4.125 24.46875 C 6.4633266 25.442952 9.5739302 26 13 26 C 16.42607 26 19.536673 25.442952 21.875 24.46875 C 23.044163 23.981649 24.012969 23.382301 24.75 22.65625 C 25.487031 21.930199 26 21.014722 26 20 A 1.0001 1.0001 0 1 0 24 20 C 24 20.366278 23.82125 20.748363 23.34375 21.21875 C 22.86625 21.689137 22.096212 22.207351 21.09375 22.625 C 19.088827 23.460298 16.20093 24 13 24 C 9.7990698 24 6.9111734 23.460298 4.90625 22.625 C 3.9037883 22.207351 3.1337504 21.689137 2.65625 21.21875 C 2.1787496 20.748363 2 20.366278 2 20 A 1.0001 1.0001 0 0 0 0.90625 18.96875 z" overflow="visible" enable-background="accumulate" font-family="Bitstream Vera Sans"/></svg2>'
    }];
    this.state = {
      name: 'icon',
      icon: this.icons[0].value
    };
  }

  onChangeInput(e) {
    console.log('ad' + e.target.value);
  }

  onChangeIco(e) {
    this.setState({
      icon: e.target.value
    });
    e.onCIP();
  }

  render() {
    return React.createElement("div", {
      className: "stm-icon-picker"
    }, React.createElement("input", {
      id: "icon_set",
      onChange: this.onChangeInput.bind(this),
      type: "text",
      value: this.state.icon
    }), React.createElement("select", {
      className: "c-select-field__menu",
      value: this.state.icon,
      onChange: this.onChangeIco.bind(this),
      id: "icon_field"
    }, this.icons.map((ico, index) => {
      return React.createElement("option", {
        key: index,
        value: ico.value
      }, ico.name);
    })));
  }

}

registerBlockType('stm-gutenberg/icon-box', {
  title: 'STM icon Box',
  icon: 'universal-access-alt',
  category: 'stm_blocks',
  edit: function (props) {
    var select = wp.data.select('core');
    var attributes = props.attributes; //Header attr

    var title = attributes.title;
    var headingTag = attributes.headingTag;
    var headingCFS = attributes.headingCFS;
    var headerStyle = attributes.headerStyle; //block attr

    var contWidth = attributes.contWidth;
    var columns = attributes.columns;
    var iconNew = attributes.icon;
    var marginTop = attributes.margin_top;
    var paddingTop = attributes.padding_top;
    var marginLeft = attributes.margin_left;
    var paddingLeft = attributes.padding_left;
    var marginRight = attributes.margin_right;
    var paddingRight = attributes.padding_right;
    var marginBottom = attributes.margin_bottom;
    var paddingBottom = attributes.padding_bottom;

    function onCIP() {
      console.log('cip');
    }

    return [React.createElement(ServerSideRender, {
      block: 'stm-gutenberg/icon-box',
      attributes: props.attributes
    }), React.createElement(InspectorControls, {
      key: 'inspector'
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
      className: "stm-posts-view-settings",
      initialOpen: false
    }, React.createElement(SelectControl, {
      label: i18n.__('Content Width'),
      value: contWidth,
      options: contentWidth,
      onChange: newContWidth => props.setAttributes({
        contWidth: newContWidth
      })
    }), React.createElement(SelectControl, {
      label: i18n.__('Columns'),
      value: columns,
      options: numColumns,
      onChange: cols => props.setAttributes({
        columns: cols
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
      title: i18n.__('icon Box Settings'),
      className: "stm-list-view",
      initialOpen: true
    }, React.createElement(StmIconPicker, null)))];
  },
  save: function (props) {
    return null;
  }
});