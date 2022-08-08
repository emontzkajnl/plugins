import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { useBlockProps, RichText } from "@wordpress/block-editor";

registerBlockType("jci-blocks/post-title", {
  title: __("Post Title", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  attributes: {
    content: {
      type: "string",
      // source: "html",
      // selector: "h1",
    },
  },
  edit: ({ attributes, setAttributes }) => {
    const blockProps = useBlockProps();
    return (
      <RichText
        {...blockProps}
        tagName='h1'
        value={attributes.content}
        onChange={(content) => setAttributes({ content })}
        placeholder={__("Post Title...")}
      />
    );
  },
  save: () => {
    return null;
  },
  // save: ({ attributes }) => {
  //   const blockProps = useBlockProps.save();
  //   return (
  //     <RichText.Content
  //       {...blockProps}
  //       tagName='h2'
  //       value={attributes.content}
  //     />
  //   );
  // },
});
