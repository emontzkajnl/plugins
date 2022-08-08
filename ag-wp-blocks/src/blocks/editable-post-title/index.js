import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { RichText, useBlockProps } from "@wordpress/block-editor";
import { withSelect } from "@wordpress/data";
// var myTitle = withSelect()

registerBlockType("jci-blocks/editable-post-title", {
  title: __("Editable Post Title", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  attributes: {
    content: {
      type: "string",
      source: "html",
      selector: "h1",
    },
    title: {
      type: "string",
    },
  },
  edit: withSelect((select, props) => {
    // const blockProps = useBlockProps();
    const test = select("core/notices");
    console.log("props ".props);
    return {
      props,
      title: select(""),
    };
  }),
  save: (props) => {
    console.log(props);
    return (
      // <RichText.Content tagName='h1' value={attributes.content} />
      null
    );
  },
});
