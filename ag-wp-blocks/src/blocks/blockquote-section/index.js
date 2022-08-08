import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { RichText, InnerBlocks, useBlockProps } from "@wordpress/block-editor";

registerBlockType("jci-blocks/blockquote-section", {
  title: __("Blockquote Section", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  attributes: {
    blockquote: {
      type: "string",
      source: "html",
      selector: "blockquote",
    },
    citation: {
      type: "string",
      source: "html",
      selector: ".citation",
    },
  },
  edit: ({ attributes, setAttributes }) => {
    const blockProps = useBlockProps({
      className: "my-cool-class",
    });
    return (
      <>
        <RichText
          {...blockProps}
          tagName='div'
          className='citation'
          value={attributes.citation}
          onChange={(citation) => setAttributes({ citation })}
          placeholder={__("Citation...", "jci_blocks")}
        />
        <RichText
          {...blockProps}
          tagName='blockquote'
          value={attributes.blockquote}
          onChange={(blockquote) => setAttributes({ blockquote })}
          placeholder={__("Blockquote...", "jci_blocks")}
        />

        <InnerBlocks />
      </>
    );
  },
  save: ({ attributes }) => {
    const blockProps = useBlockProps.save();
    return (
      <div {...blockProps}>
        <div className='quote-container'>
          <RichText.Content
            className='citation'
            tagName='span'
            value={attributes.citation}
          />
          <RichText.Content
            tagName='blockquote'
            value={attributes.blockquote}
          />
        </div>
        <InnerBlocks.Content />
      </div>
    );
  },
});
