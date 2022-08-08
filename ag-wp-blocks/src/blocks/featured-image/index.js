import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/featured-image", {
  title: __("Featured Image", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Pulls featured image", "jci-blocks"),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Featured Image Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Featured Image Placeholder</p>
      </div>
    );
  },
});
