import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/term-list", {
  title: __("Term List", "jci-blocks"),
  category: "jci-category",
  icon: "leftright",
  keywords: [__("term", "jci-blocks")],
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Term List Placeholder</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});
