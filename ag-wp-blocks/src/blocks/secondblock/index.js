import "./styles.editor.scss";
import { registerBlockType, createBlock } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import Edit from "./edit";
// const { registerBlockType } = wp.blocks;
// const { __ } = wp.i18n;
import { RichText, getColorClassName } from "@wordpress/block-editor";
// const { getColorClassName } = wp.blocksEditor;
import classnames from "classnames";
// import { ColorPalette, PanelBody, ToggleControl } from "@wordpress/components";

const attributes = {
  content: {
    type: "string",
    source: "html",
    selector: "h4",
  },
  alignment: {
    type: "string",
  },
  backgroundColor: {
    type: "string",
  },
  textColor: {
    type: "string",
  },
  customBackgroundColor: {
    type: "string",
  },
  customTextColor: {
    type: "string",
  },
};

registerBlockType("jci-blocks/secondblock", {
  title: __("Second Block Here", "jci-blocks"),
  category: "jci-category",
  icon: "admin-network",
  keywords: [__("photo", "jci-blocks")],
  // supports,

  attributes,
  deprecated: [
    {
      // supports
      attributes: {
        ...attributes,
        content: {
          type: "string",
          source: "html",
          selector: "p",
        },
      },
      save: ({ attributes }) => {
        const {
          content,
          alignment,
          backgroundColor,
          textColor,
          customBackgroundColor,
          customTextColor,
        } = attributes;
        const backgroundClass = getColorClassName(
          "background-color",
          backgroundColor
        );
        const textClass = getColorClassName("color", textColor);
        const classes = classnames({
          [backgroundClass]: backgroundClass,
          [textClass]: textClass,
        });
        return (
          <RichText.Content
            tagName='p'
            className={classes}
            value={content}
            style={{
              textAlign: alignment,
              backgroundColor: backgroundClass
                ? undefined
                : customBackgroundColor,
              color: textClass ? undefined : customTextColor,
            }}
          />
        );
      },
    },
  ],
  transforms: {
    from: [
      {
        type: "block",
        blocks: ["core/paragraph"],
        transform: ({ content, align }) => {
          return createBlock("jci-blocks/secondblock", {
            content: content,
            textAlignment: align,
          });
        },
      },
      {
        type: "prefix",
        prefix: "#",
        transform: () => createBlock("jci-blocks/secondblock"),
      },
    ],
    to: [
      {
        type: "block",
        blocks: ["core/paragraph"],
        isMatch: ({ content }) => {
          if (content) return true;
          return false;
        },
        transform: ({ content, textAlignment }) => {
          return createBlock("core/paragraph", {
            content: content,
            align: textAlignment,
          });
        },
      },
    ],
  },
  edit: Edit,
  save: ({ attributes }) => {
    const {
      content,
      alignment,
      backgroundColor,
      textColor,
      customBackgroundColor,
      customTextColor,
    } = attributes;
    const backgroundClass = getColorClassName(
      "background-color",
      backgroundColor
    );
    const textClass = getColorClassName("color", textColor);
    const classes = classnames({
      [backgroundClass]: backgroundClass,
      [textClass]: textClass,
    });
    return (
      <RichText.Content
        tagName='h4'
        className={classes}
        value={content}
        style={{
          textAlign: alignment,
          backgroundColor: backgroundClass ? undefined : customBackgroundColor,
          color: textClass ? undefined : customTextColor,
        }}
      />
    );
  },
});
