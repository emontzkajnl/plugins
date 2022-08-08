import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/magazine-articles", {
  title: __("Magazine Articles", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Used with magazine post type. Lists articles related to magazine.", "jci-blocks"),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Magazine Articles Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Magazine Articles Placeholder</p>
      </div>
    );
  },
});
