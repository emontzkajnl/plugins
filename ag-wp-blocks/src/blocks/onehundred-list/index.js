import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { InnerBlocks } from "@wordpress/block-editor";

registerBlockType("jci-blocks/onehundred-list", {
  title: __("One Hundred List", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  edit: () => {
    return (
      <>
        <div className='jci-block-placeholder'>
          <p>One Hundred List Placeholder</p>
        </div>
        <InnerBlocks />
      </>
    );
  },
  save: (props) => {
    // {
    //   console.log("props are ", props);
    // }
    return (
      <div className='jci-block-placeholder'>
        <p>One Hundred List Placeholder</p>
        <InnerBlocks.Content />
      </div>
    );
  },
});
