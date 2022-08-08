import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/best-place-title-section", {
  title: __("Best Place Title Section", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Best Place Title Section Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Best Place Title Section Placeholder</p>
      </div>
    );
  },
});
