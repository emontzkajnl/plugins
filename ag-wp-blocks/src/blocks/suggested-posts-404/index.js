import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/suggested-posts-404", {
  title: __("Suggested Posts for 404", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Suggest posts based on last segment of URL", "jci-blocks"),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Suggested Posts 404</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});