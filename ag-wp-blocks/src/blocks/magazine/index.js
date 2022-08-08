import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/magazine", {
  title: __("Magazine", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("The magazine itself, in magazine post type, pulling calameo id.","jci-blocks"),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Magazine Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Magazine Placeholder</p>
      </div>
    );
  },
});
