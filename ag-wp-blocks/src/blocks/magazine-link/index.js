import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/magazine-link", {
  title: __("Magazine Link", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Sidebar widget that links magazine from place post type.", "jci-blocks"),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Magazine Link Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Magazine Link Placeholder</p>
      </div>
    );
  },
});
