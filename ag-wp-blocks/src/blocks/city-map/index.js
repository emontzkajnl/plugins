import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/city-map", {
  title: __("City Map", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Display google map for city", "jci-blocks"),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>City Map Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>City Map Placeholder</p>
      </div>
    );
  },
});