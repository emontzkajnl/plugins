import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/scroll-load-posts", {
  title: __("Scroll Load Posts", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Scroll Load Posts Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Scroll Load Posts Placeholder</p>
      </div>
    );
  },
});
