import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/city-list", {
  title: __("City List", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("List all child cities with links on state page", "jci-blocks"),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>City List Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>City List Placeholder</p>
      </div>
    );
  },
});