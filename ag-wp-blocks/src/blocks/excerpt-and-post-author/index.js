import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/excerpt-and-post-author", {
  title: __("Excerpt and Post Author", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Excerpt and Post Author Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Excerpt and Post Author Placeholder</p>
      </div>
    );
  },
});
