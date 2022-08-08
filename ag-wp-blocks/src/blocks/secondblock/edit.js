import { Component } from "@wordpress/element";
import {
  RichText,
  BlockControls,
  AlignmentToolbar,
  InspectorControls,
  PanelColorSettings,
  withColors,
  ContrastChecker,
} from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";

class Edit extends Component {
  onChangeContent = (content) => {
    this.props.setAttributes({ content });
  };
  onChangeAlignment = (alignment) => {
    this.props.setAttributes({ alignment });
  };
  //   onChangeBackgroundColor = (backgroundColor) => {
  //     this.props.setAttributes({ backgroundColor });
  //   };
  //   onChangeTextColor = (textColor) => {
  //     this.props.setAttributes({ textColor });
  //   };
  render() {
    const {
      className,
      attributes,
      setTextColor,
      setBackgroundColor,
      textColor,
      backgroundColor,
    } = this.props;
    const { content, alignment } = attributes;
    // console.log(this.props);
    return (
      <>
        <InspectorControls>
          <PanelColorSettings
            title={__("Panel", "jci_blocks")}
            colorSettings={[
              {
                value: backgroundColor.color,
                onChange: setBackgroundColor,
                label: __("Background Color", "jci_blocks"),
              },
              {
                value: textColor.color,
                onChange: setTextColor,
                label: __("Text Color", "jci_blocks"),
                colors: [
                  {
                    name: "white",
                    color: "#fff",
                  },
                  {
                    name: "black",
                    color: "#222",
                  },
                ],
              },
            ]}
          />
          <ContrastChecker
            backgroundColor={backgroundColor.color}
            textColor={textColor.color}
          />
        </InspectorControls>
        <BlockControls
          controls={[
            [
              {
                icon: "wordpress",
                title: __("test", "jci_blocks"),
                onClick: () => alert(true),
                isActive: false,
              },
            ],
            [
              {
                icon: "wordpress",
                title: __("test", "jci_blocks"),
                onClick: () => alert(true),
                isActive: false,
              },
            ],
          ]}
        >
          <AlignmentToolbar
            onChange={this.onChangeAlignment}
            value={alignment}
          />
        </BlockControls>
        <RichText
          tagName='p'
          className={className}
          onChange={this.onChangeContent}
          value={content}
          style={{
            textAlign: alignment,
            backgroundColor: backgroundColor.color,
            color: textColor.color,
          }}
        />
      </>
    );
  }
}

export default withColors("backgroundColor", { textColor: "color" })(Edit);
